#!/usr/bin/env python3
import os
import itertools

HEAD = [
"/**",
" * Uac",
" *",
" * @author Vasiliy Horbachenko <shadow.prince@ya.ru>",
" * @copyright 2013 Vasiliy Horbachenko",
" * @version 1.0",
" * @package uac",
" *",
" */"]

def __split_seq_in(s, n):
    for i in range(len(s) - n):
        yield s[i:i+n]

def files_list(path):
    for path, dirs, files in os.walk(path):
        for file in files:
            if file.endswith(".php"):
                yield os.path.join(path, file)

def insert_at(code, i):
    return "\n".join(
            code.splitlines()[:i] +
            HEAD +
            code.splitlines()[i:])

def find_comment_end(lines):
    for i, line in enumerate(lines):
        if line.strip() == "*/":
            return i
    return -1

def update_from(code, s):
    e = find_comment_end(code.splitlines()[s:]) + s
    return insert_at(
            "\n".join(
                code.splitlines()[:s]
                +
                code.splitlines()[e+1:]),
            s)

def process_code(code):
    for i, [a, b, c] in enumerate(__split_seq_in(code.splitlines(), 3)):
        if a.strip() == "<?php":
            if b.strip().startswith("/**"):
                return update_from(code, i+1)
            else:
                return insert_at(code, i+1)
    return code

if __name__ == "__main__":
    for f in files_list("."):
        code = process_code(open(f).read())
        open(f, "w").write(code)
