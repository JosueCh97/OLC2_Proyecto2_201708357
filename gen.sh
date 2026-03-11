#!/bin/bash
cd BackEnd/grammar/
antlr4 -Dlanguage=PHP -visitor -no-listener -package App\\Language -o ../src/Language Golampi.g4