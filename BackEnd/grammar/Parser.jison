// *analizaor lexico 

%{
    //?JavaScript
    let { errores } = require ('../Clases/Utilidades/Salida')
    const { Error } = require ('../Clases/Utilidades/Error')
    const { TipoError } = require ('../Clases/Utilidades/TipoError')
%}

%lex
// Expresiones Regulares
UNUSED      [\s\r\t]+
CONTENT     ([^\n\"\\]|\\.)
ID          [a-zA-Z_][a-zA-Z0-9_]*
STRING      \"({CONTENT}*)\"
CHAT        \'({CONTENT})\'
INTEGER     [0-9]+\b
DOUBLE      [0-9]+\.[0-9]+\b
COMMENTS    \/\/.*
COMMENTM    [/][*][^*]*[*]+([^/*][^*]*[*]+)*[/]

%%
// Reglas semánticas
\n                      {}
{COMMENTS}              {}
{COMMENTM}              {}
{UNUSED}                {}
// === TOKENS ===
// === RESERVADAS ===
'ingresar'              { return 'RW_ingresar' }
'como'                  { return 'RW_como'     }
'con'                   { return 'RW_con'      }
'valor'                 { return 'RW_valor'    }
'imprimir'              { return 'RW_imprimir' }
'nl'                    { return 'RW_ln'      }
'verdadero'             { return 'RW_verdadero'}
'falso'                 { return 'RW_falso'    }
'fin'                   { return 'RW_fin'      }
'o'                     { return 'RW_o'       }
'si'                    { return 'RW_si'       }
'de lo contrario'       { return 'RW_deLoContrario'}
'segun'                { return 'RW_segun'    }
'hacer'                 { return 'RW_hacer'    }
'en caso de ser'        { return 'RW_enCasoDeSer'}
'detener'                { return 'RW_detener'    }
'mayuscula'             { return 'RW_mayuscula'}
'minuscula'            { return 'RW_minuscula'}
'longitud'             { return 'RW_longitud' }
'truncar'              { return 'RW_truncar'  }
'redondear'           { return 'RW_redondear'}
'tipo'                 { return 'RW_tipo'     }
'Lista'                { return 'RW_Lista'    }


'para'                  { return 'RW_para'     }
'mientras'              { return 'RW_mientras' }
'repetir'               { return 'RW_repetir'  }
'que'                  { return 'RW_que'      }
'hasta'                 { return 'RW_hasta'    }
'incremento'            { return 'RW_incremento' }
'decremento'           { return 'RW_decremento'}
'hacer'                 { return 'RW_hacer'    }
'entonces'              { return 'RW_entonces' }   
'retornar'              { return 'RW_retornar' }
'regresar'              { return 'RW_regresar' }
'continuar'             { return 'RW_continuar'}
'inc'         { return 'RW_inc'}
'dec'         { return 'RW_dec'}
'funcion'               { return 'RW_funcion'  }
'parametros'            { return 'RW_parametros' }
'ejecutar'              { return 'RW_ejecutar' }
'procedimiento'         { return 'RW_porcedimiento' }

// === TIPOS DE DATOS ===
'entero'                { return 'RW_entero'   }
'decimal'               { return 'RW_decimal'  }
'caracter'              { return 'RW_caracter' }
'booleano'              { return 'RW_booleano' }
'cadena'                { return 'RW_cadena'   }
// === EXPRESIONES ===
{ID}                    { return 'TK_id'       }
//{STRING}                { yytext = yytext.substr(1,yyleng - 2); return 'TK_string'   }
{STRING} {
  try {
    yytext = JSON.parse(yytext); // convierte las secuencias \n, \t, etc.
  } catch(e) {
    console.error("Error interpretando string:", yytext);
  }
  return 'TK_string';
}
{CHAT}                  { yytext = yytext.substr(1,yyleng - 2); return 'TK_char'     }
{DOUBLE}                { return 'TK_double'   }
{INTEGER}               { return 'TK_integer'  }
'ejecutar '          { return 'Tk_ejecutar'  }
// === ASIGNACION ===
'->'                    { return 'TK_asign'    }
// === OPERADORES ===
// === RELACIONALES ===
'=='                    { return 'TK_igual'    }
'!='                    { return 'TK_dif'      }
'>='                    { return 'TK_mayorI'   }
'<='                    { return 'TK_menorI'   }
'>'                     { return 'TK_mayor'    }
'<'                     { return 'TK_menor'    }
// === LOGICOS ===
'&&'                    { return 'TK_and'      }
'||'                    { return 'TK_or'       }
'!'                     { return 'TK_not'      }
// === INC DEC ===
'++'                    { return 'TK_inc'     }
'--'                    { return 'TK_dec'     }
// === ARITMETICOS ===
'+'                     { return 'TK_suma'     }
'-'                     { return 'TK_resta'    }
'*'                     { return 'TK_mult'     }
'/'                     { return 'TK_div'      }
'%'                     { return 'TK_mod'      }
'^'                     { return 'TK_pot'    }
// === SIGNOS DE AGRUPACION Y FINALIZACION ===
'('                     { return 'TK_parA'     }
')'                     { return 'TK_parC'     }
','                     { return 'TK_coma'     }
//



.                       { errores.push(new Error(yylloc.first_line, yylloc.first_column + 1, TipoError.LEXICO, `Caracter no reconocido «${yytext}»`)); }
<<EOF>>        {return 'EOF'; }

/lex
%{
    // Tipos
     const { Tipo } = require ('../Clases/Utilidades/Tipo')
    // Instrucciones
     const { DeclaracionID } = require ('../Clases/Instrucciones/DeclaracionID')
     const { Asignacion } = require ('../Clases/Instrucciones/Asignacion')
     const { Imprimir } = require ('../Clases/Instrucciones/Imprimir')
     const { Si } = require ('../Clases/Instrucciones/Si')
     const { Segun } = require ('../Clases/Instrucciones/Segun')
     const { Para } = require ('../Clases/Instrucciones/Para')
     const { Mientras } = require ('../Clases/Instrucciones/Mientras')
     const { Hasta } = require ('../Clases/Instrucciones/Hasta')

     const { Continuar } = require ('../Clases/Instrucciones/Continuar')
     const { Detener } = require ('../Clases/Instrucciones/Detener')
     const { Funcion } = require ('../Clases/Instrucciones/Funcion')
     const { Procedimiento } = require ('../Clases/Instrucciones/Procedimiento')
     // Expresiones
    const { Primitivo } = require ('../Clases/Expresiones/Primitivo')
    const { Lista } = require ('../Clases/Expresiones/Lista')
    const { AccesoID } = require ('../Clases/Expresiones/AccesoID')
    const { IncDec } = require ('../Clases/Expresiones/IncDec')
    const { Aritmetico } = require ('../Clases/Expresiones/Aritmetico')
    const { Relacional } = require ('../Clases/Expresiones/Relacional')
    const { Logico } = require ('../Clases/Expresiones/Logico')
    const { Retornar } = require ('../Clases/Expresiones/Retornar')
    const { Parametro } = require ('../Clases/Expresiones/Parametro')
    const { LlamadaFUncion } = require ('../Clases/Expresiones/LlamadaFUncion')
    const { FuncionesSistema } = require ('../Clases/Expresiones/FuncionesSistema')
    const { Casteos } = require ('../Clases/Expresiones/Casteos')
%}



// Precedencia de Operadores
%left 'TK_or'
%left 'TK_and'
%right 'TK_not'
%left 'TK_igual' 'TK_dif'
%left 'TK_menor' 'TK_menorI' 'TK_mayor' 'TK_mayorI'
%left 'TK_suma' 'TK_resta'
%left 'TK_mult' 'TK_div' 'TK_mod'
%left 'TK_pot'
%right TK_negacionUnaria
%nonassoc CAST

//Gramatica
%start INICIO   
%%

INICIO :
        INSTRUCCIONES EOF  {return $1;}
        | EOF {return [];} ;


INSTRUCCIONES :
            INSTRUCCIONES INSTRUCCION {$$.push($2)} 
            | INSTRUCCION               {$$ = [$1]  } ;


INSTRUCCION :
        DECLARACION       {$$ = $1} 
        |DECLARACION_LIST {$$ = $1}
        | ASIGNACION        {$$ = $1} 
        | LLAMAR_FUNCIONES_METODOS {$$ = $1} 
        | FUNCIONES_DEFINIDAS {$$ = $1} 
        | PROCEDIMIENTO {$$ = $1}
        | FUNCIONES_METODOS {$$ = $1}
        | IMPRIMIR          {$$ = $1} 
        | CONDICIONAL_SI    {$$ = $1} 
        | CONDICIONAL_SEGUN    {$$ = $1} 
        | CICLO_PARA        {$$ = $1} 
        | CICLO_WHILE      {$$ = $1}
        | CICLO_DOWHILE     {$$ = $1}
        | SECUNDARIAS       {$$ = $1}
        
      // |error             {errores.push(new Error(this._$.first_line, this._$.first_column + 1, TipoError.SINTACTICO, `No se esperaba el caracter <  ${yytext}   >`))} 
         ;

SECUNDARIAS:
         RW_continuar   {$$ = new Continuar(@1.first_line, @1.first_column)} 
        | RW_detener   {$$ = new Detener(@1.first_line, @1.first_column)}
        | INCREMENTO {$$ = $1}
        | INCREMENTO2 {$$ = $1}
        | RETORNO           {$$ = $1} 
        //|FUNCIONES_NATIVAS
        |CASTEOS {$$ = $1}
        ;

 RETORNO : 
        RW_retorar           {$$ = new Retornar(@1.first_line, @1.first_column, null);}
        |RW_retornar EXPRESION {$$ = new Retornar(@1.first_line, @1.first_column, $2);  } ;        

DECLARACION : RW_ingresar TK_id RW_como TIPO OPTION_DECLARACION  {$$ = new DeclaracionID(@1.first_line, @1.first_column, $2, $4, $5)} ;

OPTION_DECLARACION : 
        RW_con RW_valor EXPRESION {$$ = $3}
        | /* vacío */      {$$ = null}   ;

//************************************************************


DECLARACION_LIST:
    RW_ingresar RW_Lista TK_parA EXPRESION TK_coma TIPO TK_parC TK_id TK_asign ILISTA {
        
        $$ = new DeclaracionID(@1.first_line, @1.first_column, $8, $6,  new Lista(@1.first_line, @1.first_column, $4, $6, $8, $10));}
;

ILISTA:
    TK_parA OPELEMENTOS TK_parC {$$ = $2}
;

OPELEMENTOS:
    OPELEMENTOS TK_coma ELEMENTOS {$$ = $1; $$?.push($3)}
    | ELEMENTOS {$$ = [$1]}
;

ELEMENTOS: 
    ELEMENTO {$$ = $1}
    | ILISTA  {$$ = $1}
;


ELEMENTO:
        | TK_id                         {$$ = new AccesoID(@1.first_line, @1.first_column, $1                )} 
        | RW_verdadero                  {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.BOOLEANO)} 
        | RW_falso                      {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.BOOLEANO)} 
        | TK_string                     {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.CADENA  )} 
        | TK_char                       {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.CARACTER)} 
        | TK_double                     {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.DECIMAL )} 
        | TK_integer                    {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.ENTERO  )} 
        ;

