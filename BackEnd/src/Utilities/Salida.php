<?php
namespace App\Utilities;

// use App\Utilities\Error; // Descomenta esto cuando tengas tu clase Error

class Salida {
    public static array $salidasConsola = [];
    public static array $errores = [];
    public static array $erroresDetallados = [];

    public static function reportarError(
        string $tipo,
        string $descripcion,
        ?int $linea = null,
        ?int $columna = null,
        bool $agregarConsola = true
    ): void {
        $entrada = [
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'linea' => $linea,
            'columna' => $columna,
        ];

        self::$erroresDetallados[] = $entrada;

        $texto = self::formatearError($entrada);
        self::$errores[] = $texto;
        if ($agregarConsola) {
            self::$salidasConsola[] = $texto;
        }
    }

    private static function formatearError(array $err): string {
        $tipo = $err['tipo'] ?? 'General';
        $descripcion = $err['descripcion'] ?? 'Error';
        $linea = $err['linea'] ?? null;
        $columna = $err['columna'] ?? null;

        $ubicacion = '';
        if ($linea !== null && $columna !== null) {
            $ubicacion = " [Linea {$linea}, Columna {$columna}]";
        } elseif ($linea !== null) {
            $ubicacion = " [Linea {$linea}]";
        }

        return "❌ Error {$tipo}{$ubicacion}: {$descripcion}";
    }

    public static function getSalida(): string {
        $out = '';
        
        // Recorrer las salidas de consola
        $cantidadSalidas = count(self::$salidasConsola);
        for ($i = 0; $i < $cantidadSalidas; $i++) {
            $out .= self::$salidasConsola[$i];
            if ($i < $cantidadSalidas - 1) {
                $out .= "\n";
            }
        }

        // Recorrer los errores
        $cantidadErrores = count(self::$errores);
        if ($cantidadErrores > 0) {
            if ($out !== "") {
                $out .= "\n\n↳ ERRORES\n";
            } else {
                $out .= "↳ ERRORES\n";
            }
            
            for ($i = 0; $i < $cantidadErrores; $i++) {
                // Los errores ya vienen como strings, no necesitan toString()
                $out .= self::$errores[$i];
                if ($i < $cantidadErrores - 1) {
                    $out .= "\n";
                }
            }
        }
        return $out;
    }

    public static function getErrores(): array {
        return self::$errores;
    }

    public static function getErroresDetallados(): array {
        return self::$erroresDetallados;
    }

    public static function limpiarSalidas(): void {
        self::$salidasConsola = [];
        self::$errores = [];
        self::$erroresDetallados = [];
    }
}