<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Antlr\Antlr4\Runtime\CommonTokenStream;
use Antlr\Antlr4\Runtime\InputStream;
use Antlr\Antlr4\Runtime\Token;
use App\Compiler\ARM64Generator;
use App\Entorno\Entorno;
use App\Entorno\Tabla;
use App\Expressions\Llamada;
use App\Interprete\CustomErrorListener;
use App\Interprete\CustomVisitor;
use App\Language\GolampiLexer;
use App\Language\GolampiParser;
use App\Utilities\Salida;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'error' => 'Metodo no permitido',
        'detalle' => 'Usa POST para analizar codigo.',
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $raw = file_get_contents('php://input');
    $payload = json_decode($raw !== false ? $raw : '', true);

    if (!is_array($payload) || !array_key_exists('codigo', $payload)) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Solicitud invalida',
            'detalle' => "Se esperaba JSON con la propiedad 'codigo'.",
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    $codigo = (string) $payload['codigo'];

    // Reinicio de estado global para evitar datos retenidos entre ejecuciones.
    Salida::limpiarSalidas();
    Tabla::splice();

    $input = InputStream::fromString($codigo);
    $lexer = new GolampiLexer($input);

    $manejadorErrores = new CustomErrorListener();
    $lexer->removeErrorListeners();
    $lexer->addErrorListener($manejadorErrores);

    $tokens = new CommonTokenStream($lexer);
    $parser = new GolampiParser($tokens);

    $parser->removeErrorListeners();
    $parser->addErrorListener($manejadorErrores);

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

    $tree = $parser->inicio();
    $visitor = new CustomVisitor();
    $ast = $visitor->visit($tree);

    $entornoGlobal = new Entorno(null, 'GLOBAL');

    if (is_array($ast)) {
        foreach ($ast as $instruccion) {
            if ($instruccion === null) {
                continue;
            }

            if (!is_object($instruccion) || !method_exists($instruccion, 'ejecutar')) {
                $tipo = gettype($instruccion);
                Salida::$salidasConsola[] = "Nodo invalido en AST (tipo: {$tipo}), se omite su ejecucion.";
                continue;
            }

            $resultado = $instruccion->ejecutar($entornoGlobal);

            if (is_array($resultado) && isset($resultado['control'])) {
                $control = strtoupper((string) $resultado['control']);
                if ($control === 'BREAK') {
                    Salida::reportarError('Semantico', "'break' fuera de un ciclo o switch.");
                } elseif ($control === 'CONTINUE') {
                    Salida::reportarError('Semantico', "'continue' fuera de un ciclo.");
                } elseif ($control === 'RETURN') {
                    Salida::reportarError('Semantico', "'return' fuera de una funcion.");
                }
            }
        }
    } else {
        Salida::$salidasConsola[] = 'El visitor no devolvio un arreglo de instrucciones valido.';
    }

    // ── Generación de código ARM64 ────────────────────────────────────────────
    // Se ejecuta sobre el AST completo antes de la interpretación,
    // sin afectar el flujo del intérprete existente.
    $codigoARM64 = '';
    $erroresGenerador = [];
    if (is_array($ast)) {
        try {
            $generador   = new ARM64Generator();
            $codigoARM64 = $generador->generar($ast);
        } catch (\Throwable $eg) {
            $erroresGenerador[] = 'Error en generador ARM64: ' . $eg->getMessage();
        }
    }

    // ── Interpretación (Proyecto 1 – se mantiene para pruebas) ───────────────
    $funcionMain = $entornoGlobal->getFuncion('main');

    if ($funcionMain !== null) {
        Salida::$salidasConsola[] = "Ejecutando funcion principal (main)...\n";
        $llamadaMain = new Llamada(0, 0, 'main', []);
        $llamadaMain->ejecutar($entornoGlobal);
    } else {
        Salida::reportarError('Semantico', "La funcion 'main' no esta declarada en el archivo.");
    }

    $salidaConsolaStr = implode("\n", Salida::$salidasConsola);

    $simbolosJSON = [];
    foreach (Tabla::$simbolos as $simbolo) {
        $tipoDato = null;
        try {
            $tipoDato = $simbolo->getTipo($simbolo->tipo);
        } catch (\Throwable $e) {
            $tipoDato = (string) ($simbolo->tipo->name ?? 'DESCONOCIDO');
            Salida::reportarError('Semantico', 'Error al convertir tipo de simbolo: ' . $e->getMessage());
        }

        $simbolosJSON[] = [
            'id' => $simbolo->id,
            'tipoDato' => $tipoDato,
            'entorno' => $simbolo->nombreEntorno,
            'linea' => $simbolo->linea,
            'columna' => $simbolo->columna,
        ];
    }

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

    $erroresSemanticosJSON = [];
    $erroresDetallados = Salida::getErroresDetallados();
    $descripcionesDetalladas = [];

    foreach ($erroresDetallados as $err) {
        $tipo = strtolower((string) ($err['tipo'] ?? ''));

        if ($tipo === 'sintactico') {
            $erroresSintacticosJSON[] = [
                'tipo' => 'Sintactico',
                'descripcion' => $err['descripcion'] ?? '',
                'linea' => $err['linea'] ?? 0,
                'columna' => $err['columna'] ?? 0,
            ];
            continue;
        }

        if ($tipo === 'semantico') {
            $descripcion = (string) ($err['descripcion'] ?? '');
            $descripcionesDetalladas[$descripcion] = true;
            $erroresSemanticosJSON[] = [
                'tipo' => 'Semantico',
                'descripcion' => $descripcion,
                'linea' => $err['linea'] ?? 0,
                'columna' => $err['columna'] ?? 0,
            ];
        }
    }

    // Compatibilidad con errores guardados solo como string.
    foreach (Salida::$errores as $err) {
        if (!is_string($err)) {
            continue;
        }

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

    $erroresJSON = [
        ...$erroresLexicosJSON,
        ...$erroresSintacticosJSON,
        ...$erroresSemanticosJSON,
    ];

    echo json_encode([
        'tokens'              => $tokensJSON,
        'consola'             => $salidaConsolaStr,
        'codigoARM64'         => $codigoARM64,
        'erroresGenerador'    => $erroresGenerador,
        'simbolos'            => $simbolosJSON,
        'erroresLexicos'      => $erroresLexicosJSON,
        'erroresSintacticos'  => $erroresSintacticosJSON,
        'erroresSemanticos'   => $erroresSemanticosJSON,
        'errores'             => $erroresJSON,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Excepcion durante interpretacion',
        'detalle' => $e->getMessage(),
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
