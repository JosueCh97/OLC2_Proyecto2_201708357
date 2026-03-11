<?php
namespace App\Interprete;

use App\Language\GolampiBaseVisitor;
use App\Instructions\DeclaracionID;
use App\Expressions\Primitivo;
use App\Utilities\Tipo;
use App\Utilities\Salida;

class CustomVisitor extends GolampiBaseVisitor {

    // 1. Punto de entrada: visitamos el inicio del archivo
    public function visitInicio($ctx) {
        // En lugar de solo seguir bajando, vamos a recolectar todas las instrucciones
        $ast = [];
        
        // Obtenemos el contexto de las instrucciones
        $instruccionesCtx = $ctx->instrucciones();
        
        if ($instruccionesCtx !== null) {
            // Recorremos cada instrucción individual (declaraciones, asignaciones, etc.)
            foreach ($instruccionesCtx->instruccion() as $instruccionCtx) {
                // Visitamos la instrucción y guardamos el objeto que nos retorne
                $nodo = $this->visit($instruccionCtx);
                if ($nodo !== null) {
                    $ast[] = $nodo;
                }
            }
        }
        
        // Retornamos el arreglo completo de objetos al Test.php
        return $ast; 
    }

    // 2. Visitamos las declaraciones
    public function visitDeclaracion($ctx) {

        $listaCtx = $ctx->listids();
        $listaexpCtx = $ctx->listaexp();
        $isconst = false; // Verificamos si es una declaración constante
        //verifica si es const o var
        if ($ctx->TKCONST() !== null) {
            $isconst = true;
        }
        // Early Return si hay un error sintáctico (Panic Mode de ANTLR)
        if ($listaCtx === null) {
            return null; 
        }

        // Obtener el texto completo de la declaración para detectar syntaxis incompleta
        $declText = $ctx->getText();
        
        // DETECCIÓN DE ERROR: Si la declaración termina con '=' o contiene '=' pero sin valores válidos
        if (substr($declText, -1) === '=' || (strpos($declText, '=') !== false && $listaexpCtx === null)) {
            Salida::$salidasConsola[] = "❌ Error: Declaración incompleta [Línea {$ctx->getStart()->getLine()}] - Se esperaba una expresión después de '='";
            return null; // Ignorar esta declaración
        }

        // --- EXTRACCIÓN DE IDs ---
        $ids = [];
        
        // listaCtx puede ser un array de contextos o un solo contexto
        // Usamos un loop para obtener cada ID
        $listaIdCtx = $ctx->listids();
        while ($listaIdCtx !== null) {
            $idname = $listaIdCtx->IDNAME();
            if ($idname !== null) {
                $ids[] = $idname->getText();
            }
            // Intentamos ir al siguiente en la recursión
            $listaIdCtx = $listaIdCtx->listids();
        }

        // --- EXTRACCIÓN DE EXPRESIONES (VALORES) ---
        $valores = [];
        if ($listaexpCtx !== null) {
            // Recorrer recursivamente la estructura listaexp
            // listaexp: expresion | listaexp ',' expresion;
            $listaExpActual = $listaexpCtx;
            $expresionesTemp = [];
            
            while ($listaExpActual !== null) {
                // Obtener la expresión de este nivel
                $expr = $listaExpActual->expresion();
                if ($expr !== null) {
                    $expresionesTemp[] = $expr;
                }
                // Ir al nivel anterior (recursión izquierda)
                $listaExpActual = $listaExpActual->listaexp();
            }
            
            // Invertir para tener el orden correcto (ya que es recursión izquierda)
            $expresionesTemp = array_reverse($expresionesTemp);
            
            // Visitar cada expresión
            foreach ($expresionesTemp as $expr) {
                $valores[] = $this->visit($expr);
            }
        }

        // VALIDACIÓN CRÍTICA: Comparar tamaños de listas (IDs vs Valores)
        if ($ctx->IGUAL() !== null && !empty($valores)) {
            // Si hay asignación (=) y hay valores, deben coincidir las cantidades
            if (count($ids) !== count($valores)) {
                Salida::$salidasConsola[] = "❌ Error [Línea {$ctx->getStart()->getLine()}]: Se declararon " . count($ids) . " IDs pero se asignaron " . count($valores) . " valores";
                return null; // Ignorar esta declaración
            }
        }

        // --- OBTENER EL TIPO (int32, float32, etc.) ---
        $tipoDato = $this->visitOptipo($ctx->optipo());
        // --- RETORNAR EL NODO AST ---
        // En lugar de hacer echo, construimos nuestro objeto DeclaracionID
        return new DeclaracionID(
            $ctx->getStart()->getLine(), 
            $ctx->getStart()->getCharPositionInLine(), 
            count($ids) === 1 ? $ids[0] : $ids, // Si es uno, mandamos string, si son varios, mandamos array
            $tipoDato, 
            count($valores) === 1 ? $valores[0] : $valores
            ,$isconst
        );






        
    }

     
    


