<?php
// Requerir el autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\Token;
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

    // 1. Instanciamos nuestro detector de errores
    $manejadorErrores = new \App\Interprete\CustomErrorListener();

    // 2. Conectamos el listener tanto al lexer (errores léxicos) como al parser (sintácticos)
    $lexer->removeErrorListeners();
    $lexer->addErrorListener($manejadorErrores);

    $tokens = new CommonTokenStream($lexer);
    $parser = new GolampiParser($tokens);

    $parser->removeErrorListeners();
    $parser->addErrorListener($manejadorErrores);

    // 3. Capturamos la lista de tokens para la respuesta JSON
    $tokens->fill();
    $tokensJSON = [];
    $vocabulario = $lexer->getVocabulary();

    foreach ($tokens->getAllTokens() as $token) {
        if ($token->getType() === Token::EOF) {
            continue;
        }

        $tipo = $token->getType();
        $nombreTipo = $vocabulario->getSymbolicName($tipo)
            ?? $vocabulario->getLiteralName($tipo)
            ?? (string) $tipo;

        $tokensJSON[] = [
            'tipo' => $nombreTipo,
            'lexema' => $token->getText(),
            'linea' => $token->getLine(),
            'columna' => $token->getCharPositionInLine(),
            'canal' => $token->getChannel(),
        ];
    }

    // 4. Generamos el árbol
    $tree = $parser->inicio();

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

    // 7. Recorremos el AST y ejecutamos instrucción por instrucción (PRIMERA PASADA - CARGA)
    if (is_array($ast)) {
        foreach ($ast as $instruccion) {
            if ($instruccion === null) {
                continue;
            }

            if (!is_object($instruccion) || !method_exists($instruccion, 'ejecutar')) {
                $tipo = gettype($instruccion);
                Salida::$salidasConsola[] = "⚠️ Nodo inválido en AST (tipo: {$tipo}), se omite su ejecución.";
                continue;
            }

            // Aquí se guardan las variables globales y las funciones (incluyendo 'main')
            $instruccion->ejecutar($entornoGlobal);
        }
    } else {
        echo "El Visitor no devolvió un arreglo de instrucciones válido.\n";
    }

    // =========================================================
    // NUEVO PASO 7.5: AUTO-ARRANQUE DE LA FUNCIÓN MAIN (SEGUNDA PASADA)
    // =========================================================
    $funcionMain = $entornoGlobal->getFuncion("main");

    if ($funcionMain !== null) {
        \App\Utilities\Salida::$salidasConsola[] = "🚀 Ejecutando función principal (main)...\n";
        
        // Creamos una Llamada artificial a "main" sin argumentos
        $llamadaMain = new \App\Expressions\Llamada(0, 0, "main", []);
        
        // ¡Damos play a la función main!
        $llamadaMain->ejecutar($entornoGlobal);
        
    } else {
        // Si no hay main, Go no puede iniciar
        \App\Utilities\Salida::$errores[] = "❌ Error: La función 'main' no está declarada en el archivo.";
        \App\Utilities\Salida::$salidasConsola[] = "❌ Error: La función 'main' no está declarada en el archivo.";
    }

    // =========================================================
    // 9. GENERACIÓN DE RESPUESTA JSON (API)
    // =========================================================

    // 1. Preparar la salida de Consola (Unir el arreglo en un solo string)
    $salidaConsolaStr = implode("\n", \App\Utilities\Salida::$salidasConsola);

    // 2. Preparar la Tabla de Símbolos
    $simbolosJSON = [];
    foreach (\App\Entorno\Tabla::$simbolos as $simbolo) {
        $tipoDato = null;
        try {
            $tipoDato = $simbolo->getTipo($simbolo->tipo);
        } catch (\Throwable $e) {
            $tipoDato = (string) ($simbolo->tipo->name ?? 'DESCONOCIDO');
            \App\Utilities\Salida::$errores[] = 'Error al convertir tipo de simbolo: ' . $e->getMessage();
        }

        $simbolosJSON[] = [
            "id" => $simbolo->id,
            "tipoDato" => $tipoDato,
            "entorno" => $simbolo->nombreEntorno,
            "linea" => $simbolo->linea,
            "columna" => $simbolo->columna
        ];
    }

    // 3. Preparar los Errores
    $erroresLexicosJSON = [];
    foreach ($manejadorErrores->erroresLexicos as $err) {
        $erroresLexicosJSON[] = [
            'tipo' => 'Lexico',
            'descripcion' => $err['descripcion'] ?? '',
            'linea' => $err['linea'] ?? 0,
            'columna' => $err['columna'] ?? 0,
        ];
    }

    $erroresSintacticosJSON = [];
    foreach ($manejadorErrores->erroresSintacticos as $err) {
        $erroresSintacticosJSON[] = [
            'tipo' => 'Sintactico',
            'descripcion' => $err['descripcion'] ?? '',
            'linea' => $err['linea'] ?? 0,
            'columna' => $err['columna'] ?? 0,
        ];
    }

    // Sintacticos reportados manualmente desde el visitor
    $erroresDetallados = \App\Utilities\Salida::getErroresDetallados();
    foreach ($erroresDetallados as $err) {
        $tipo = strtolower((string) ($err['tipo'] ?? ''));
        if ($tipo !== 'sintactico') {
            continue;
        }

        $erroresSintacticosJSON[] = [
            'tipo' => 'Sintactico',
            'descripcion' => $err['descripcion'] ?? '',
            'linea' => $err['linea'] ?? 0,
            'columna' => $err['columna'] ?? 0,
        ];
    }

    $erroresSemanticosJSON = [];
    $descripcionesDetalladas = [];
    foreach ($erroresDetallados as $err) {
        $tipo = strtolower((string) ($err['tipo'] ?? ''));
        if ($tipo !== 'semantico') {
            continue;
        }

        $descripcion = (string) ($err['descripcion'] ?? '');
        $descripcionesDetalladas[$descripcion] = true;

        $erroresSemanticosJSON[] = [
            'tipo' => 'Semantico',
            'descripcion' => $descripcion,
            'linea' => $err['linea'] ?? 0,
            'columna' => $err['columna'] ?? 0,
        ];
    }

    // Compatibilidad: errores antiguos guardados solo como string.
    foreach (\App\Utilities\Salida::$errores as $err) {
        if (!is_string($err)) {
            continue;
        }

        // Evitar duplicados si ya vino como error detallado.
        if (isset($descripcionesDetalladas[$err])) {
            continue;
        }

        $linea = 0;
        $columna = 0;
        if (preg_match('/\\[L[ií]nea\s+(\d+)(?:,\s*Columna\s*(\d+))?\]/iu', $err, $m)) {
            $linea = isset($m[1]) ? (int) $m[1] : 0;
            $columna = isset($m[2]) ? (int) $m[2] : 0;
        }

        $erroresSemanticosJSON[] = [
            'tipo' => 'Semantico',
            'descripcion' => $err,
            'linea' => $linea,
            'columna' => $columna,
        ];
    }

    $erroresJSON = [];
    foreach ($erroresLexicosJSON as $err) {
        $erroresJSON[] = $err;
    }
    foreach ($erroresSintacticosJSON as $err) {
        $erroresJSON[] = $err;
    }
    foreach ($erroresSemanticosJSON as $err) {
        $erroresJSON[] = $err;
    }

    // 4. Empaquetar todo en un solo objeto de respuesta
    $respuestaAPI = [
        'tokens' => $tokensJSON,
        'consola' => $salidaConsolaStr,
        'simbolos' => $simbolosJSON,
        'erroresLexicos' => $erroresLexicosJSON,
        'erroresSintacticos' => $erroresSintacticosJSON,
        'erroresSemanticos' => $erroresSemanticosJSON,
        'errores' => $erroresJSON,
    ];

    // 5. Configurar los Headers para que el navegador sepa que es un JSON
    // (Asegurate de no tener ningun echo antes de esta linea)
    header('Content-Type: application/json; charset=utf-8');

    // Si tu frontend esta en otro puerto (ej: React en localhost:3000),
    // descomenta esta linea para evitar el error de CORS:
    // header('Access-Control-Allow-Origin: *');

    // 6. Imprimir el JSON
    echo json_encode($respuestaAPI, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'error' => 'Excepcion durante interpretacion',
        'detalle' => $e->getMessage(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}