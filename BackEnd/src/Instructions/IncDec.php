<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\Salida;
use App\Utilities\Tipo;

class IncDec extends Instruction {
    public string $id;
    public string $operador;

    public function __construct(int $linea, int $columna, string $id, string $operador) {
        parent::__construct($linea, $columna);
        $this->id = $id;
        $this->operador = $operador;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $simbolo = $entorno->getVariable($this->id);

        if ($simbolo === null) {
            Salida::$errores[] = "Error semantico [Linea {$this->linea}]: la variable '{$this->id}' no existe.";
            //Salida::$salidasConsola[] = "Error semantico [Linea {$this->linea}]: la variable '{$this->id}' no existe.";
            return null;
        }

        if ($simbolo->isConst === true) {
            Salida::$errores[] = "Error semantico [Linea {$this->linea}]: no se puede modificar la constante '{$this->id}'.";
           // Salida::$salidasConsola[] = "Error semantico [Linea {$this->linea}]: no se puede modificar la constante '{$this->id}'.";
            return null;
        }

        if ($simbolo->tipo !== Tipo::ENTERO && $simbolo->tipo !== Tipo::DECIMAL) {
            Salida::$errores[] = "Error semantico [Linea {$this->linea}]: solo se permite ++/-- en valores numericos.";
            //Salida::$salidasConsola[] = "Error semantico [Linea {$this->linea}]: solo se permite ++/-- en valores numericos.";
            return null;
        }

        $nuevoValor = $simbolo->valor;
        if ($this->operador === '++') {
            $nuevoValor = $simbolo->valor + 1;
        } elseif ($this->operador === '--') {
            $nuevoValor = $simbolo->valor - 1;
        } else {
            Salida::$errores[] = "Error semantico [Linea {$this->linea}]: operador '{$this->operador}' no soportado para IncDec.";
           // Salida::$salidasConsola[] = "Error semantico [Linea {$this->linea}]: operador '{$this->operador}' no soportado para IncDec.";
            return null;
        }
        $entorno->setVariable($this->id, $nuevoValor);
        return null;
    }
}
