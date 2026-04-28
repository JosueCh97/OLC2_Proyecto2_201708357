<?php
require_once __DIR__ . '/vendor/autoload.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use App\Language\GolampiLexer;
use App\Language\GolampiParser;
use App\Interprete\CustomVisitor;
use App\Compiler\ARM64Generator;

$file = $argv[1] ?? null;
if (!$file || !file_exists($file)) {
    fwrite(STDERR, "Uso: php compilar.php <archivo.go>\n");
    exit(1);
}

$code   = file_get_contents($file);
$input  = InputStream::fromString($code);
$lexer  = new GolampiLexer($input);
$tokens = new CommonTokenStream($lexer);
$parser = new GolampiParser($tokens);
$tree   = $parser->inicio();

$visitor = new CustomVisitor();
$instrucciones = $visitor->visit($tree);

$gen = new ARM64Generator();
echo $gen->generar($instrucciones);
