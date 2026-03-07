<?php
namespace App\Interprete;

use App\Language\GolampiBaseVisitor;

class CustomVisitor extends GolampiBaseVisitor {

    // 1. Punto de entrada: visitamos el inicio del archivo
    public function visitInicio($ctx) {
        echo "Visitando el inicio del programa...\n";
       
        // visitChildren le dice a ANTLR que siga bajando por el arbol.
        return $this->visitChildren($ctx); 
    }

public function visitDeclaracion($ctx) {
        echo "\n Visitando una declaración...\n";
        $listaCtx = $ctx->listids();
        $listaexpCtx = $ctx->listaexp();
        echo "\n --------------------------------------------------------\n";
        
        // OPTIMIZACIÓN 1: Early Return
        if ($listaCtx === null) {
            echo "No hay lista de IDs.\n";
            return null; 
        }

        // --- EXTRACCIÓN A PRUEBA DE BALAS PARA IDs ---
        // Leemos TODOS los hijos del nodo directamente (ej: 'w', ',', 'z')
        foreach ($listaCtx->children as $hijo) {
            $texto = $hijo->getText();
            // Ignoramos las comas, guardamos solo las variables
            if ($texto !== ',') { 
                echo "||" . $texto . "||" ;
            }
        }

        // --- EXTRACCIÓN A PRUEBA DE BALAS PARA EXPRESIONES ---
        if ($listaexpCtx !== null) {
            foreach ($listaexpCtx->children as $hijo) {
                $texto = $hijo->getText();
                // Ignoramos las comas, guardamos solo los valores
                if ($texto !== ',') { 
                    echo "," . $texto . "," ;
                }
            }
        } else {
            echo ",SIN VALOR,"; // Esto pasará en tu error intencional de la variable 'y'
        }

        // Tipo de dato
        if ($ctx->primitivos() !== null) {
            echo "\n \t \t Tipo: " . $ctx->primitivos()->getText();
        }

        echo "\n --------------------------------------------------------\n";

        return null;
    }

    
}