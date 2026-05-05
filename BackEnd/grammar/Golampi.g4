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
        | bloque
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

// Un asignable puede ser una variable, una posicion de arreglo o un puntero dereferenciado
asignable: PUNTERO? IDNAME ('[' expresion ']')* ;

declaracion:
        TKVAR listids tipo_var 
        | TKVAR listids tipo_var IGUAL listaexp
        | TKCONST listids tipo_var IGUAL listaexp;  

decl_corta: IDNAME (',' IDNAME)* DPIGUAL listaexp ;

asignacion: asignable (',' asignable)* IGUAL listaexp;

asig_compuesta: asignable ( MASIG| MENOSIG| PORIG | DIVIG | MODIG) expresion ;
inc_dec: asignable (INC | DEC) ;

si_stmt: TKIF (init ';')? expresion bloque (TKELSE (bloque | si_stmt))? ;

imprimir: (TKPRINTLN | TKPRINT) '(' listaexp? ')';

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
    | TKFOR IDNAME TKIN expresion RANGO expresion bloque    #ForRango
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
         '-' expresion                                                     #ExprUnaria
        | '!' expresion                                                    #ExprNot
        | '(' expresion ')'                                                #ExprAgrupacion
        | expresion '[' expresion ']'                                      #ExprArregloAcceso
        | optipo '(' expresion ')'                                         #ExprCasteo
        | tipo_var '{' lista_valores? '}'                                  #ExprArregloLiteral
        | expresion ('*' | '/' | '%') expresion                            #ExprMultiplicacion
        | expresion ('+' | '-') expresion                                  #ExprSuma
        | expresion (IGUAL_IGUAL | DIFERENTE | MENOR_IGUAL | MAYOR_IGUAL | MENOR | MAYOR) expresion  #ExprComparacion
        | expresion (TKNOT TKIN | TKIN) '[' expresion RANGO expresion ']' #ExprRango
        | expresion TKAND expresion                                        #ExprAnd
        | expresion TKOR expresion                                         #ExprOr
        | IDNAME '(' listaexp? ')'                                         #ExprLlamada
        | REFERENCIA asignable                                             #ExprReferencia
        | PUNTERO asignable                                                #ExprDesreferencia
        | NIL                                                              #ExprNil
        | RUNE                                                             #ExprCaracter
        | INT                                                              #ExprEntero
        | FLOAT                                                            #ExprDecimal
        | BOOL                                                             #ExprBooleano
        | STRING                                                           #ExprCadena
        | IDNAME                                                           #ExprIdentificador
        ;

        // --- REGLAS PARA VALORES DE ARREGLOS LITERALES ---
lista_valores: lista_valor (',' lista_valor)* ','? ;
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


TKIN: 'in';
TKNOT: 'not';
RANGO: '..';
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
MODIG: '%=';
//? Bloque de sentrencia principal */

//?TK_FUNCIONES SISTEMA
TKFUNC: 'func';
TKPRINTLN: 'fmt.Println';
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
RUNE: '\'' ( '\\' . | ~['\\] ) '\'';
STRING: '"' ( '\\' . | ~["\\] )* '"';
UNICODE : '\\u' [0-9a-fA-F] [0-9a-fA-F] [0-9a-fA-F] [0-9a-fA-F];
IDNAME: [a-zA-Z_][a-zA-Z0-9_]*;
INT: [0-9][0-9]*;
FLOAT: [0-9]+ '.' [0-9]+;
COMENT: '//' ~[\r\n]* -> skip;
MULTILINE_COMMENT: '/*' .*? '*/' -> skip;
WS: [ \t\r\n]+ -> skip;