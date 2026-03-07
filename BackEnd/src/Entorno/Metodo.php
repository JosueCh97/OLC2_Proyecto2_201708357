<?php
namespace App\Entorno;

class Metodo {
    public string $id;
    public array $instrucciones;

    public function __construct(string $id, array $instrucciones) {
        $this->id = $id;
        $this->instrucciones = $instrucciones;
    }
}