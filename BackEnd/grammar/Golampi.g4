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
        | asignacion
        | asig_compuesta     
        | inc_dec
        | si_stmt 
        | switch          
        | imprimir
        | for         
        | break      
        | continue    
  ; 
bloque: '{' instruccion* '}';
listids:
        IDNAME
        | listids ',' IDNAME;

listaexp:
        expresion
        | listaexp ',' expresion;


declaracion:
        TKVAR listids optipo 
        | TKVAR listids optipo IGUAL listaexp
        | TKCONST listids optipo IGUAL listaexp;   

asignacion: listids IGUAL listaexp;

asig_compuesta: IDNAME ( MASIG| MENOSIG| PORIG | DIVIG) expresion ;
inc_dec: IDNAME (INC | DEC) ;

si_stmt: TKIF expresion bloque (TKELSE (bloque | si_stmt))? ;

imprimir: TKPRINT '(' listaexp ')';

// ---  SWITCH ---
switch: TKSWITCH expresion '{' case* default? '}' ;

case: TKCASE listaexp ':' instruccion* ;

default: TKDEFAULT ':' instruccion* ;

// Para el For clásico, definimos qué puede ir en la inicialización y en la actualización
init: declaracion | asignacion | asig_compuesta | inc_dec;
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

expresion:
         // Operaciones aritméticas - ordenadas por precedencia (menor a mayor)
         '-' expresion                             #ExprUnaria
        | '!' expresion                               #ExprNot
        
        | '(' expresion ')'                         #ExprAgrupacion
        
        | expresion ('*' | '/' | '%') expresion     #ExprMultiplicacion
        |expresion ('+' | '-') expresion             #ExprSuma
        //Operaciones de comparación (¡CAMBIA ESTA LÍNEA!)
        | expresion (IGUAL_IGUAL | DIFERENTE | MENOR_IGUAL | MAYOR_IGUAL | MENOR | MAYOR) expresion  #ExprComparacion
        //Lógicos (Tienen la menor precedencia)
        | expresion TKAND expresion                   #ExprAnd
        | expresion TKOR expresion                    #ExprOr
        
        // Expresiones primarias
        | INT                                       #ExprEntero
        | FLOAT                                     #ExprDecimal
        | BOOL                                      #ExprBooleano
        | STRING                                    #ExprCadena
        | IDNAME                                    #ExprIdentificador
        ;


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
DEC: '--';
INC: '++';
MASIG: '+=';
MENOSIG: '-=';
PORIG: '*=';
DIVIG: '/=';
//? Bloque de sentrencia principal */
TKMAIN: 'main';

//?TK_FUNCIONES SISTEMA
TKFUNC: 'func';
TKPRINT: 'fmt.Print';
TKLEN: 'len';
TKNOW: 'now';
TKSUBSTR: 'substr';
TYPEOF: 'typeof';


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




BOOL: 'true' | 'false';
STRING: '"' ( '\\' . | ~["\\] )* '"';
UNICODE : '\\u' [0-9a-fA-F] [0-9a-fA-F] [0-9a-fA-F] [0-9a-fA-F];
IDNAME: [a-zA-Z_][a-zA-Z0-9_]*;
INT: [-]*[0-9][0-9]*;
FLOAT: [-]*[0-9]+ '.' [0-9]+;
COMENT: '//' ~[\r\n]* -> skip;
MULTILINE_COMMENT: '/*' .*? '*/' -> skip;
WS: [ \t\r\n]+ -> skip;