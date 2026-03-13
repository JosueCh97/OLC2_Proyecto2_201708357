<?php
namespace App\Entorno;

use App\Utilities\Tipo;
use App\Utilities\Salida; // salida en consola
use App\Instructions\Funcion;
use App\Expresiones\Atributo;
use App\Entorno\SimboloTabla;
use App\Entorno\Objeto;
use App\Entorno\Simbolo;



class Entorno {
    // En PHP usamos arreglos asociativos en lugar de Map
    public array $ids;       // Para objetos Simbolo
    public array $funciones; // Para objetos Funcion
    public array $objetos;   // Para objetos Objeto

    public ?Entorno $anterior;
    public string $nombre;

    public function __construct(?Entorno $anterior, string $nombre) {
        $this->anterior = $anterior;
        $this->nombre = $nombre;
        
        // Inicializamos los "Maps"
        $this->ids = [];
        $this->funciones = [];
        $this->objetos = [];
    }

    // === Guardar Variable ===
    public function guardarVariable(string $id, mixed $valor, Tipo $tipo, int $linea, int $columna , bool $isconstid = false ): void {
        $entorno = $this;
        if (!array_key_exists($id, $entorno->ids)) {
            // Guardar variable
            $entorno->ids[$id] = new Simbolo($valor, $id, $tipo, $isconstid);
            
            // Insertamos en la tabla de simbolos (usando una clase estática Tabla)
            //Tabla::$tablaSimbolos[] = new SimboloTabla($linea, $columna, true, true, $valor, $tipo, $id, $entorno->nombre);
            Tabla::push(new SimboloTabla($linea, $columna, true, true, $valor, $tipo, $id, $entorno->nombre));
            }
        // Error semántico - Variable ya existe
    }

    // === Obtener Variable ===
    public function getVariable(string $id): ?Simbolo {
        $entorno = $this;
        while ($entorno !== null) {
            if (array_key_exists($id, $entorno->ids)) {
                return $entorno->ids[$id];
            }
            $entorno = $entorno->anterior;
        }
        // Error semántico - Variable no existe
        return null;
    }

    // === Actualizar Variable ===


    public function setVariable(string $id, mixed $valor): bool {
        $entorno = $this;
        while ($entorno !== null) {
            if (array_key_exists($id, $entorno->ids)) {
                $simbolo = $entorno->ids[$id];
                
                // Si es constante, bloqueamos la asignación
                if (isset($simbolo->isConst) && $simbolo->isConst === true) {
                    return false; 
                }
                
                $simbolo->valor = $valor; // Actualizamos el valor
                return true; // Éxito
            }
            $entorno = $entorno->anterior;
        }
        return false; // La variable no existe
    }


    // === GUARDAR OBJETO ===
    /**
     * @param string $id
     * @param Atributo[] $atributos
     */
    public function guardarObjeto(string $id, array $atributos): void {
        $entorno = $this;
        if (!array_key_exists($id, $entorno->objetos)) {
            // console.log($atributos);
            $this->objetos[$id] = new Objeto($id);
            $this->guardarAtributo($id, $atributos);
        }
        // Error semántico - Objeto ya existe
    }

    public function guardarAtributo(string $id, array $atributo): void {
        $entorno = $this;
        if (array_key_exists($id, $entorno->objetos)) {
            $objeto = $entorno->objetos[$id];
            for ($i = 0; $i < count($atributo); $i++) {
                // console.log($atributo[$i]);
                // Asumimos que Objeto tiene un arreglo 'atributos'
                $objeto->atributos[$atributo[$i]->id] = $atributo[$i];
            }
        }
        // Error semántico - Objeto no existe
    }

    // === OBTENER OBJETO ===
    public function getObjeto(string $id): ?Objeto {
        $entorno = $this;
        while ($entorno !== null) {
            if (array_key_exists($id, $entorno->objetos)) {
                // echo 'Objeto encontrado: ' . $id . "\n";
                // print_r($entorno->objetos[$id]);
                return $entorno->objetos[$id];
            }
            $entorno = $entorno->anterior;
        }
        // Error semántico - Objeto no existe
        return null;
    }

    // === GUARDAR FUNCION ===
    public function guardarFuncion(string $id, Funcion $funcion): void {
        $entorno = $this;
        if (!array_key_exists($id, $entorno->funciones)) {
            // Guardar Funcion
            $entorno->funciones[$id] = $funcion;
            
            // Insertamos en la tabla de simbolos
            //Tabla::$tablaSimbolos[] = new SimboloTabla($funcion->linea, $funcion->columna, false, false, null, Tipo::NIL, $id, $entorno->nombre);
            Tabla::push(new SimboloTabla($funcion->linea, $funcion->columna, false, false, null, Tipo::NIL, $id, $entorno->nombre));
       
            }
        // Error semántico - Funcion ya existe
    }

    // === OBTENER FUNCION ===
    public function getFuncion(string $id): ?Funcion {
        $entorno = $this;
        while ($entorno !== null) {
            if (array_key_exists($id, $entorno->funciones)) {
                return $entorno->funciones[$id];
            }
            $entorno = $entorno->anterior;
        }
        // Error semántico - Funcion no existe
        return null;
    }

    // === GESTIÓN DE CONSOLA ===
    public function setPrint(string $print): void {
        Salida::$salidasConsola[] = $print;
    }
}