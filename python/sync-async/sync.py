

def synchronous_file_read(file_path):
    with open(file_path, 'r') as file:
        content = file.read()
        # Process the file content as needed
        print(content)


# Usage
print("1")
synchronous_file_read('text.txt')
print("2")
