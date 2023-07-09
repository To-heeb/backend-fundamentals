import asyncio
import aiofiles


async def asynchronous_file_read(file_path):
    async with aiofiles.open(file_path, 'r') as file:
        content = await file.read()
        # Process the file content as needed
        print(content)
        # print("Content")

# Usage
print("1")
loop = asyncio.get_event_loop()
loop.run_until_complete(asynchronous_file_read('text.txt'))
# asyncio.run(asynchronous_file_read('text.txt'))
print("2")
