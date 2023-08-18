import asyncio
import json
import time
from aiohttp import web

routes = web.RouteTableDef()


def get_data():
    try:
        with open('cache.json', 'r') as file:
            data = json.load(file)
            return data
    except FileNotFoundError:
        return {}


def update_data(job):
    with open('cache.json', 'w') as file:
        json.dump(job, file)


def update_job(job_id, progress, time_=0):
    job = get_data()
    job[job_id] = progress
    update_data(job)

    if progress == 100:
        return

    async def job_updater(job_id, progress):
        await asyncio.sleep(1)
        job = get_data()
        job[job_id] = progress + 10
        update_data(job)

        if progress < 90:  # Limit the recursion to avoid infinite loop
            await update_job(job_id, progress + 10)

    asyncio.ensure_future(job_updater(job_id, progress))


@routes.post('/submit')
async def submit(request):
    job = get_data()
    job_id = f"job:{int(time.time())}"
    job[job_id] = 0
    print(f"\n{job_id}\n\n")
    update_data(job)
    await update_job(job_id, 0)
    return web.Response(text=job_id)


@routes.get('/checkstatus')
async def check_status(request):
    job_id = request.query.get('jobId')
    if not job_id:
        return web.Response(text="No job_id specified")

    job = get_data()
    if job_id in job:
        return web.Response(text=f"Jobstatus: {job[job_id]}%")
    else:
        return web.Response(text="Unidentified job")

app = web.Application()
app.add_routes(routes)

web.run_app(app)
