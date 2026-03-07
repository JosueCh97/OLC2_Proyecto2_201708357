<?php
namespace App\Entorno;

use App\Utilities\Tipo ;

class Simbolo {
    // 'any' en TypeScript se convierte en 'mixed' en PHP
    public mixed $valor; 
    public string $id;
    public Tipo $tipo;

    public function __construct(mixed $valor, string $id, Tipo $tipo) {
        $this->valor = $valor;
        $this->id = $id;
        $this->tipo = $tipo;
    }
}