    public function visitOptipo($ctx) {

        $texto = $ctx->getText();
        
        // En PHP 8, 'match' es la forma elegante y rápida de hacer un switch
        return match($texto) {
            'int32'   => Tipo::ENTERO,
            'float32' => Tipo::DECIMAL,
            'string'  => Tipo::CADENA,
            'bool'    => Tipo::BOOLEANO,
            'rune'    => Tipo::CARACTER,
            default   => Tipo::NIL,
        };
    }

    public function visitExpresion($ctx) {
        // Una expresión puede ser INT, FLOAT, STRING, BOOL, UNICODE o un optipo
        
        if ($ctx->INT() !== null) {
            return new Primitivo(
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine(),
                $ctx->INT()->getText(),
                Tipo::ENTERO
            );
        }
        
        if ($ctx->FLOAT() !== null) {
            return new Primitivo(
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine(),
                $ctx->FLOAT()->getText(),
                Tipo::DECIMAL
            );
        }
        
        if ($ctx->STRING() !== null) {
            // Remover las comillas del string
            $stringValue = $ctx->STRING()->getText();
            $stringValue = substr($stringValue, 1, -1); // Quita las comillas
            
            return new Primitivo(
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine(),
                $stringValue,
                Tipo::CADENA
            );
        }
        
        if ($ctx->BOOL() !== null) {
            return new Primitivo(
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine(),
                $ctx->BOOL()->getText(),
                Tipo::BOOLEANO
            );
        }
        
        if ($ctx->UNICODE() !== null) {
            return new Primitivo(
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine(),
                $ctx->UNICODE()->getText(),
                Tipo::CARACTER
            );
        }
        
        // Si no, intentamos obtener el optipo
        if ($ctx->optipo() !== null) {
            return $this->visitOptipo($ctx->optipo());
        }
        
        return null;
    }

    // Método helper para extraer expresiones de listaexp
    private function extractarExpresionesDeListaexp($listaexpCtx) {
        $expresiones = [];
        
        if ($listaexpCtx === null) {
            return $expresiones;
        }
        
        $listaExpActual = $listaexpCtx;
        $expresionesTemp = [];
        
        while ($listaExpActual !== null) {
            $expr = $listaExpActual->expresion();
            if ($expr !== null) {
                $expresionesTemp[] = $expr;
            }
            $listaExpActual = $listaExpActual->listaexp();
        }
        
        return array_reverse($expresionesTemp);
    }

