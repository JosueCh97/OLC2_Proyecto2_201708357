<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;

class Llamada extends Expresion {
    private string $nombre;
    /** @var Expresion[] */
    private array $args;

    public function __construct(int $linea, int $columna, string $nombre, array $args) {
        parent::__construct($linea, $columna, TipoExpresion::LLAMADA);
        $this->nombre = $nombre;
        $this->args = $args;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        
        // ==========================================
        // 1. INTERCEPTAR FUNCIONES EMBEBIDAS (NATIVAS)
        // ==========================================
        if ($this->nombre === "now") {
            if (count($this->args) !== 0) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: 'now()' no espera argumentos.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }
            return new TipoRetorno(date('Y-m-d H:i:s'), Tipo::CADENA);
        }

        if ($this->nombre === "len") {
            if (count($this->args) !== 1) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: 'len()' espera 1 argumento.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }
            
            $argEvaluado = $this->args[0]->ejecutar($entorno);
            
            if ($argEvaluado->tipo === Tipo::CADENA) {
                return new TipoRetorno(strlen($argEvaluado->valor), Tipo::ENTERO);
            } elseif ($argEvaluado->tipo === Tipo::ARREGLO || is_array($argEvaluado->valor)) {
                return new TipoRetorno(count($argEvaluado->valor), Tipo::ENTERO);
            } else {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: 'len()' solo acepta arreglos o cadenas.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }
        }

        // ==========================================
        // 2. FLUJO NORMAL (Buscar función creada por el usuario)
        // ==========================================
        $funcion = $entorno->getFuncion($this->nombre);

        if ($funcion === null) {
            $msg = "❌ Error Semántico [Línea {$this->linea}]: La función '{$this->nombre}' no está definida.";
            Salida::$errores[] = $msg;
            Salida::$salidasConsola[] = $msg;
            return new TipoRetorno(null, Tipo::NIL);
        }

        // Evaluar los argumentos en el entorno actual
        $valoresArgs = [];
        foreach ($this->args as $arg) {
            $valoresArgs[] = $arg->ejecutar($entorno);
        }

        // Validar cantidad de argumentos vs parámetros
        if (count($valoresArgs) !== count($funcion->parametros)) {
            $msg = "❌ Error Semántico [Línea {$this->linea}]: La función '{$this->nombre}' espera "
                . count($funcion->parametros) . " argumento(s), se recibieron "
                . count($valoresArgs) . ".";
            Salida::$errores[] = $msg;
            Salida::$salidasConsola[] = $msg;
            return new TipoRetorno(null, Tipo::NIL);
        }

        // Crear nuevo entorno para la función
        $entornoFuncion = new Entorno($entorno, "Funcion_{$this->nombre}");

        // Enlazar parámetros con los valores de los argumentos
        foreach ($funcion->parametros as $i => $param) {
            $argVal = $valoresArgs[$i];
            $entornoFuncion->guardarVariable(
                $param->id,
                $argVal->valor,
                $argVal->tipo,
                $this->linea,
                $this->columna,
                false
            );
        }

        // Ejecutar el bloque de la función
        $resultado = $funcion->bloque->ejecutar($entornoFuncion);

        // Interceptar la señal de RETURN
        if (is_array($resultado) && isset($resultado['control']) && $resultado['control'] === 'RETURN') {
            $valores = $resultado['valores'];
            if (!empty($valores)) {
                return $valores[0];
            }
        }

        return new TipoRetorno(null, Tipo::NIL);
    }
}