<?php
namespace App\Entorno;

use App\Utilities\Tipo ;

class Simbolo {
    // 'any' en TypeScript se convierte en 'mixed' en PHP
    public mixed $valor; 
    public string $id;
    public Tipo $tipo;
    public $isConst;

    public function __construct(mixed $valor, string $id, Tipo $tipo  , bool $isConst= false ) {
        $this->valor = $valor;
        $this->id = $id;
        $this->tipo = $tipo;
        $this->isConst = $isConst;
    }

    //devolver si es constante
    public function esConstante(): bool {
        
        return $this->isConst;
    }
}