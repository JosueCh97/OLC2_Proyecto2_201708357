<?php
namespace App\Instructions;

use App\Ast\Instruction;
use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\TipoInstruccion;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\ValorArreglo;

class AsignacionArreglo extends Instruction {
    private string $id;
    private array $indices; // Expresiones de los índices
    private Expresion $valorNuevo;

    public function __construct(int $linea, int $columna, string $id, array $indices, Expresion $valorNuevo) {
        parent::__construct($linea, $columna, TipoInstruccion::ASIGNACION ?? 'ASIGNACION');
        $this->id = $id;
        $this->indices = $indices;
        $this->valorNuevo = $valorNuevo;
    }

    public function ejecutar(Entorno $entorno): mixed {
        $simbolo = $entorno->getVariable($this->id);
        if ($simbolo === null || !($simbolo->valor instanceof ValorArreglo)) {
            Salida::$salidasConsola[] = "❌ Error: '{$this->id}' no es un arreglo válido.";
            return null;
        }

        $arregloObj = $simbolo->valor;
        $nuevoValorEval = $this->valorNuevo->ejecutar($entorno);

        if ($nuevoValorEval->tipo !== $arregloObj->tipoBase) {
            Salida::$salidasConsola[] = "❌ Error: Tipo incorrecto. El arreglo es de tipo {$arregloObj->tipoBase->name}.";
            return null;
        }

        // Navegación por referencia para poder modificar el arreglo original en PHP
        $puntero = &$arregloObj->valores;
        
        foreach ($this->indices as $i => $expIndice) {
            $indiceEval = $expIndice->ejecutar($entorno);
            $idx = $indiceEval->valor;

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

        Salida::$salidasConsola[] = "🔄 Arreglo [Línea {$this->linea}]: {$this->id} actualizado exitosamente.";
        return null;
    }
}