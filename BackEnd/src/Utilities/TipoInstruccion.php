<?php
namespace App\Utilities;

enum TipoInstruccion {
    case IMPRIMIR;
    case CREAR_VARIABLE;
    case ASIGNACION;
    case INCREMENTO;
    case DECREMENTO;
    case BLOQUE_INSTRUCCIONES;
    case SI;
    case SEGUN;
    case PARA;
    case MIENTRAS;
    case HASTA;
    case DECLARAR_FUNCION;
    case DECLARAR_PROCEDIMIENTO;
    case CONTINUAR;
    case DETENER;
}