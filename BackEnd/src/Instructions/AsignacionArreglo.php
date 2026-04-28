<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\ValorArreglo;
use App\Utilities\ReferenciaValor;

class AsignacionArreglo extends Instruction {
    public string $id;
    public array $indices; // Expresiones de los índices
    public Expresion $valorNuevo;

    public function __construct(int $linea, int $columna, string $id, array $indices, Expresion $valorNuevo) {
        parent::__construct($linea, $columna, TipoInstruccion::ASIGNACION ?? 'ASIGNACION');
        $this->id = $id;
        $this->indices = $indices;
        $this->valorNuevo = $valorNuevo;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $simbolo = $entorno->getVariable($this->id);
        if ($simbolo === null) {
            Salida::$salidasConsola[] = "❌ Error: '{$this->id}' no es un arreglo válido.";
            return null;
        }

        $indicesTotales = [];
        $arregloObj = null;

        if ($simbolo->valor instanceof ValorArreglo) {
            $arregloObj = $simbolo->valor;
        } elseif ($simbolo->valor instanceof ReferenciaValor) {
            $ref = $simbolo->valor;
            $simboloDestino = $entorno->getVariable($ref->id);
            if ($simboloDestino === null || !($simboloDestino->valor instanceof ValorArreglo)) {
                Salida::$salidasConsola[] = "❌ Error: '{$this->id}' no es un arreglo válido.";
                return null;
            }

            $arregloObj = $simboloDestino->valor;
            $indicesTotales = $ref->indices;
        } else {
            Salida::$salidasConsola[] = "❌ Error: '{$this->id}' no es un arreglo válido.";
            return null;
        }

        foreach ($this->indices as $expIndice) {
            $indiceEval = $expIndice->ejecutar($entorno);
            if ($indiceEval->tipo !== Tipo::ENTERO || !is_int($indiceEval->valor)) {
                Salida::$salidasConsola[] = "❌ Error: El índice del arreglo debe ser entero.";
                return null;
            }
            $indicesTotales[] = $indiceEval->valor;
        }

        if (count($indicesTotales) !== count($arregloObj->dimensiones)) {
            Salida::$salidasConsola[] = "❌ Error: Cantidad de índices inválida para '{$this->id}'.";
            return null;
        }

        $nuevoValorEval = $this->valorNuevo->ejecutar($entorno);

        if ($nuevoValorEval->tipo !== $arregloObj->tipoBase) {
            Salida::$salidasConsola[] = "❌ Error: Tipo incorrecto. El arreglo es de tipo {$arregloObj->tipoBase->name}.";
            return null;
        }

        // Navegación por referencia para poder modificar el arreglo original en PHP
        $puntero = &$arregloObj->valores;
        
        foreach ($indicesTotales as $i => $idx) {

            if ($idx < 0 || $idx >= $arregloObj->dimensiones[$i]) {
                Salida::$salidasConsola[] = "❌ Error: Índice {$idx} fuera de límites.";
                return null;
            }

            // Si es la última dimensión, asignamos el valor
            if ($i === count($this->indices) - 1) {
                $puntero[$idx] = $nuevoValorEval->valor;
            } else {
                // Si no, avanzamos el puntero al siguiente nivel
                $puntero = &$puntero[$idx];
            }
        }

        //Salida::$salidasConsola[] = "🔄 Arreglo [Línea {$this->linea}]: {$this->id} actualizado exitosamente.";
        return null;
    }
}