    public function visitAsignacion($ctx) {
        $listaIdsCtx = $ctx->listids();
        $listaExpCtx = $ctx->listaexp();
        
        // Prevención de errores sintácticos
        if ($listaIdsCtx === null || $listaExpCtx === null) {
            return null; 
        }

        // --- 1. EXTRACCIÓN DE IDs (mismo método que en declaración) ---
        $ids = [];
        $listaIdActual = $listaIdsCtx;
        while ($listaIdActual !== null) {
            $idname = $listaIdActual->IDNAME();
            if ($idname !== null) {
                $ids[] = $idname->getText();
            }
            $listaIdActual = $listaIdActual->listids();
        }

        // --- 2. EXTRACCIÓN DE VALORES (mismo método que en declaración) ---
        $valores = [];
        $listaExpActual = $listaExpCtx;
        $expresionesTemp = [];
        
        while ($listaExpActual !== null) {
            $expr = $listaExpActual->expresion();
            if ($expr !== null) {
                $expresionesTemp[] = $expr;
            }
            $listaExpActual = $listaExpActual->listaexp();
        }
        
        // Invertir para tener el orden correcto
        $expresionesTemp = array_reverse($expresionesTemp);
        
        // Visitar cada expresión
        foreach ($expresionesTemp as $expr) {
            $valores[] = $this->visit($expr);
        }

        // VALIDACIÓN: Comparar tamaños
        if (count($ids) !== count($valores)) {
            Salida::$salidasConsola[] = "❌ Error [Línea {$ctx->getStart()->getLine()}]: Se intentaron asignar " . count($valores) . " valores a " . count($ids) . " variables";
            return null;
        }

        // --- 3. RETORNAR EL NODO AST ---
        return new \App\Instructions\Asignacion(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            count($ids) === 1 ? $ids[0] : $ids,
            count($valores) === 1 ? $valores[0] : $valores
        );
    }


    // ==========================================
    // EXPRESIONES ARITMÉTICAS
    // ==========================================

    public function visitExprSuma($ctx) {
        $izq = $this->visit($ctx->expresion(0));
        $der = $this->visit($ctx->expresion(1));
        $signo = $ctx->getChild(1)->getText(); // '+' o '-'
        
        return new \App\Expressions\Aritmetico(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $izq, $signo, $der
        );
    }

    public function visitExprMultiplicacion($ctx) {
        $izq = $this->visit($ctx->expresion(0));
        $der = $this->visit($ctx->expresion(1));
        $signo = $ctx->getChild(1)->getText(); // '*', '/' o '%'
        
        return new \App\Expressions\Aritmetico(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $izq, $signo, $der
        );
    }

    public function visitExprUnaria($ctx) {
        $signo = $ctx->getChild(0)->getText(); // '-'
        $der = $this->visit($ctx->expresion());
        
        return new \App\Expressions\Aritmetico(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            null, $signo, $der
        );
    }

    public function visitExprAgrupacion($ctx) {
        // Retornamos directamente lo que esté adentro de los paréntesis
        return $this->visit($ctx->expresion());
    }

    // ==========================================
    // VALORES PRIMITIVOS
    // ==========================================

    public function visitExprEntero($ctx) {
        return new \App\Expressions\Primitivo(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $ctx->INT()->getText(), \App\Utilities\Tipo::ENTERO
        );
    }

    public function visitExprDecimal($ctx) {
        return new \App\Expressions\Primitivo(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $ctx->FLOAT()->getText(), \App\Utilities\Tipo::DECIMAL
        );
    }

    public function visitExprCadena($ctx) {
        // Quitamos las comillas del string
        $texto = $ctx->STRING()->getText();
        $textoLimpio = substr($texto, 1, -1); 
        
        return new \App\Expressions\Primitivo(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $textoLimpio, \App\Utilities\Tipo::CADENA
        );
    }

    public function visitExprBooleano($ctx) {
        return new \App\Expressions\Primitivo(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $ctx->BOOL()->getText(), \App\Utilities\Tipo::BOOLEANO
        );
    }
    public function visitExprIdentificador($ctx) {
        // Instanciamos AccesoID pasándole la línea, columna y el nombre de la variable
        return new \App\Expressions\AccesoID(
            $ctx->getStart()->getLine(), 
            $ctx->getStart()->getCharPositionInLine(),
            $ctx->IDNAME()->getText()
        );
    }


