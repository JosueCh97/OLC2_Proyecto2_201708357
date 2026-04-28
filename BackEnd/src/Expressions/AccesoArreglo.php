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

class AccesoArreglo extends Expresion {
    public string $id;
    /** @var Expresion[] */
    public array $expresionesIndices;

    public function __construct(int $linea, int $columna, string $id, array $expresionesIndices) {
        parent::__construct($linea, $columna, TipoExpresion::ACCESO_ARREGLO ?? 'ACCESO_ARREGLO');
        $this->id = $id;
        $this->expresionesIndices = $expresionesIndices;
    }

    public function ejecutar(Entorno $entorno): TipoRetorno {
        $simbolo = $entorno->getVariable($this->id);

        if ($simbolo === null) {
            return $this->error("La variable '{$this->id}' no existe.");
        }

        $indicesTotales = [];
        $valorObjetivo = $simbolo->valor;

        // Soporte para punteros a arreglos: arr *[N]T se accede como arr[i].
        if ($valorObjetivo instanceof ReferenciaValor) {
            $simboloDestino = $entorno->getVariable($valorObjetivo->id);
            if ($simboloDestino === null) {
                return $this->error("La referencia '{$this->id}' apunta a una variable inexistente.");
            }

            $valorObjetivo = $simboloDestino->valor;
            foreach ($simbolo->valor->indices as $idxRef) {
                if (!is_int($idxRef)) {
                    return $this->error("Índice inválido en la referencia '{$this->id}'.");
                }
                $indicesTotales[] = $idxRef;
            }
        }

        // Verificamos que sea un arreglo
        if (!($valorObjetivo instanceof ValorArreglo)) {
            return $this->error("La variable '{$this->id}' no es un arreglo.");
        }

        $arregloObj = $valorObjetivo;
        $valoresActuales = $arregloObj->valores;

        // Evaluamos índices declarados en la expresión actual
        foreach ($this->expresionesIndices as $expIndice) {
            $resultadoIndice = $expIndice->ejecutar($entorno);
            if ($resultadoIndice->tipo !== Tipo::ENTERO || !is_int($resultadoIndice->valor)) {
                return $this->error("Los índices de un arreglo deben ser de tipo ENTERO.");
            }
            $indicesTotales[] = $resultadoIndice->valor;
        }

        // Validamos que no se excedan las dimensiones
        if (count($indicesTotales) > count($arregloObj->dimensiones)) {
            return $this->error("Cantidad de índices incorrecta para el arreglo '{$this->id}'. Se esperaban como máximo " . count($arregloObj->dimensiones));
        }

        // Navegamos por los índices //
        foreach ($indicesTotales as $i => $indice) {

            // Verificamos límites (Out of Bounds)
            if ($indice < 0 || $indice >= $arregloObj->dimensiones[$i]) {
                return $this->error("Índice {$indice} fuera de los límites en la dimensión {$i} para '{$this->id}'.");
            }

            // Entramos un nivel más profundo
            $valoresActuales = $valoresActuales[$indice];
        }

        // Al final, $valoresActuales tendrá el valor primitivo final (ej: 10, "hola", true)
        return new TipoRetorno($valoresActuales, $arregloObj->tipoBase);
    }

    private function error(string $mensaje): TipoRetorno {
        $msg = "❌ Error Semántico [Línea {$this->linea}]: " . $mensaje;
        Salida::$errores[] = $msg;
        Salida::$salidasConsola[] = $msg;
        return new TipoRetorno(null, Tipo::NIL);
    }

    // Método para agregar índices cuando hay múltiples dimensiones m[i][j]
    public function agregarIndice(Expresion $indice) {
        $this->expresionesIndices[] = $indice;
    }
}