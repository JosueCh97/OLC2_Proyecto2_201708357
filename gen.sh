#!/bin/bash
cd BackEnd/grammar/
antlr4 -Dlanguage=PHP -visitor -no-listener -package App\\Language -o ../src/Language Golampi.g4
npm run dev
cd /home/josuelts/Escritorio/OLC2P1/BackEnd
php -S 127.0.0.1:8000