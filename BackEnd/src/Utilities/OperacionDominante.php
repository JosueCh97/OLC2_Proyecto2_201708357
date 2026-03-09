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
     * 5: NULL / ERROR
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
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NULL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NULL, Tipo::DECIMAL, Tipo::NULL, Tipo::NULL], // DECIMAL
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // CARACTER
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::CADENA, Tipo::NULL],// CADENA
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL]  // NULL
    ];

    public static array $resta = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NULL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NULL, Tipo::DECIMAL, Tipo::NULL, Tipo::NULL], // DECIMAL
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // CARACTER
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // CADENA
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL]  // NULL
    ];

    public static array $multiplicacion = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NULL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::CADENA, Tipo::NULL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NULL, Tipo::DECIMAL, Tipo::NULL,   Tipo::NULL], // DECIMAL
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL,   Tipo::NULL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL,   Tipo::NULL], // CARACTER
        [Tipo::CADENA,  Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::CADENA, Tipo::NULL], // CADENA
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL,   Tipo::NULL]  // NULL
    ];

    public static array $division = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NULL
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // ENTERO
        [Tipo::DECIMAL, Tipo::DECIMAL, Tipo::NULL, Tipo::DECIMAL, Tipo::NULL, Tipo::NULL], // DECIMAL
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::DECIMAL, Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // CARACTER
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // CADENA
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL]  // NULL
    ];

    public static array $modulo = [
        // ENTERO,      DECIMAL,       BOOLEANO,   CARACTER,      CADENA,     NULL
        [Tipo::ENTERO,  Tipo::NULL,    Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // ENTERO
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // DECIMAL
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // BOOLEANO
        [Tipo::ENTERO,  Tipo::NULL,    Tipo::NULL, Tipo::ENTERO,  Tipo::NULL, Tipo::NULL], // CARACTER
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL], // CADENA
        [Tipo::NULL,    Tipo::NULL,    Tipo::NULL, Tipo::NULL,    Tipo::NULL, Tipo::NULL]  // NULL
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
            default => Tipo::NULL
        };
    }
}