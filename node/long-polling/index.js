const app = require("express")();
const jobs = {};

app.post("/submit", (req, res) => {
    const jobId = `job:${Date.now()}`;
    jobs[jobId] = 0;
    updateJob(jobId, 0)
    res.end("\n\n" + jobId + "\n\n");
})

app.get("/job", (req, res) => {
    console.log(jobs[req.query.jobId])
    // long polling, don't respond until done
    while (await checkJobComplete(req.query.jobId) == false);
    res.end("\n\nJobStatus: Complete " + jobs[req.query.job] + "%\n\n");
})