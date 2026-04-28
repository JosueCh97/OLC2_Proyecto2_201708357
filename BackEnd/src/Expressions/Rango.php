<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Salida;
use App\Utilities\Tipo;
use App\Utilities\TipoExpresion;
use App\Utilities\TipoRetorno;

class Rango extends Expresion {
    public Expresion $valor;
    public Expresion $inicio;
    public Expresion $fin;
    public bool $negado;

    public function __construct(
        int $linea,
        int $columna,
        Expresion $valor,
        Expresion $inicio,
        Expresion $fin,
        bool $negado
    ) {
        parent::__construct($linea, $columna, TipoExpresion::RELACIONAL);
        $this->valor = $valor;
        $this->inicio = $inicio;
        $this->fin = $fin;
        $this->negado = $negado;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $valorEval = $this->valor->ejecutar($entorno);
        $inicioEval = $this->inicio->ejecutar($entorno);
        $finEval = $this->fin->ejecutar($entorno);

        if ($valorEval->tipo === Tipo::NIL || $inicioEval->tipo === Tipo::NIL || $finEval->tipo === Tipo::NIL) {
            return $this->error("No se puede evaluar un rango con valores nulos o inexistentes.");
        }

        $tiposPermitidos = [Tipo::ENTERO, Tipo::DECIMAL, Tipo::CARACTER];
        if (
            !in_array($valorEval->tipo, $tiposPermitidos, true)
            || !in_array($inicioEval->tipo, $tiposPermitidos, true)
            || !in_array($finEval->tipo, $tiposPermitidos, true)
        ) {
            return $this->error("El operador de rango solo admite valores numericos.");
        }

        if ($inicioEval->valor > $finEval->valor) {
            return $this->error("Rango invalido: el limite inferior es mayor que el superior.");
        }

        $estaEnRango = $valorEval->valor >= $inicioEval->valor && $valorEval->valor <= $finEval->valor;
        $resultado = $this->negado ? !$estaEnRango : $estaEnRango;

        return new TipoRetorno($resultado, Tipo::BOOLEANO);
    }

    private function error(string $mensaje): TipoRetorno {
        $msgError = "❌ Error Semántico [Línea {$this->linea}]: {$mensaje}";
        Salida::$errores[] = $msgError;
        Salida::$salidasConsola[] = $msgError;
        return new TipoRetorno(null, Tipo::NIL);
    }
}
