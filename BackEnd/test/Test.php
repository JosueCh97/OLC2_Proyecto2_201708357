<?php
// Requerir el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use App\Language\GolampiLexer;
use App\Language\GolampiParser;
use App\Interprete\CustomVisitor;
use App\Entorno\Entorno; // <-- IMPORTANTE: Importamos nuestro Entorno
use App\Utilities\Salida; // <-- IMPORTANTE: Importamos la clase Salida para mostrar la salida de consola y errores

try {
    // 1. Leer el archivo de prueba
    $input = InputStream::fromPath(__DIR__ . '/test.txt');

    // 2. Análisis Léxico (Tokens)
    $lexer = new GolampiLexer($input);
    $tokens = new CommonTokenStream($lexer);

    $parser = new GolampiParser($tokens);

    // 1. Instanciamos nuestro detector de errores
    $manejadorErrores = new \App\Interprete\CustomErrorListener();

    // 2. Le quitamos al Parser el detector por defecto y le ponemos el nuestro
    $parser->removeErrorListeners();
    $parser->addErrorListener($manejadorErrores);

    // 3. Generamos el árbol
    $tree = $parser->inicio();

    // 4. Avisamos si hubo errores
    if ($manejadorErrores->hayErrores) {
        echo "\n⚠️ Se encontraron errores de sintaxis. Se ignorarán las sentencias mal escritas y se continuará con el resto.\n\n";
    }

    // 5. Llamamos al Visitor (Analizará y nos devolverá una lista de instrucciones)
    $visitor = new CustomVisitor();
    
    // Ahora guardamos lo que nos devuelve el Visitor en una variable llamada $ast (Abstract Syntax Tree)
    $ast = $visitor->visit($tree); 

    // =========================================================
    // 🚀 FASE DE EJECUCIÓN: DONDE LA MAGIA SUCEDE
    // =========================================================

    // 6. Instanciamos la memoria principal de nuestro programa
    // Le pasamos 'null' porque es el entorno más alto, y le llamamos "GLOBAL"
    $entornoGlobal = new Entorno(null, "GLOBAL");

    echo "\n--- INICIANDO EJECUCIÓN ---\n";
    
    // 7. Recorremos el AST y ejecutamos instrucción por instrucción
    if (is_array($ast)) {
        foreach ($ast as $instruccion) {
            if ($instruccion !== null) {
                // Aquí se llama al método ejecutar() de Primitivo, Asignacion, Declaracion, etc.
                $instruccion->ejecutar($entornoGlobal);
            }
        }
    } else {
        echo "El Visitor no devolvió un arreglo de instrucciones válido.\n";
    }

    // 8. (Opcional - Para depurar) Imprimimos qué quedó guardado en la memoria
    echo "\n--- MEMORIA FINAL (Entorno Global) ---\n";
    
    
    print_r($entornoGlobal->ids);
    // 9. Imprimimos las salidas de consola y errores
    echo "\n--- SALIDA DE CONSOLA ---\n";
    echo \App\Utilities\Salida::getSalida() . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}