    // ==========================================
    // EXPRESIONES COMPARACION
    // ==========================================

public function visitExprComparacion($ctx) {
        $izq = $this->visit($ctx->expresion(0));
        $der = $this->visit($ctx->expresion(1));
        
        // Ahora obtenemos el signo usando el hijo 1 (que es el operador)
        $signo = $ctx->getChild(1)->getText(); 
        
        return new \App\Expressions\Relacional(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $izq,
            $signo,
            $der
        );
    }


    // ==========================================
    // EXPRESIONES LÓGICAS
    // ==========================================


    public function visitExprAnd($ctx) {
        $izq = $this->visit($ctx->expresion(0));
        $der = $this->visit($ctx->expresion(1));
        return new \App\Expressions\Logico(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(), 
            $izq, '&&', $der
        );
    }

    public function visitExprOr($ctx) {
        $izq = $this->visit($ctx->expresion(0));
        $der = $this->visit($ctx->expresion(1));
        return new \App\Expressions\Logico(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(), 
            $izq, '||', $der
        );
    }

    public function visitExprNot($ctx) {
        $der = $this->visit($ctx->expresion());
        return new \App\Expressions\Logico(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(), 
            null, '!', $der // El hijo izquierdo es nulo porque es unario
        );
    }



   // ==========================================
    // OPERACIONES COMPUESTAS (+=, -=, ++, --)
    // ==========================================


    // Para x += 5;
    public function visitAsig_compuesta($ctx) {
        $id = $ctx->IDNAME()->getText();
        $operador = $ctx->getChild(1)->getText(); // Obtiene '+=', '-=', etc.
        $expresion = $this->visit($ctx->expresion());
        
        return new \App\Instructions\AsignacionCompuesta(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $id,
            $operador,
            $expresion
        );
    }

    // Para x++; o x--;
    public function visitInc_dec($ctx) {
        $id = $ctx->IDNAME()->getText();
        $operador = $ctx->getChild(1)->getText(); // Obtiene '++' o '--'
        
        return new \App\Instructions\AsignacionCompuesta(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $id,
            $operador,
            null // No hay expresión del lado derecho
        );
    }
    
    
  // ==========================================
    // !BLOQUE DE INTRUCCIONES 
    // ==========================================


    // --- VISITOR PARA EL BLOQUE ---
    public function visitBloque($ctx) {
        $instrucciones = [];
        // Recorremos todas las instrucciones que haya dentro de las llaves { }
        foreach ($ctx->instruccion() as $instCtx) {
            $instrucciones[] = $this->visit($instCtx);
        }
        
        return new \App\Instructions\Bloque(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $instrucciones
        );
    }

    //!  SALIDA (PRINT)
    // --- VISITOR PARA IMPRIMIR ---
    public function visitImprimir($ctx) {
        // Obtenemos el arreglo plano de expresiones visitando la lista
        $expresiones = $this->visit($ctx->listaexp());
        
        return new \App\Instructions\Imprimir(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $expresiones
        );
    }

    // --- VISITOR PARA LA LISTA DE EXPRESIONES (listaexp) ---
    public function visitListaexp($ctx) {
        $expresiones = [];
        
        // Si hay una lista anidada a la izquierda (recursividad), la visitamos primero
        if ($ctx->listaexp() !== null) {
            $expresiones = $this->visit($ctx->listaexp()); // Esto devuelve un arreglo
        }
        
        // Agregamos la expresión actual de la derecha
        $expresiones[] = $this->visit($ctx->expresion());
        
        return $expresiones; // Devolvemos el arreglo acumulado
    }

  // ==========================================
    // !INSTRUCCIONES DE CONTROL DE FLUJO 
    // ==========================================



    // --- VISITOR PARA EL IF / ELSE IF / ELSE ---
    public function visitSi_stmt($ctx) {
        $condicion = $this->visit($ctx->expresion());
        $bloqueIf = $this->visit($ctx->bloque(0)); // El primer bloque siempre es el del IF
        
        $bloqueElse = null;
        
        // Verificamos si existe un ELSE en la gramática (preguntamos si hay un token TKELSE)
        if ($ctx->TKELSE() !== null) {
            // Si el else va seguido de un IF (else if), ANTLR lo guarda en si_stmt()
            if ($ctx->si_stmt() !== null) {
                $bloqueElse = $this->visit($ctx->si_stmt());
            } 
            // Si el else va seguido de llaves normales, ANTLR lo guarda en el segundo bloque
            else if ($ctx->bloque(1) !== null) {
                $bloqueElse = $this->visit($ctx->bloque(1));
            }
        }
        
        return new \App\Instructions\Si(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $condicion,
            $bloqueIf,
            $bloqueElse
        );
    }


