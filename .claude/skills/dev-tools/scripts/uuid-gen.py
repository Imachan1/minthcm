#!/usr/bin/env python3
import sys
import uuid

def main():
    count = int(sys.argv[1]) if len(sys.argv) > 1 else 1
    for _ in range(count):
        print(uuid.uuid4())

if __name__ == "__main__":
    main()
