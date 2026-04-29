<?php

/**
 * Router para el servidor de desarrollo PHP integrado (php -S).
 *
 * Uso:  cd BackEnd && php -S 127.0.0.1:8000 router.php
 *
 * Por qué existe:
 *   El servidor integrado de PHP es monoproceso: si analizar.php lanza una
 *   excepción no capturada (o se agota la memoria/tiempo) el proceso muere.
 *   Este router configura límites seguros antes de despachar cada petición,
 *   de modo que errores fatales devuelven HTTP 500 en lugar de matar el
 *   servidor entero.
 *
 * Configuración de seguridad aplicada en cada request:
 *   · memory_limit   = 512 MB  (ANTLR4 en PHP puede ser voraz)
 *   · max_execution_time = 60 s
 *   · display_errors = 0       (los errores van al log, no a la respuesta)
 */

ini_set('memory_limit', '512M');
ini_set('max_execution_time', '60');
ini_set('display_errors', '0');
ini_set('log_errors', '1');

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));

// Archivos estáticos que existen en el document root → PHP los sirve solo
if ($uri !== '/' && is_file(__DIR__ . $uri)) {
    return false;
}

// Rutas PHP explícitas
$phpMap = [
    '/api/analizar.php' => __DIR__ . '/api/analizar.php',
    '/analizar.php'     => __DIR__ . '/api/analizar.php',
];

foreach ($phpMap as $route => $file) {
    if ($uri === $route || str_starts_with($uri, $route . '?')) {
        if (is_file($file)) {
            // register_shutdown_function captura fatales y emite JSON válido
            register_shutdown_function(function () {
                $err = error_get_last();
                if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
                    if (!headers_sent()) {
                        http_response_code(500);
                        header('Content-Type: application/json; charset=utf-8');
                        header('Access-Control-Allow-Origin: *');
                    }
                    echo json_encode([
                        'error'   => 'Error interno del servidor',
                        'detalle' => $err['message'] . ' en ' . $err['file'] . ':' . $err['line'],
                    ]);
                }
            });
            require $file;
            return true;
        }
    }
}

// 404 en JSON para mantener compatibilidad con el frontend
http_response_code(404);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
echo json_encode(['error' => 'Ruta no encontrada', 'uri' => $uri]);