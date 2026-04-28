<?php
namespace App\Interprete;

use App\Language\GolampiBaseVisitor;
use App\Instructions\DeclaracionID;
use App\Instructions\IncDec;
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
            Salida::reportarError(
                'Sintactico',
                "Declaracion incompleta. Se esperaba una expresion despues de '='",
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null; // Ignorar esta declaración
        }

        // --- EXTRACCIÓN DE IDs ---
        $ids = $this->extraerIdsDeListids($listaCtx);

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
                Salida::reportarError(
                    'Sintactico',
                    'Se declararon ' . count($ids) . ' IDs pero se asignaron ' . count($valores) . ' valores',
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );
                return null; // Ignorar esta declaración
            }
        }

        // --- OBTENER EL TIPO BASE (int32, float32, etc.) ---
        // Con la nueva gramatica, declaracion usa tipo_var y no optipo directo.
        $tipoVarCtx = $ctx->tipo_var();
        if ($tipoVarCtx === null || $tipoVarCtx->optipo() === null) {
            Salida::reportarError(
                'Sintactico',
                'Tipo de declaracion invalido o incompleto',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }
        list($tipoDato, $dimensionesExpr) = $this->analizarTipoVar($tipoVarCtx);
        // --- RETORNAR EL NODO AST ---
        // En lugar de hacer echo, construimos nuestro objeto DeclaracionID
        return new DeclaracionID(
            $ctx->getStart()->getLine(), 
            $ctx->getStart()->getCharPositionInLine(), 
            count($ids) === 1 ? $ids[0] : $ids, // Si es uno, mandamos string, si son varios, mandamos array
            $tipoDato, 
            count($valores) === 1 ? $valores[0] : $valores,
            $isconst,
            $dimensionesExpr
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
        // La regla expresion usa alternativas etiquetadas (#Expr...)
        // y se resuelve en visitExpr*; este metodo queda como fallback.
        return $this->visitChildren($ctx);
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

    private function extraerIdsDeListids($listaCtx): array {
        $ids = [];

        while ($listaCtx !== null) {
            $idNode = $listaCtx->IDNAME();
            if ($idNode !== null) {
                $ids[] = $idNode->getText();
            }
            $listaCtx = $listaCtx->listids();
        }

        return array_reverse($ids);
    }

    private function construirIndicesDesdeAsignable($asignableCtx): array {
        $indices = [];
        $indicesCtx = $asignableCtx !== null ? $asignableCtx->expresion() : null;

        if (is_array($indicesCtx)) {
            foreach ($indicesCtx as $indiceCtx) {
                $indices[] = $this->visit($indiceCtx);
            }
        }

        return $indices;
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

    public function visitExprCasteo($ctx) {
        $tipoDestino = $this->visitOptipo($ctx->optipo());
        $valor = $this->visit($ctx->expresion());

        return new \App\Expressions\Casteo(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $tipoDestino,
            $valor
        );
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
        $textoLimpio = stripcslashes(substr($texto, 1, -1));
        
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

    public function visitExprCaracter($ctx) {
        $texto = $ctx->RUNE()->getText();
        $textoLimpio = stripcslashes(substr($texto, 1, -1));

        return new \App\Expressions\Primitivo(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $textoLimpio,
            \App\Utilities\Tipo::CARACTER
        );
    }

    public function visitExprNil($ctx) {
        return new \App\Expressions\Primitivo(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            null,
            \App\Utilities\Tipo::NIL
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

    public function visitExprRango($ctx) {
        $valor = $this->visit($ctx->expresion(0));
        $inicio = $this->visit($ctx->expresion(1));
        $fin = $this->visit($ctx->expresion(2));
        $esNegado = $ctx->TKNOT() !== null;

        return new \App\Expressions\Rango(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $valor,
            $inicio,
            $fin,
            $esNegado
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
    // ASIGNACIONES (=, +=, -=, ++, --)
    // ==========================================

    // Para x = 10;  x, y = 1, 2;  arr[0] = 99;
    public function visitAsignacion($ctx) {
        $asignablesRaw = $ctx->asignable();
        $asignables = [];

        if (is_array($asignablesRaw)) {
            $asignables = $asignablesRaw;
        } elseif ($asignablesRaw !== null) {
            $asignables[] = $asignablesRaw;
        }

        if (empty($asignables)) {
            Salida::reportarError(
                'Sintactico',
                'Asignacion invalida, no hay variables destino',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        $exprCtxs = $this->extractarExpresionesDeListaexp($ctx->listaexp());
        $valores = [];
        foreach ($exprCtxs as $exprCtx) {
            $valores[] = $this->visit($exprCtx);
        }
        



        if (count($asignables) !== count($valores) && count($valores) !== 1  ) {
            Salida::reportarError(
                'Sintactico',
                'La cantidad de destinos no coincide con la cantidad de valores',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        // Caso especial: asignación sobre arreglo, ej. nums[0] = 10
        if (count($asignables) === 1) {
            $asignable = $asignables[0];
            $idToken = $asignable !== null ? $asignable->IDNAME() : null;
            $esPuntero = $asignable !== null && $asignable->PUNTERO() !== null;
            if ($idToken === null) {
                Salida::reportarError(
                    'Sintactico',
                    'Destino de asignacion invalido',
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );
                return null;
            }

            $indices = $this->construirIndicesDesdeAsignable($asignable);

            if ($esPuntero) {
                return new \App\Instructions\AsignacionPuntero(
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine(),
                    $idToken->getText(),
                    $valores[0]
                );
            }

            if (!empty($indices)) {
                return new \App\Instructions\AsignacionArreglo(
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine(),
                    $idToken->getText(),
                    $indices,
                    $valores[0]
                );
            }

            return new \App\Instructions\Asignacion(
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine(),
                $idToken->getText(),
                $valores[0]
            );
        }

        // Asignación múltiple: x, y = a, b
        $ids = [];
        foreach ($asignables as $asignable) {
            $idToken = $asignable !== null ? $asignable->IDNAME() : null;
            if ($idToken === null) {
                Salida::reportarError(
                    'Sintactico',
                    'Destino invalido en asignacion multiple',
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );
                return null;
            }

            if ($asignable->PUNTERO() !== null) {
                Salida::reportarError(
                    'Sintactico',
                    'No se soporta asignacion multiple con destinos desreferenciados',
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );
                return null;
            }

            $indicesCtx = $asignable->expresion();
            if (is_array($indicesCtx) && count($indicesCtx) > 0) {
                Salida::reportarError(
                    'Sintactico',
                    'No se soporta mezcla de asignacion multiple con indices de arreglo',
                    $ctx->getStart()->getLine(),
                    $ctx->getStart()->getCharPositionInLine()
                );
                return null;
            }

            $ids[] = $idToken->getText();
        }

        return new \App\Instructions\Asignacion(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $ids,
            $valores
        );
    }



public function visitAsig_compuesta($ctx) {
        $asignableCtx = $ctx->asignable();
        $id = $asignableCtx->IDNAME()->getText();
        
        // REGRESAMOS A TU CÓDIGO ORIGINAL: Usamos expresion() en lugar de listaexp()
        $valor = $this->visit($ctx->expresion());
        
        $operador = $ctx->getChild(1)->getText(); // +=, -=, *=, /=

        if ($asignableCtx->PUNTERO() !== null) {
            \App\Utilities\Salida::reportarError(
                'Sintactico',
                'Asignacion compuesta sobre punteros no soportada aun',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        // Validamos que no intenten hacer nums[0] += 5 por ahora
        if (count($asignableCtx->expresion()) > 0) {
            \App\Utilities\Salida::reportarError(
                'Sintactico',
                'Asignacion compuesta en arreglos no soportada aun',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        // ✅ EL ÚNICO CAMBIO QUE NECESITABAS: El orden ($id, $operador, $valor)
        return new \App\Instructions\AsignacionCompuesta(
            $ctx->getStart()->getLine(), 
            $ctx->getStart()->getCharPositionInLine(),
            $id, 
            $operador, 
            $valor
        );
    }
    



 public function visitInc_dec($ctx) {
        $asignableCtx = $ctx->asignable();
        $id = $asignableCtx->IDNAME()->getText();
        $operador = $ctx->getChild(1)->getText(); // ++ o --

        if ($asignableCtx->PUNTERO() !== null) {
            Salida::reportarError(
                'Sintactico',
                'Incremento/Decremento sobre punteros no soportado aun',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        // Validamos que no intenten hacer nums[0]++ por ahora
        if (count($asignableCtx->expresion()) > 0) {
            Salida::reportarError(
                'Sintactico',
                'Incremento/Decremento en arreglos no soportado aun',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        return new IncDec(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $id, $operador
        );
    }
    
  // ==========================================
    // !BLOQUE DE INTRUCCIONES 
    // ==========================================


    // // --- VISITOR PARA EL BLOQUE ---
    // public function visitBloque($ctx) {
    //     $instrucciones = [];
    //     // Recorremos todas las instrucciones que haya dentro de las llaves { }
    //     foreach ($ctx->instruccion() as $instCtx) {
    //         $instrucciones[] = $this->visit($instCtx);
    //     }
        
    //     return new \App\Instructions\Bloque(
    //         $ctx->getStart()->getLine(),
    //         $ctx->getStart()->getCharPositionInLine(),
    //         $instrucciones
    //     );
    // }

 public function visitBloque($ctx) {
        $instrucciones = [];
        
        // Verificamos si hay instrucciones dentro de las llaves { }
        if ($ctx->instruccion() !== null) {
            foreach ($ctx->instruccion() as $instruccionCtx) {
                $nodo = $this->visit($instruccionCtx);
                
                // ✅ Solo guardamos si NO es null (ignoramos errores sintácticos)
                if ($nodo !== null) {
                    $instrucciones[] = $nodo;
                }
            }
        }
        
        // ✅ EL PASO QUE FALTABA: Obtener línea/columna y retornar el OBJETO Bloque
        $linea = $ctx->getStart()->getLine();
        $columna = $ctx->getStart()->getCharPositionInLine();
        
        return new \App\Instructions\Bloque($linea, $columna, $instrucciones);
    }

    //!  SALIDA (PRINT)
    // --- VISITOR PARA IMPRIMIR ---
    public function visitImprimir($ctx) {
        // Obtenemos el arreglo plano de expresiones visitando la lista
        $expresiones = $ctx->listaexp() !== null ? $this->visit($ctx->listaexp()) : [];

        // true = fmt.Println (agrega \n al final), false = fmt.Print (sin \n)
        $esNewLine = $ctx->TKPRINTLN() !== null;

        return new \App\Instructions\Imprimir(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $expresiones,
            $esNewLine
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
        $init = $ctx->init() !== null ? $this->visit($ctx->init()) : null;
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
        
        $si = new \App\Instructions\Si(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $condicion,
            $bloqueIf,
            $bloqueElse
        );

        if ($init === null) {
            return $si;
        }

        return new \App\Instructions\Bloque(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            [$init, $si]
        );
    }

    public function visitInit($ctx) {
        return $this->visitChildren($ctx);
    }

    public function visitPost($ctx) {
        return $this->visitChildren($ctx);
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



    /**
    // arreglos
     */

    public function analizarTipoVar($ctxTipoVar): array {
        // 1. Obtenemos el tipo base
        $tipoBaseStr = $ctxTipoVar->optipo()->getText();
        $tipoBase = match ($tipoBaseStr) {
            'int32' => Tipo::ENTERO,
            'float32' => Tipo::DECIMAL,
            'bool' => Tipo::BOOLEANO,
            'string' => Tipo::CADENA,
            'rune' => Tipo::CARACTER,
            default => Tipo::NIL
        };

        // 2. Extraemos las expresiones de las dimensiones sin evaluarlas aún
        $dimensionesExpr = [];
        foreach ($ctxTipoVar->expresion() as $expCtx) {
            $dimensionesExpr[] = $this->visit($expCtx);
        }

        return [$tipoBase, $dimensionesExpr];
    }

public function visitExprArregloAcceso($ctx) {
        // Visitamos el índice actual (el de la derecha, ej: 'j')
        $indiceNuevo = $this->visit($ctx->expresion(1));
        
        // Visitamos la parte izquierda (ej: 'm[i]' o simplemente 'nums')
        $nodoIzquierdo = $this->visit($ctx->expresion(0));

        // Si la parte izquierda ya era un Acceso a Arreglo, 
        // significa que estamos en una matriz. Solo le sumamos el nuevo índice.
        if ($nodoIzquierdo instanceof \App\Expressions\AccesoArreglo) {
            $nodoIzquierdo->agregarIndice($indiceNuevo);
            return $nodoIzquierdo; 
        }

        // Si es la primera vez (ej: 'nums[0]'), creamos el acceso base extrayendo el ID real
        $id = $ctx->expresion(0)->getText();
        return new \App\Expressions\AccesoArreglo(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $id,
            [$indiceNuevo]
        );
    }

    public function visitExprReferencia($ctx) {
        $asignableCtx = $ctx->asignable();
        $idToken = $asignableCtx !== null ? $asignableCtx->IDNAME() : null;

        if ($asignableCtx === null || $idToken === null || $asignableCtx->PUNTERO() !== null) {
            Salida::reportarError(
                'Sintactico',
                'Referencia invalida',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        return new \App\Expressions\Referencia(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $idToken->getText(),
            $this->construirIndicesDesdeAsignable($asignableCtx)
        );
    }

    public function visitExprDesreferencia($ctx) {
        $asignableCtx = $ctx->asignable();
        $idToken = $asignableCtx !== null ? $asignableCtx->IDNAME() : null;

        if ($asignableCtx === null || $idToken === null || $asignableCtx->PUNTERO() !== null) {
            Salida::reportarError(
                'Sintactico',
                'Desreferencia invalida',
                $ctx->getStart()->getLine(),
                $ctx->getStart()->getCharPositionInLine()
            );
            return null;
        }

        return new \App\Expressions\Desreferencia(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $idToken->getText(),
            $this->construirIndicesDesdeAsignable($asignableCtx)
        );
    }

 





    // --- VISITOR PARA EL LITERAL [3]int32{1, 2, 3} ---
    public function visitExprArregloLiteral($ctx) {
        // Aprovechamos tu función perfectamente modificada para sacar el tipo y dimensiones
        list($tipoBase, $dimensionesExpr) = $this->analizarTipoVar($ctx->tipo_var());

        $valores = [];
        if ($ctx->lista_valores() !== null) {
            $valores = $this->visit($ctx->lista_valores());
        }

        return new \App\Expressions\ArregloLiteral(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $tipoBase,
            $dimensionesExpr,
            $valores
        );
    }

    // --- VISITORS PARA EXTRAER LAS LISTAS DE EXPRESIONES ---
    public function visitLista_valores($ctx) {
        $valores = [];
        foreach ($ctx->lista_valor() as $valorCtx) {
            $valores[] = $this->visit($valorCtx);
        }
        return $valores; // Retorna un array de expresiones
    }

    public function visitLista_valor($ctx) {
        // Si es un valor simple (ej: 5)
        if ($ctx->expresion() !== null) {
            return $this->visit($ctx->expresion());
        }
        // Si es un sub-arreglo anidado (ej: {1, 2})
        if ($ctx->lista_valores() !== null) {
            return $this->visit($ctx->lista_valores());
        }
        return [];
    }


    // --- VISITOR PARA DECLARACIÓN CORTA: x := 10;  a, b := 1, 2;// --- VISITOR PARA DECLARACIÓN CORTA ( x, y := 1, 2 ) ---
    public function visitDecl_corta($ctx) {
        // 1. Extraemos todos los IDs de la izquierda
        $ids = [];
        foreach ($ctx->IDNAME() as $idNode) {
            $ids[] = $idNode->getText();
        }

        // 2. Extraemos y visitamos las expresiones de la derecha usando tu función helper
        $exprCtxs = $this->extractarExpresionesDeListaexp($ctx->listaexp());
        $valores = [];
        foreach ($exprCtxs as $exprCtx) {
            $valores[] = $this->visit($exprCtx);
        }

        return new \App\Instructions\DeclaracionCorta(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $ids,
            $valores
        );
    }

//---------------------------------------------------
// Todo: funciones y parametrosn
//---------------------------------------------------
public function visitFunc_dcl($ctx) {
        $nombre = $ctx->IDNAME()->getText();
        
        // 1. Extraer parámetros (si hay)
        $parametros = [];
        if ($ctx->parametros() !== null) {
            $parametros = $this->visit($ctx->parametros());
        }

        // 2. Extraer tipos de retorno (si hay)
        $tiposRetorno = [];
        if ($ctx->tipo_retorno() !== null) {
            $tiposRetorno = $this->visit($ctx->tipo_retorno());
        }

        // 3. Extraer el bloque de código
        $bloque = $this->visit($ctx->bloque());

        return new \App\Instructions\Funcion(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $nombre,
            $parametros,
            $tiposRetorno,
            $bloque
        );
    }

    //return
    // Extrae la lista completa de parámetros
    public function visitParametros($ctx) {
        $params = [];
        foreach ($ctx->parametro() as $paramCtx) {
            $params[] = $this->visit($paramCtx);
        }
        return $params;
    }

    // Extrae un solo parámetro (ej: x *[5]int32)
    public function visitParametro($ctx) {
        $id = $ctx->IDNAME()->getText();
        
        // Verificamos si tiene el símbolo '*' de puntero
        $isPuntero = $ctx->tipo_var()->PUNTERO() !== null;
        
        list($tipoBase, $dimensiones) = $this->analizarTipoVar($ctx->tipo_var());

        return new \App\Expressions\Parametro($id, $tipoBase, $dimensiones, $isPuntero);
    }

    // Extrae los tipos de retorno (puede ser 1 o varios)
    public function visitTipo_retorno($ctx) {
        $tipos = [];
        foreach ($ctx->tipo_var() as $tipoCtx) {
            $isPuntero = $tipoCtx->PUNTERO() !== null;
            list($tipoBase, $dimensiones) = $this->analizarTipoVar($tipoCtx);
            $tipos[] = [
                "tipoBase" => $tipoBase,
                "dimensiones" => $dimensiones,
                "isPuntero" => $isPuntero
            ];
        }
        return $tipos; // Retorna un array de arreglos asociativos
    }

    // Extrae la instrucción Return
    public function visitReturn_stmt($ctx) {
        $valores = [];
        if ($ctx->listaexp() !== null) {
            $exprCtxs = $this->extractarExpresionesDeListaexp($ctx->listaexp());
            foreach ($exprCtxs as $expCtx) {
                $valores[] = $this->visit($expCtx);
            }
        }

        return new \App\Instructions\Retorno(
            $ctx->getStart()->getLine(),
            $ctx->getStart()->getCharPositionInLine(),
            $valores
        );
    }



    //?llamadas
    // Para cuando la llamada está sola en una línea (Ej: imprimirArbol() )
    public function visitLlamada_stmt($ctx) {
        $nombre = $ctx->IDNAME()->getText();
        $args = [];
        
        if ($ctx->listaexp() !== null) {
            $exprCtxs = $this->extractarExpresionesDeListaexp($ctx->listaexp());
            foreach ($exprCtxs as $exprCtx) {
                $args[] = $this->visit($exprCtx);
            }
        }
        
        $llamadaExpr = new \App\Expressions\Llamada(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $nombre, $args
        );
        
        return new \App\Instructions\LlamadaInstr(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $llamadaExpr
        );
    }

    // Para cuando la llamada es parte de una ecuación (Ej: x = suma(1, 2) )
    public function visitExprLlamada($ctx) {
        $nombre = $ctx->IDNAME()->getText();
        $args = [];
        
        if ($ctx->listaexp() !== null) {
            $exprCtxs = $this->extractarExpresionesDeListaexp($ctx->listaexp());
            foreach ($exprCtxs as $exprCtx) {
                $args[] = $this->visit($exprCtx);
            }
        }
        
        return new \App\Expressions\Llamada(
            $ctx->getStart()->getLine(), $ctx->getStart()->getCharPositionInLine(),
            $nombre, $args
        );
    }

} // Todo: Fin de la clase CustomVisitor