//************************************************************

IMPRIMIR :  RW_imprimir EXPRESION {$$ = new Imprimir(@1.first_line, @1.first_column, $2)} 
            | RW_imprimir RW_ln EXPRESION  {$$ = new Imprimir(@1.first_line, @1.first_column, $3 , true)};


ASIGNACION :
            TK_id TK_asign EXPRESION {$$ = new Asignacion(@1.first_line, @1.first_column, $1, $3)} ;

INCREMENTO :
        TK_id TK_inc {$$ = new IncDec(@1.first_line, @1.first_column, $1, 'inc')} 
        | TK_id TK_dec {$$ = new IncDec(@1.first_line, @1.first_column, $1, 'dec')} ;

INCREMENTO2 :
         RW_inc TK_parA TK_id TK_parC {$$ = new IncDec(@1.first_line, @1.first_column, $3, 'inc')} 
        | RW_dec TK_parA TK_id TK_parC {$$ = new IncDec(@1.first_line, @1.first_column, $3, 'dec')} ;

//*CONDICIONEALES
// *SI
CONDICIONAL_SI :
         RW_si EXPRESION RW_entonces INSTRUCCIONES ELSEIFS ELSE RW_fin RW_si  {
        let condicionI={condicion: $2, instrucciones: $4}; // Creamos un objeto con la condicion y las instrucciones
        let listaCondiciones = [condicionI]; // Creamos una lista con la primera condición
        listaCondiciones = listaCondiciones.concat($5); // Agregamos los Elseifs a la lista
        $$ = new Si(@1.first_line, @1.first_column, listaCondiciones, $6 ); // Pasamos los datos necesarios al constructor de Si
    } ;

