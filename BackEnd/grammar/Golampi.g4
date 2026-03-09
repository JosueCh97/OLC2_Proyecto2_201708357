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
        ; 

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




expresion:
         // Operaciones aritméticas - ordenadas por precedencia (menor a mayor)
         '-' expresion                             #ExprUnaria
        | '(' expresion ')'                         #ExprAgrupacion
        | expresion ('*' | '/' | '%') expresion     #ExprMultiplicacion
        |expresion ('+' | '-') expresion             #ExprSuma
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


// ? TK asignacion
TKVAR: 'var';
TKCONST: 'const';
IGUAL: '=';
NIL: 'nil';
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