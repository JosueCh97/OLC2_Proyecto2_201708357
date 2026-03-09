<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use App\Language\GolampiLexer;
use App\Language\GolampiParser;
use App\Interprete\CustomVisitor;
use App\Entorno\Entorno;
use App\Utilities\Salida;

try {
    $input = InputStream::fromPath(__DIR__ . '/test2.txt');
    $lexer = new GolampiLexer($input);
    $tokens = new CommonTokenStream($lexer);
    $parser = new GolampiParser($tokens);
    $manejadorErrores = new \App\Interprete\CustomErrorListener();
    $parser->removeErrorListeners();
    $parser->addErrorListener($manejadorErrores);
    $tree = $parser->inicio();
    
    if ($manejadorErrores->hayErrores) {
        echo "\n⚠️ Se encontraron errores de sintaxis.\n\n";
    }

    $visitor = new CustomVisitor();
    $ast = $visitor->visit($tree);
    $entornoGlobal = new Entorno(null, "GLOBAL");

    if (is_array($ast)) {
        foreach ($ast as $instruccion) {
            if ($instruccion !== null) {
                $instruccion->ejecutar($entornoGlobal);
            }
        }
    }

    echo "\n--- SALIDA DE CONSOLA ---\n";
    echo Salida::getSalida();

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