ELSEIFS :
         /* vacío */                         { $$ = []; }
        | ELSEIF_LIST                         { $$ = $1; } ;

ELSEIF_LIST: 
          ELSEIF                              { $$ = [$1]; }
        | ELSEIF_LIST ELSEIF                  { $$ = $1.concat([$2]); } ;

ELSEIF: RW_o RW_si EXPRESION RW_entonces INSTRUCCIONES {
        $$ = { condicion: $3, instrucciones: $5 }; } ;

ELSE 
    : RW_deLoContrario INSTRUCCIONES { $$ = $2; }
    | /* vacío */                    { $$ = null; }
    ;
//*CASE
CONDICIONAL_SEGUN : RW_segun EXPRESION RW_hacer CASE_LIST DEFAULT RW_fin RW_segun {
        
        $$ = new Segun(@1.first_line, @1.first_column, $2,$4, $5 ); // Pasamos los datos necesarios al constructor de Si
    } ;

CASE_LIST: 
         CASE                              { $$ = [$1]; }        
        | CASE_LIST CASE                     { $$ = $1.concat([$2]); } ;

CASE : RW_enCasoDeSer EXPRESION RW_entonces INSTRUCCIONES RW_detener {
        $$ = { condicion: $2, instrucciones: $4 , detener: $5}; } ;

