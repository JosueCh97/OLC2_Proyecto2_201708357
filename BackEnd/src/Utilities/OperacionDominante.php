<?php
namespace App\Utilities;

use App\Utilities\Tipo;

class OperacionDominante {
    /**
     * INDICES DE LA MATRIZ:
     * 0: ENTERO (int32)
     * 1: DECIMAL (float32)
     * 2: BOOLEANO (bool)
     * 3: CARACTER (rune)
     * 4: CADENA (string)
     * 5: NIL / ERROR
     */

    // Función auxiliar para obtener el índice de la matriz a partir del Enum
    private static function getIndex(Tipo $tipo): int {
        return match($tipo) {
            Tipo::ENTERO   => 0,
            Tipo::DECIMAL  => 1,
            Tipo::BOOLEANO => 2,
            Tipo::CARACTER => 3,
            Tipo::CADENA   => 4,
            default        => 5,
        };
    }

    public static array $suma = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NIL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NIL, Tipo::DECIMAL, Tipo::NIL, Tipo::NIL], // DECIMAL
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // CARACTER
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::CADENA, Tipo::NIL],// CADENA
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL]  // NIL
    ];

    public static array $resta = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NIL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NIL, Tipo::DECIMAL, Tipo::NIL, Tipo::NIL], // DECIMAL
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // CARACTER
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // CADENA
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL]  // NIL
    ];

    public static array $multiplicacion = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NIL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::CADENA, Tipo::NIL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NIL, Tipo::DECIMAL, Tipo::NIL,   Tipo::NIL], // DECIMAL
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL,   Tipo::NIL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL,   Tipo::NIL], // CARACTER
        [Tipo::CADENA,  Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::CADENA, Tipo::NIL], // CADENA
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL,   Tipo::NIL]  // NIL
    ];

    public static array $division = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NIL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NIL, Tipo::DECIMAL, Tipo::NIL, Tipo::NIL], // DECIMAL
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // CARACTER
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // CADENA
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL]  // NIL
    ];

    public static array $modulo = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NIL
        [Tipo::ENTERO,  Tipo::NIL,    Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // ENTERO
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // DECIMAL
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::NIL,    Tipo::NIL, Tipo::ENTERO,  Tipo::NIL, Tipo::NIL], // CARACTER
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL], // CADENA
        [Tipo::NIL,    Tipo::NIL,    Tipo::NIL, Tipo::NIL,    Tipo::NIL, Tipo::NIL]  // NIL
    ];

    /**
     * Esta es la función mágica. La usarás en todas tus clases aritméticas.
     */
    public static function getTipo(Tipo $izq, Tipo $der, string $operacion): Tipo {
        $indiceIzq = self::getIndex($izq);
        $indiceDer = self::getIndex($der);

        return match($operacion) {
            '+' => self::$suma[$indiceIzq][$indiceDer],
            '-' => self::$resta[$indiceIzq][$indiceDer],
            '*' => self::$multiplicacion[$indiceIzq][$indiceDer],
            '/' => self::$division[$indiceIzq][$indiceDer],
            '%' => self::$modulo[$indiceIzq][$indiceDer],
            default => Tipo::NIL
        };
    }
}