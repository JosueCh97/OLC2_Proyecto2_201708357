grammar Golampi;

// Agregamos el namespace de PHP para que Composer lo encuentre fácil
@php {
namespace App\Language;
}

inicio: instrucciones EOF;

instrucciones:
            instruccion+;


instruccion: 
        declaracion
        | decl_corta
        | asignacion
        | asig_compuesta     
        | inc_dec
        | si_stmt 
        | switch          
        | imprimir
        | for         
        | break      
        | continue  
        | func_dcl           
        | return_stmt        
        | llamada_stmt
  ; 
bloque: '{' instruccion* '}';
listids:
        IDNAME
        | listids ',' IDNAME;

listaexp:
        expresion
        | listaexp ',' expresion;

// --- NUEVO SISTEMA DE TIPOS Y ASIGNABLES ---
// Un tipo puede ser 'int32' o '[5]int32' o '[2][3]int32'
tipo_var: PUNTERO? ('[' expresion ']')* optipo ;

// Un asignable es una variable 'x' o una posición de arreglo 'nums[0][1]'
asignable: IDNAME ('[' expresion ']')* ;

declaracion:
        TKVAR listids tipo_var 
        | TKVAR listids tipo_var IGUAL listaexp
        | TKCONST listids tipo_var IGUAL listaexp;  

decl_corta: IDNAME (',' IDNAME)* DPIGUAL listaexp ;

asignacion: asignable (',' asignable)* IGUAL listaexp;

asig_compuesta: asignable ( MASIG| MENOSIG| PORIG | DIVIG) expresion ;
inc_dec: asignable (INC | DEC) ;

si_stmt: TKIF expresion bloque (TKELSE (bloque | si_stmt))? ;

imprimir: TKPRINT '(' listaexp ')';

// ---  SWITCH ---
switch: TKSWITCH expresion '{' case* default? '}' ;

case: TKCASE listaexp ':' instruccion* ;

default: TKDEFAULT ':' instruccion* ;

// Para el For clásico, definimos qué puede ir en la inicialización y en la actualización
init: declaracion | decl_corta | asignacion | asig_compuesta | inc_dec;
post: asignacion | asig_compuesta | inc_dec;

// 3 FOR
for:
      TKFOR bloque                                          #ForInfinito
    | TKFOR expresion bloque                                #ForMientras
    | TKFOR init ';' expresion ';' post bloque              #ForClasico
    ;


// DETENER/CONUAR
break: TKBREAK ;
continue: TKCONTINUE ;    

// --- FUNCIONES ---
func_dcl: TKFUNC IDNAME '(' parametros? ')' tipo_retorno? bloque ;
parametros: parametro (',' parametro)* ;
parametro: IDNAME tipo_var ;
tipo_retorno: tipo_var | '(' tipo_var (',' tipo_var)* ')' ;

return_stmt: TKRETURN listaexp? ;
llamada_stmt: IDNAME '(' listaexp? ')' ;


expresion:
         // Operaciones aritméticas - ordenadas por precedencia (menor a mayor)
         '-' expresion                             #ExprUnaria
        | '!' expresion                               #ExprNot

       

        
        | '(' expresion ')'                         #ExprAgrupacion
        //arreglos
        | expresion '[' expresion ']'              #ExprArregloAcceso     
        | tipo_var '{' lista_valores? '}'          #ExprArregloLiteral   
        
        | expresion ('*' | '/' | '%') expresion     #ExprMultiplicacion
        |expresion ('+' | '-') expresion             #ExprSuma
        //Operaciones de comparación (¡CAMBIA ESTA LÍNEA!)
        | expresion (IGUAL_IGUAL | DIFERENTE | MENOR_IGUAL | MAYOR_IGUAL | MENOR | MAYOR) expresion  #ExprComparacion
        //Lógicos (Tienen la menor precedencia)
        | expresion TKAND expresion                   #ExprAnd
        | expresion TKOR expresion                    #ExprOr
        // Llamadas a funciones, Referencias y Desreferencias
        | IDNAME '(' listaexp? ')'                 #ExprLlamada         // suma(3, 4)
        | REFERENCIA asignable                     #ExprReferencia      // &nums2
        | PUNTERO asignable                        #ExprDesreferencia   // *a

        // Agrupación, Acceso a Arreglos y Literales de Arreglos
        | '(' expresion ')'                        #ExprAgrupacion
        | expresion '[' expresion ']'              #ExprArregloAcceso      // <--- NUEVO: nums[0]
        | tipo_var '{' lista_valores? '}'          #ExprArregloLiteral     // <--- NUEVO: [3]int32{1, 2, 3}
    
        

        // Expresiones primarias
        | INT                                       #ExprEntero
        | FLOAT                                     #ExprDecimal
        | BOOL                                      #ExprBooleano
        | STRING                                    #ExprCadena
        | IDNAME                                    #ExprIdentificador
        ;

        // --- REGLAS PARA VALORES DE ARREGLOS LITERALES ---
lista_valores: lista_valor (',' lista_valor)* ;
lista_valor: expresion | '{' lista_valores? '}' ; // Permite anidar {{1, 2}, {3, 4}}
        
optipo:
         TKINT 
        | TKFLOAT 
        | TKBOOL
        | TKRUNE 
        | TKSTRING;


//TK_palabras Reservadas
//? Tipo estatico
TKINT: 'int32';
TKFLOAT: 'float32';
TKBOOL: 'bool';
TKRUNE: 'rune';
TKSTRING: 'string';



// --- TOKENS RELACIONALES ---
IGUAL_IGUAL: '==';
DIFERENTE: '!=';
MENOR_IGUAL: '<=';
MAYOR_IGUAL: '>=';
MENOR: '<';
MAYOR: '>';

// Tokens Logicos
TKAND: '&&';
TKOR: '||';
// ? TK asignacion
TKVAR: 'var';
TKCONST: 'const';
IGUAL: '=';
NIL: 'nil';
//asignacion
DPIGUAL: ':=';
DEC: '--';
INC: '++';
MASIG: '+=';
MENOSIG: '-=';
PORIG: '*=';
DIVIG: '/=';
//? Bloque de sentrencia principal */

//?TK_FUNCIONES SISTEMA
TKFUNC: 'func';
TKPRINT: 'fmt.Print';
// TKLEN: 'len';
// TKNOW: 'now';
// TKSUBSTR: 'substr';
// TYPEOF: 'typeof';


//TK CONTROL DE FLUJO
TKIF: 'if';
TKELSE: 'else';

TKSWITCH: 'switch';
TKCASE: 'case';
TKDEFAULT: 'default';

TKFOR: 'for';
TKBREAK: 'break';
TKCONTINUE: 'continue';

TKRETURN: 'return';
PUNTERO: '*';
REFERENCIA: '&';




BOOL: 'true' | 'false';
STRING: '"' ( '\\' . | ~["\\] )* '"';
UNICODE : '\\u' [0-9a-fA-F] [0-9a-fA-F] [0-9a-fA-F] [0-9a-fA-F];
IDNAME: [a-zA-Z_][a-zA-Z0-9_]*;
INT: [-]*[0-9][0-9]*;
FLOAT: [-]*[0-9]+ '.' [0-9]+;
COMENT: '//' ~[\r\n]* -> skip;
MULTILINE_COMMENT: '/*' .*? '*/' -> skip;
WS: [ \t\r\n]+ -> skip;