<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\ReferenciaValor;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\TipoExpresion;
use App\Utilities\TipoRetorno;

class Referencia extends Expresion {
    public string $id;
    /** @var Expresion[] */
    public array $indices;

    public function __construct(int $linea, int $columna, string $id, array $indices) {
        parent::__construct($linea, $columna, TipoExpresion::REFERENCIA);
        $this->id = $id;
        $this->indices = $indices;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $simbolo = $entorno->getVariable($this->id);
        if ($simbolo === null) {
            $msg = "❌ Error Semántico [Línea {$this->linea}]: No se puede referenciar '{$this->id}' porque no existe.";
            Salida::$errores[] = $msg;
            Salida::$salidasConsola[] = $msg;
            return new TipoRetorno(null, Tipo::NIL);
        }

        $indicesEvaluados = [];
        foreach ($this->indices as $indiceExpr) {
            $indice = $indiceExpr->ejecutar($entorno);
            if ($indice->tipo !== Tipo::ENTERO) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: Los índices referenciados deben ser enteros.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }
            $indicesEvaluados[] = $indice->valor;
        }

        // Si la variable ya es una referencia, aplanamos la cadena para que
        // siempre apunte al símbolo base real.
        if ($simbolo->valor instanceof ReferenciaValor) {
            $refBase = $simbolo->valor;
            return new TipoRetorno(
                new ReferenciaValor($refBase->id, array_merge($refBase->indices, $indicesEvaluados)),
                Tipo::NIL
            );
        }

        return new TipoRetorno(new ReferenciaValor($this->id, $indicesEvaluados), Tipo::NIL);
    }
}