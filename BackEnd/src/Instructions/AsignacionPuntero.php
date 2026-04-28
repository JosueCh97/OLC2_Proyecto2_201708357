<?php
namespace App\Instructions;

use App\Ast\Expresion;
use App\Ast\Instruction;
use App\Entorno\Entorno;
use App\Utilities\ReferenciaValor;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo;

class AsignacionPuntero extends Instruction {
    public string $idPuntero;
    public Expresion $valor;

    public function __construct(int $linea, int $columna, string $idPuntero, Expresion $valor) {
        parent::__construct($linea, $columna);
        $this->idPuntero = $idPuntero;
        $this->valor = $valor;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $simboloPuntero = $entorno->getVariable($this->idPuntero);
        if ($simboloPuntero === null || !($simboloPuntero->valor instanceof ReferenciaValor)) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: '{$this->idPuntero}' no es un puntero válido.";
            Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: '{$this->idPuntero}' no es un puntero válido.";
            return null;
        }

        $referencia = $simboloPuntero->valor;
        $destino = $entorno->getVariable($referencia->id);
        if ($destino === null) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: El puntero '{$this->idPuntero}' apunta a una variable inexistente.";
            Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: El puntero '{$this->idPuntero}' apunta a una variable inexistente.";
            return null;
        }

        $nuevoValor = $this->valor->ejecutar($entorno);

        if (empty($referencia->indices)) {
            $destino->valor = $nuevoValor->valor;
            return null;
        }

        if (!($destino->valor instanceof ValorArreglo)) {
            Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: El puntero '{$this->idPuntero}' esperaba un arreglo.";
            Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: El puntero '{$this->idPuntero}' esperaba un arreglo.";
            return null;
        }

        $cursor = &$destino->valor->valores;
        foreach ($referencia->indices as $pos => $indice) {
            if (!is_array($cursor) || !array_key_exists($indice, $cursor)) {
                Salida::$errores[] = "❌ Error Semántico [Línea {$this->linea}]: Índice {$indice} fuera de rango al asignar por puntero.";
                Salida::$salidasConsola[] = "❌ Error Semántico [Línea {$this->linea}]: Índice {$indice} fuera de rango al asignar por puntero.";
                return null;
            }

            if ($pos === count($referencia->indices) - 1) {
                $cursor[$indice] = $nuevoValor->valor;
                return null;
            }

            $cursor = &$cursor[$indice];
        }

        return null;
    }
}