      // !SWITCH / CASE SEGUN

      // --- VISITOR PARA EL SWITCH ---
    public function visitSwitch($ctx) {
        $condicionPrincipal = $this->visit($ctx->expresion());
        
        $casos = [];
        foreach ($ctx->case() as $caseCtx) {
            $casos[] = $this->visit($caseCtx);
        }
        
        $bloqueDefault = null;
        if ($ctx->default() !== null) {
            $bloqueDefault = $this->visit($ctx->default());
        }
        
        return new \App\Instructions\Segun(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $condicionPrincipal,
            $casos,
            $bloqueDefault
        );
    }

    // --- VISITOR PARA LOS CASES (case 1, 2:) ---
    public function visitCase($ctx) {
        // Obtenemos la lista de expresiones usando el visitListaexp que hicimos en la impresión
        $condiciones = $this->visit($ctx->listaexp()); 
        
        $instrucciones = [];
        foreach ($ctx->instruccion() as $instCtx) {
            $instrucciones[] = $this->visit($instCtx);
        }
        
        // Convertimos el arreglo de instrucciones en un Bloque
        $bloque = new \App\Instructions\Bloque(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $instrucciones
        );
        
        return new \App\Instructions\Caso($condiciones, $bloque);
    }

    // --- VISITOR PARA EL DEFAULT ---
    public function visitDefault($ctx) {
        $instrucciones = [];
        foreach ($ctx->instruccion() as $instCtx) {
            $instrucciones[] = $this->visit($instCtx);
        }
        
        return new \App\Instructions\Bloque(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $instrucciones
        );
    }



        // !FOR  / bREACK/  /CONTINUE 
        // ? NOTA  el return aun no porque no tenemos funciones,  y ya me dio error 
        // --- BREAK Y CONTINUE ---
    public function visitBreak($ctx) {
        return new \App\Instructions\Romper($ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
    }

    public function visitContinue($ctx) {
        return new \App\Instructions\Continuar($ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine());
    }

    // Compatibilidad con nombres anteriores de reglas (si no regeneraste parser).
    public function visitSwitch_stmt($ctx) { return $this->visitSwitch($ctx); }
    public function visitCase_stmt($ctx) { return $this->visitCase($ctx); }
    public function visitDefault_stmt($ctx) { return $this->visitDefault($ctx); }
    public function visitBreak_stmt($ctx) { return $this->visitBreak($ctx); }
    public function visitContinue_stmt($ctx) { return $this->visitContinue($ctx); }

    // --- FOR INFINITO: for { ... } ---
    public function visitForInfinito($ctx) {
        $bloque = $this->visit($ctx->bloque());
        return new \App\Instructions\Para(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            null, null, null, $bloque
        );
    }

    // --- FOR MIENTRAS: for x < 5 { ... } ---
    public function visitForMientras($ctx) {
        $condicion = $this->visit($ctx->expresion());
        $bloque = $this->visit($ctx->bloque());
        return new \App\Instructions\Para(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            null, $condicion, null, $bloque
        );
    }

    // --- FOR CLÁSICO: for var i int32 = 0; i < 5; i++ { ... } ---
    public function visitForClasico($ctx) {
        $init = $this->visit($ctx->init());
        $condicion = $this->visit($ctx->expresion());
        $post = $this->visit($ctx->post());
        $bloque = $this->visit($ctx->bloque());
        
        return new \App\Instructions\Para(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $init, $condicion, $post, $bloque
        );
    }






} // Todo: Fin de la clase CustomVisitor
