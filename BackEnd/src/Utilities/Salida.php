<?php
namespace App\Utilities;

// use App\Utilities\Error; // Descomenta esto cuando tengas tu clase Error

class Salida {
    public static array $salidasConsola = [];
    public static array $errores = [];

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

    public static function limpiarSalidas(): void {
        self::$salidasConsola = [];
        self::$errores = [];
    }
}