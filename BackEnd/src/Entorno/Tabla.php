<?php
namespace App\Entorno;

class Tabla {
    // Esta propiedad estática funcionará como tu variable global
    public static array $simbolos = [];

    public static function push(SimboloTabla $simbolo): void {
        if (self::validarSimbolo($simbolo)) {
            self::$simbolos[] = $simbolo;
        }
    }

    public static function validarSimbolo(SimboloTabla $simbolo): bool {
        foreach (self::$simbolos as $i) {
            if ($i->hash() === $simbolo->hash()) {
                return false;
            }
        }
        return true;
    }
    
    public static function splice(): void {
        // Para limpiar el arreglo en PHP, simplemente lo reasignamos vacío
        self::$simbolos = []; 
    }

    public static function imprimirTabla(): void {
        echo "Tabla de simbolos:\n";
        foreach (self::$simbolos as $simbolo) { 
            echo $simbolo->toString() . "\n";
        }
    }

    public static function toString(): string {
        // En PHP usamos el punto (.) para concatenar en lugar del más (+)
        $table = '╔═' . str_repeat('═', 69) . "═╗\n";
        $table .= '║ ' . str_repeat(' ', 26) . 'TABLA DE SÍMBOLOS' . str_repeat(' ', 26) . " ║\n";
        $table .= '╠═' . str_repeat('═', 20) . '═╦═' . str_repeat('═', 10) . '═╦═' . str_repeat('═', 15) . '═╦═' . str_repeat('═', 5) . '═╦═' . str_repeat('═', 7) . "═╣\n";
        
        $table .= '║ ' . str_pad('ID', 20) . ' ║ ' . str_pad('TIPO', 10) . ' ║ ' . str_pad('ENTORNO', 15) . ' ║ ' . str_pad('LINEA', 5) . ' ║ ' . str_pad('COLUMNA', 7) . " ║\n";
        
        $table .= '╠═' . str_repeat('═', 20) . '═╬═' . str_repeat('═', 10) . '═╬═' . str_repeat('═', 15) . '═╬═' . str_repeat('═', 5) . '═╬═' . str_repeat('═', 7) . "═╣\n";
        
        foreach (self::$simbolos as $sym) {
            $table .= $sym->toString() . "\n";
        }
        
        $table .= '╚═' . str_repeat('═', 20) . '═╩═' . str_repeat('═', 10) . '═╩═' . str_repeat('═', 15) . '═╩═' . str_repeat('═', 5) . '═╩═' . str_repeat('═', 7) . "═╝\n";
        
        return $table;
    }
}