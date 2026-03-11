<?php
namespace App\Utilities;

enum Tipo {
    case ENTERO;
    case DECIMAL;
    case BOOLEANO;
    case CARACTER;
    case CADENA;
    case NIL;
    case LISTA;
}

// // Equivalente a: export type TipoRetorno = {valor: any, tipo: Tipo};
// class TipoRetorno {
//     public mixed $valor;
//     public Tipo $tipo;

//     public function __construct(mixed $valor, Tipo $tipo) {
//         $this->valor = $valor;
//         $this->tipo = $tipo;
//     }
// }