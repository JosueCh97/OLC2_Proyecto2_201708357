<?php
namespace App\Expressions;

use App\Ast\Expresion;
use App\Entorno\Entorno;
use App\Utilities\Tipo;
use App\Utilities\TipoRetorno;
use App\Utilities\TipoExpresion;
use App\Utilities\Salida;
use App\Utilities\ValorArreglo;
use App\Utilities\ReferenciaValor;

class Llamada extends Expresion {
    public string $nombre;
    /** @var Expresion[] */
    public array $args;

    public function __construct(int $linea, int $columna, string $nombre, array $args) {
        parent::__construct($linea, $columna, TipoExpresion::LLAMADA);
        $this->nombre = $nombre;
        $this->args = $args;
    }

    private function tipoAString(TipoRetorno $valor): string {
        if ($valor->tipo === Tipo::ARREGLO && $valor->valor instanceof ValorArreglo) {
            $prefijoDim = '';
            foreach ($valor->valor->dimensiones as $dim) {
                $prefijoDim .= '[' . $dim . ']';
            }

            $tipoBase = match ($valor->valor->tipoBase) {
                Tipo::ENTERO => 'int32',
                Tipo::DECIMAL => 'float32',
                Tipo::BOOLEANO => 'bool',
                Tipo::CARACTER => 'rune',
                Tipo::CADENA => 'string',
                Tipo::NIL => 'nil',
                default => 'any',
            };

            return $prefijoDim . $tipoBase;
        }

        return match ($valor->tipo) {
            Tipo::ENTERO => 'int32',
            Tipo::DECIMAL => 'float32',
            Tipo::BOOLEANO => 'bool',
            Tipo::CARACTER => 'rune',
            Tipo::CADENA => 'string',
            Tipo::NIL => 'nil',
            Tipo::ARREGLO => 'array',
            Tipo::LISTA => 'lista',
        };
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
            } elseif ($argEvaluado->valor instanceof ValorArreglo) {
                return new TipoRetorno(count($argEvaluado->valor->valores), Tipo::ENTERO);
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
        // SOPORTE PARA LA FUNCIÓN EMBEBIDA 'substr'
        // ==========================================
        if ($this->nombre === "substr") {
            if (count($this->args) !== 3) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: 'substr' espera 3 argumentos (texto, inicio, longitud).";
                \App\Utilities\Salida::$errores[] = $msg;
                \App\Utilities\Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }

            $strArg = $this->args[0]->ejecutar($entorno);
            $startArg = $this->args[1]->ejecutar($entorno);
            $lengthArg = $this->args[2]->ejecutar($entorno);

            // Verificamos que los tipos sean correctos
            if ($strArg->tipo === Tipo::CADENA && $startArg->tipo === Tipo::ENTERO && $lengthArg->tipo === Tipo::ENTERO) {
                $texto = $strArg->valor;
                $inicio = $startArg->valor;
                $longitud = $lengthArg->valor;
                $tamCadena = strlen($texto);

                if ($inicio < 0 || $longitud < 0 || $inicio > $tamCadena || ($inicio + $longitud) > $tamCadena) {
                    $msg = "❌ Error Semántico [Línea {$this->linea}]: Índices inválidos en 'substr'.";
                    \App\Utilities\Salida::$errores[] = $msg;
                    \App\Utilities\Salida::$salidasConsola[] = $msg;
                    return new TipoRetorno(null, Tipo::NIL);
                }

                $subcadena = substr($texto, $inicio, $longitud);
                
                return new TipoRetorno($subcadena, Tipo::CADENA);
            } else {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: Tipos incorrectos para 'substr'.";
                \App\Utilities\Salida::$errores[] = $msg;
                \App\Utilities\Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }
        }

        if ($this->nombre === "typeOf") {
            if (count($this->args) !== 1) {
                $msg = "❌ Error Semántico [Línea {$this->linea}]: 'typeOf()' espera 1 argumento.";
                Salida::$errores[] = $msg;
                Salida::$salidasConsola[] = $msg;
                return new TipoRetorno(null, Tipo::NIL);
            }

            $argEvaluado = $this->args[0]->ejecutar($entorno);
            return new TipoRetorno($this->tipoAString($argEvaluado), Tipo::CADENA);
        }





     
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
            // ✅ PROTECCIÓN: Si el argumento es nulo (error sintáctico previo)
            if ($arg === null) {
                $msg = "❌ Error de Ejecución [Línea {$this->linea}]: Un argumento en la llamada a '{$this->nombre}' es inválido o tiene errores de sintaxis.";
                \App\Utilities\Salida::$errores[] = $msg;
                \App\Utilities\Salida::$salidasConsola[] = $msg;
                
                // Rellenamos con un valor NIL para que no explote la validación posterior
                $valoresArgs[] = new \App\Utilities\TipoRetorno(null, \App\Utilities\Tipo::NIL);
                continue;
            }
            
            // Si el argumento está bien, lo ejecutamos normalmente
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

            if ($param->isPuntero) {
                if (!($argVal->valor instanceof ReferenciaValor)) {
                    $msg = "❌ Error Semántico [Línea {$this->linea}]: El parámetro '{$param->id}' requiere un argumento por referencia (&).";
                    Salida::$errores[] = $msg;
                    Salida::$salidasConsola[] = $msg;
                    return new TipoRetorno(null, Tipo::NIL);
                }

                $entornoFuncion->guardarVariable(
                    $param->id,
                    $argVal->valor,
                    Tipo::NIL,
                    $this->linea,
                    $this->columna,
                    false
                );
                continue;
            }

            $tipoParam = $argVal->valor instanceof ValorArreglo ? Tipo::ARREGLO : $argVal->tipo;
            $entornoFuncion->guardarVariable(
                $param->id,
                $argVal->valor,
                $tipoParam,
                $this->linea,
                $this->columna,
                false
            );
        }

   
    
        // Ejecutar el bloque de la función
        // ==========================================
        // 5. EJECUTAR EL BLOQUE (PROTEGIDO)
        // ==========================================
        if ($funcion->bloque === null) {
            $msg = "❌ Error de Ejecución [Línea {$this->linea}]: La función '{$this->nombre}' tiene un bloque inválido o con errores de sintaxis previos.";
            \App\Utilities\Salida::$errores[] = $msg;
            \App\Utilities\Salida::$salidasConsola[] = $msg;
            return new TipoRetorno(null, Tipo::NIL);
        }

        // Ejecutar el bloque de la función (Ahora sí, seguros de que no es null)
        $resultado = $funcion->bloque->ejecutar($entornoFuncion);

        // Interceptar la señal de RETURN
        if (is_array($resultado) && isset($resultado['control']) && $resultado['control'] === 'RETURN') {
            $valores = $resultado['valores'];
            if (!empty($valores)) {
                if (count($valores) > 1) {
                    // En retorno multiple, empaquetamos la lista dentro de TipoRetorno
                    // para respetar la firma del metodo (TipoRetorno).
                    return new TipoRetorno($valores, Tipo::LISTA);
                }

                return $valores[0];
            }
        }

        return new TipoRetorno(null, Tipo::NIL);



    }
}