// DET : RW_detener { $$ = true; }
//     | /* vacío */  { $$ = false; } ;

DEFAULT:
        RW_deLoContrario RW_entonces INSTRUCCIONES  RW_detener { $$ = $3; } 
        | /* vacío */                            { $$ = null; } ;


// === CICLOS ===
// === PARA ===
CICLO_PARA :
         RW_para TK_id TK_asign EXPRESION RW_hasta EXPRESION RW_con OPCION_INCREMENTO EXPRESION RW_hacer INSTRUCCIONES RW_fin RW_para {$$ = new Para(@1.first_line, @1.first_column, $2, $4, $6, $8, $11)};
   

OPCION_INCREMENTO:
        RW_incremento {$$ = 'incremento'}
        | RW_decremento {$$ = 'decremento'} ;     

// **CICLO MIENTRAS  **
CICLO_WHILE:
        RW_mientras EXPRESION RW_hacer INSTRUCCIONES RW_fin RW_mientras {$$ = new Mientras(@1.first_line, @1.first_column, $2, $4)} ;  

//**CICLO DO WHILE ** 
CICLO_DOWHILE:
         RW_repetir INSTRUCCIONES RW_hasta RW_que EXPRESION  {$$= new Hasta(@1.first_line, @1.first_column, $5, $2 )  };


//*FUNCIONES Y METODOS
// === FUNCIONES/METODOS ===
FUNCIONES_METODOS :
            RW_funcion TK_id TIPO RW_con RW_parametros TK_parA PARAMETROS TK_parC INSTRUCCIONES RW_fin RW_funcion {$$ = new Funcion(@1.first_line, @1.first_column, $2, $3, $7, $9)} 
            |RW_funcion TK_id TIPO INSTRUCCIONES RW_fin RW_funcion                                                 {$$ = new Funcion(@1.first_line, @1.first_column, $2, $3, [], $4)} ;

PROCEDIMIENTO:
        RW_porcedimiento TK_id INSTRUCCIONES RW_fin RW_porcedimiento {$$ = new Procedimiento(@1.first_line, @1.first_column, $2, null, [], $3)} 
        |RW_porcedimiento TK_id  RW_con RW_parametros  TK_parA PARAMETROS TK_parC  INSTRUCCIONES RW_fin RW_porcedimiento {$$ = new Procedimiento(@1.first_line, @1.first_column, $2, null, $6, $8)} ;

PARAMETROS :
            PARAMETROS TK_coma PARAMETRO {$$.push($3)} |
            PARAMETRO                    {$$ = [$1]  } ;

PARAMETRO :
            TK_id TIPO {$$ = new Parametro(@1.first_line, @1.first_column, $1, $2)} ;

LLAMAR_FUNCIONES_METODOS :
            RW_ejecutar TK_id TK_parA ARGUMENTOS TK_parC {$$ = new LlamadaFUncion(@1.first_line, @1.first_column, $2, $4)} 
            | RW_ejecutar TK_id TK_parA TK_parC            {$$ = new LlamadaFUncion(@1.first_line, @1.first_column, $2, [])}  ;

