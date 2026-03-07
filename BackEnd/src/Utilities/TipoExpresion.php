<?php
namespace App\Utilities;

enum TipoExpresion {
    case PRIMITIVO;
    case ARMITEMETICO; // Lo mantengo exactamente como lo escribiste
    case RELACIONAL;
    case LOGICO;
    case ACCESO_ID;
    case FUNCION_NATIVA;
    case INC_DEC;
    case RETORNAR;
    case LISTA;
    case NULL;
}