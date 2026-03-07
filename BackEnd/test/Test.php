<?php
// Requerir el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use App\Language\GolampiLexer;
use App\Language\GolampiParser;
use App\Interprete\CustomVisitor;

try {
    // 1. Leer el archivo de prueba
    $input = InputStream::fromPath(__DIR__ . '/test.txt');

    // 2. Análisis Léxico (Tokens)
    $lexer = new GolampiLexer($input);
    $tokens = new CommonTokenStream($lexer);

        // ... [código anterior de Test.php] ...
    $parser = new \App\Language\GolampiParser($tokens);

    // 1. Instanciamos nuestro detector de errores
    $manejadorErrores = new \App\Interprete\CustomErrorListener();

    // 2. Le quitamos al Parser el detector por defecto y le ponemos el nuestro
    $parser->removeErrorListeners();
    $parser->addErrorListener($manejadorErrores);

    // 3. Generamos el árbol
    $tree = $parser->inicio();

    // 4. Avisamos si hubo errores, ¡PERO YA NO DETENEMOS EL PROGRAMA!
    if ($manejadorErrores->hayErrores) {
        echo "\n⚠️ Se encontraron errores de sintaxis. Se ignorarán las sentencias mal escritas y se continuará con el resto.\n\n";
        // Eliminamos el exit(); de aquí
    }

    // 5. Llamamos al Visitor (Analizará las sentencias buenas y saltará las malas)
    $visitor = new \App\Interprete\CustomVisitor();
    $visitor->visit($tree);

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}