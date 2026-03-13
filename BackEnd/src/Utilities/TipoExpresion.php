<?php
namespace App\Utilities;

enum TipoExpresion {
    case PRIMITIVO;
    case ARITMETICO; 
    case RELACIONAL;
    case LOGICO;
    case ACCESO_ID;
    case FUNCION_NATIVA;
    case INC_DEC;
    case RETORNAR;
    case LISTA;
    case ACCESO_ARREGLO ;
    case ARREGLO_LITERAL;
    case LLAMADA;
    
    case NULL;
}