ARGUMENTOS :
            ARGUMENTOS TK_coma EXPRESION {$$.push($3)} |
            EXPRESION                    {$$ = [$1]  } ;


//*EXPRESIONES 

EXPRESION :
         ARITMETICOS                    {$$ = $1} 
        | FUNCIONES_NATIVAS             {$$ = $1}
        | RELACIONALES                  {$$ = $1} 
        | LOGICOS                       {$$ = $1} 
        | INCREMENTO                    {$$ = $1} 
        | INCREMENTO2                   {$$ = $1} 
        | LLAMAR_FUNCIONES_METODOS      {$$ = $1} 
        | TK_id                         {$$ = new AccesoID(@1.first_line, @1.first_column, $1                )} 
        | RW_verdadero                  {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.BOOLEANO)} 
        | RW_falso                      {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.BOOLEANO)} 
        | TK_string                     {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.CADENA  )} 
        | TK_char                       {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.CARACTER)} 
        | TK_double                     {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.DECIMAL )} 
        | TK_integer                    {$$ = new Primitivo(@1.first_line, @1.first_column, $1, Tipo.ENTERO  )} 
        | CASTEOS                       {$$ = $1}
        | TK_parA EXPRESION TK_parC     {$$ = $2} 
        //|LISTAS {$$ = $1}
        ;

CASTEOS:
        TK_parA TIPO TK_parC EXPRESION %prec CAST {$$ = new Casteos(@1.first_line, @1.first_column, $4, $2)}
;

        


FUNCIONES_NATIVAS
  : RW_minuscula TK_parA EXPRESION TK_parC {    $$ = new FuncionesSistema(@1.first_line, @1.first_column, $3, $1); }
  | RW_mayuscula TK_parA EXPRESION TK_parC {    $$ = new FuncionesSistema(@1.first_line, @1.first_column, $3, $1); }
  | RW_longitud TK_parA EXPRESION TK_parC {     $$ = new FuncionesSistema(@1.first_line, @1.first_column, $3, $1); }
  | RW_truncar TK_parA EXPRESION TK_parC {      $$ = new FuncionesSistema(@1.first_line, @1.first_column, $3, $1); }
  | RW_redondear TK_parA EXPRESION TK_parC {    $$ = new FuncionesSistema(@1.first_line, @1.first_column, $3, $1); }
  | RW_tipo TK_parA EXPRESION TK_parC {         $$ = new FuncionesSistema(@1.first_line, @1.first_column, $3, $1); }
  ;

ARITMETICOS : 
        EXPRESION TK_resta EXPRESION {$$ = new Aritmetico(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_suma EXPRESION  {$$ = new Aritmetico(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_mult EXPRESION  {$$ = new Aritmetico(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_div  EXPRESION  {$$ = new Aritmetico(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_mod  EXPRESION  {$$ = new Aritmetico(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_pot  EXPRESION  {$$ = new Aritmetico(@1.first_line, @1.first_column, $1, $2, $3)}
        | TK_resta EXPRESION %prec TK_negacionUnaria {$$ = new Aritmetico(@1.first_line, @1.first_column, undefined, $1, $2)} ;

RELACIONALES : 
        EXPRESION TK_igual  EXPRESION {$$ = new Relacional(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_dif    EXPRESION {$$ = new Relacional(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_mayor  EXPRESION {$$ = new Relacional(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_menor  EXPRESION {$$ = new Relacional(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_mayorI EXPRESION {$$ = new Relacional(@1.first_line, @1.first_column, $1, $2, $3)} 
        | EXPRESION TK_menorI EXPRESION {$$ = new Relacional(@1.first_line, @1.first_column, $1, $2, $3)} ;


LOGICOS :
        EXPRESION TK_and EXPRESION {$$ = new Logico(@1.first_line, @1.first_column, $1, $2, $3)       } 
        | EXPRESION TK_or  EXPRESION {$$ = new Logico(@1.first_line, @1.first_column, $1, $2, $3)       } 
        | TK_not EXPRESION           {$$ = new Logico(@1.first_line, @1.first_column, undefined, $1, $2)} ;

TIPO :
          RW_entero  {$$ = Tipo.ENTERO }
        | RW_decimal {$$ = Tipo.DECIMAL}
        | RW_caracter {$$ = Tipo.CARACTER}
        | RW_booleano {$$ = Tipo.BOOLEANO}
        | RW_cadena {$$ = Tipo.CADENA}
        | RW_Litas {$$ = Tipo.LISTA};

