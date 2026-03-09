<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;

class AccesoID extends Expresion {
    private string $id;

    public function __construct(int $linea, int $columna, string $id) {
        // Asegúrate de que ACCESO_ID exista en tu Enum de TipoExpresion
        parent::__construct($linea, $columna, TipoExpresion::ACCESO_ID);
        $this->id = $id;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        // Buscamos la variable en la memoria (Entorno)
        $simbolo = $entorno->getVariable($this->id);

        if ($simbolo !== null) {
            // Si existe, retornamos su valor y su tipo (ej: 10, Tipo::ENTERO)
            return new TipoRetorno($simbolo->valor, $simbolo->tipo);
        }

        // Si no existe, registramos el error semántico
        $msgError = "❌ Error Semántico [Línea {$this->linea}]: La variable '{$this->id}' no existe en este entorno.";
        Salida::$errores[] = $msgError;
        Salida::$salidasConsola[] = $msgError;
        
        // Retornamos NULL para que las operaciones matemáticas fallen limpiamente
        return new TipoRetorno(null, Tipo::NULL);
    }
}