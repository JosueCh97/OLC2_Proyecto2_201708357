<?php

/*
 * Generated from Golampi.g4 by ANTLR 4.13.1
 */

namespace App\Language {
	use Antlr\Antlr4\Runtime\Atn\ATN;
	use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
	use Antlr\Antlr4\Runtime\Atn\ParserATNSimulator;
	use Antlr\Antlr4\Runtime\Dfa\DFA;
	use Antlr\Antlr4\Runtime\Error\Exceptions\FailedPredicateException;
	use Antlr\Antlr4\Runtime\Error\Exceptions\NoViableAltException;
	use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
	use Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException;
	use Antlr\Antlr4\Runtime\RuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\TokenStream;
	use Antlr\Antlr4\Runtime\Vocabulary;
	use Antlr\Antlr4\Runtime\VocabularyImpl;
	use Antlr\Antlr4\Runtime\RuntimeMetaData;
	use Antlr\Antlr4\Runtime\Parser;

	final class GolampiParser extends Parser
	{
		public const T__0 = 1, T__1 = 2, T__2 = 3, T__3 = 4, T__4 = 5, T__5 = 6, 
               T__6 = 7, T__7 = 8, T__8 = 9, T__9 = 10, T__10 = 11, T__11 = 12, 
               T__12 = 13, T__13 = 14, TKINT = 15, TKFLOAT = 16, TKBOOL = 17, 
               TKRUNE = 18, TKSTRING = 19, IGUAL_IGUAL = 20, DIFERENTE = 21, 
               MENOR_IGUAL = 22, MAYOR_IGUAL = 23, MENOR = 24, MAYOR = 25, 
               TKAND = 26, TKOR = 27, TKVAR = 28, TKCONST = 29, IGUAL = 30, 
               NIL = 31, DPIGUAL = 32, DEC = 33, INC = 34, MASIG = 35, MENOSIG = 36, 
               PORIG = 37, DIVIG = 38, TKFUNC = 39, TKPRINT = 40, TKIF = 41, 
               TKELSE = 42, TKSWITCH = 43, TKCASE = 44, TKDEFAULT = 45, 
               TKFOR = 46, TKBREAK = 47, TKCONTINUE = 48, TKRETURN = 49, 
               PUNTERO = 50, REFERENCIA = 51, BOOL = 52, STRING = 53, UNICODE = 54, 
               IDNAME = 55, INT = 56, FLOAT = 57, COMENT = 58, MULTILINE_COMMENT = 59, 
               WS = 60;

		public const RULE_inicio = 0, RULE_instrucciones = 1, RULE_instruccion = 2, 
               RULE_bloque = 3, RULE_listids = 4, RULE_listaexp = 5, RULE_tipo_var = 6, 
               RULE_asignable = 7, RULE_declaracion = 8, RULE_decl_corta = 9, 
               RULE_asignacion = 10, RULE_asig_compuesta = 11, RULE_inc_dec = 12, 
               RULE_si_stmt = 13, RULE_imprimir = 14, RULE_switch = 15, 
               RULE_case = 16, RULE_default = 17, RULE_init = 18, RULE_post = 19, 
               RULE_for = 20, RULE_break = 21, RULE_continue = 22, RULE_func_dcl = 23, 
               RULE_parametros = 24, RULE_parametro = 25, RULE_tipo_retorno = 26, 
               RULE_return_stmt = 27, RULE_llamada_stmt = 28, RULE_expresion = 29, 
               RULE_lista_valores = 30, RULE_lista_valor = 31, RULE_optipo = 32;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'inicio', 'instrucciones', 'instruccion', 'bloque', 'listids', 'listaexp', 
			'tipo_var', 'asignable', 'declaracion', 'decl_corta', 'asignacion', 'asig_compuesta', 
			'inc_dec', 'si_stmt', 'imprimir', 'switch', 'case', 'default', 'init', 
			'post', 'for', 'break', 'continue', 'func_dcl', 'parametros', 'parametro', 
			'tipo_retorno', 'return_stmt', 'llamada_stmt', 'expresion', 'lista_valores', 
			'lista_valor', 'optipo'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "'{'", "'}'", "','", "'['", "']'", "'('", "')'", "':'", "';'", 
		    "'-'", "'!'", "'/'", "'%'", "'+'", "'int32'", "'float32'", "'bool'", 
		    "'rune'", "'string'", "'=='", "'!='", "'<='", "'>='", "'<'", "'>'", 
		    "'&&'", "'||'", "'var'", "'const'", "'='", "'nil'", "':='", "'--'", 
		    "'++'", "'+='", "'-='", "'*='", "'/='", "'func'", "'fmt.Print'", "'if'", 
		    "'else'", "'switch'", "'case'", "'default'", "'for'", "'break'", "'continue'", 
		    "'return'", "'*'", "'&'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, null, null, null, null, null, null, null, null, null, null, 
		    null, null, null, null, "TKINT", "TKFLOAT", "TKBOOL", "TKRUNE", "TKSTRING", 
		    "IGUAL_IGUAL", "DIFERENTE", "MENOR_IGUAL", "MAYOR_IGUAL", "MENOR", 
		    "MAYOR", "TKAND", "TKOR", "TKVAR", "TKCONST", "IGUAL", "NIL", "DPIGUAL", 
		    "DEC", "INC", "MASIG", "MENOSIG", "PORIG", "DIVIG", "TKFUNC", "TKPRINT", 
		    "TKIF", "TKELSE", "TKSWITCH", "TKCASE", "TKDEFAULT", "TKFOR", "TKBREAK", 
		    "TKCONTINUE", "TKRETURN", "PUNTERO", "REFERENCIA", "BOOL", "STRING", 
		    "UNICODE", "IDNAME", "INT", "FLOAT", "COMENT", "MULTILINE_COMMENT", 
		    "WS"
		];

		private const SERIALIZED_ATN =
			[4, 1, 60, 411, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 2, 4, 
		    7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 2, 9, 7, 9, 
		    2, 10, 7, 10, 2, 11, 7, 11, 2, 12, 7, 12, 2, 13, 7, 13, 2, 14, 7, 
		    14, 2, 15, 7, 15, 2, 16, 7, 16, 2, 17, 7, 17, 2, 18, 7, 18, 2, 19, 
		    7, 19, 2, 20, 7, 20, 2, 21, 7, 21, 2, 22, 7, 22, 2, 23, 7, 23, 2, 
		    24, 7, 24, 2, 25, 7, 25, 2, 26, 7, 26, 2, 27, 7, 27, 2, 28, 7, 28, 
		    2, 29, 7, 29, 2, 30, 7, 30, 2, 31, 7, 31, 2, 32, 7, 32, 1, 0, 1, 0, 
		    1, 0, 1, 1, 4, 1, 71, 8, 1, 11, 1, 12, 1, 72, 1, 2, 1, 2, 1, 2, 1, 
		    2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 3, 
		    2, 89, 8, 2, 1, 3, 1, 3, 5, 3, 93, 8, 3, 10, 3, 12, 3, 96, 9, 3, 1, 
		    3, 1, 3, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 5, 4, 106, 8, 4, 10, 
		    4, 12, 4, 109, 9, 4, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 5, 5, 117, 
		    8, 5, 10, 5, 12, 5, 120, 9, 5, 1, 6, 3, 6, 123, 8, 6, 1, 6, 1, 6, 
		    1, 6, 1, 6, 5, 6, 129, 8, 6, 10, 6, 12, 6, 132, 9, 6, 1, 6, 1, 6, 
		    1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 5, 7, 141, 8, 7, 10, 7, 12, 7, 144, 
		    9, 7, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 
		    1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 3, 8, 162, 8, 8, 1, 9, 1, 9, 1, 
		    9, 5, 9, 167, 8, 9, 10, 9, 12, 9, 170, 9, 9, 1, 9, 1, 9, 1, 9, 1, 
		    10, 1, 10, 1, 10, 5, 10, 178, 8, 10, 10, 10, 12, 10, 181, 9, 10, 1, 
		    10, 1, 10, 1, 10, 1, 11, 1, 11, 1, 11, 1, 11, 1, 12, 1, 12, 1, 12, 
		    1, 13, 1, 13, 1, 13, 1, 13, 1, 13, 1, 13, 3, 13, 199, 8, 13, 3, 13, 
		    201, 8, 13, 1, 14, 1, 14, 1, 14, 1, 14, 1, 14, 1, 15, 1, 15, 1, 15, 
		    1, 15, 5, 15, 212, 8, 15, 10, 15, 12, 15, 215, 9, 15, 1, 15, 3, 15, 
		    218, 8, 15, 1, 15, 1, 15, 1, 16, 1, 16, 1, 16, 1, 16, 5, 16, 226, 
		    8, 16, 10, 16, 12, 16, 229, 9, 16, 1, 17, 1, 17, 1, 17, 5, 17, 234, 
		    8, 17, 10, 17, 12, 17, 237, 9, 17, 1, 18, 1, 18, 1, 18, 1, 18, 1, 
		    18, 3, 18, 244, 8, 18, 1, 19, 1, 19, 1, 19, 3, 19, 249, 8, 19, 1, 
		    20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 
		    1, 20, 1, 20, 1, 20, 1, 20, 3, 20, 265, 8, 20, 1, 21, 1, 21, 1, 22, 
		    1, 22, 1, 23, 1, 23, 1, 23, 1, 23, 3, 23, 275, 8, 23, 1, 23, 1, 23, 
		    3, 23, 279, 8, 23, 1, 23, 1, 23, 1, 24, 1, 24, 1, 24, 5, 24, 286, 
		    8, 24, 10, 24, 12, 24, 289, 9, 24, 1, 25, 1, 25, 1, 25, 1, 26, 1, 
		    26, 1, 26, 1, 26, 1, 26, 5, 26, 299, 8, 26, 10, 26, 12, 26, 302, 9, 
		    26, 1, 26, 1, 26, 3, 26, 306, 8, 26, 1, 27, 1, 27, 3, 27, 310, 8, 
		    27, 1, 28, 1, 28, 1, 28, 3, 28, 315, 8, 28, 1, 28, 1, 28, 1, 29, 1, 
		    29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 
		    1, 29, 3, 29, 331, 8, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 3, 29, 
		    338, 8, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 
		    1, 29, 1, 29, 1, 29, 1, 29, 3, 29, 352, 8, 29, 1, 29, 1, 29, 1, 29, 
		    1, 29, 1, 29, 1, 29, 1, 29, 3, 29, 361, 8, 29, 1, 29, 1, 29, 1, 29, 
		    1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 
		    29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 1, 29, 
		    1, 29, 1, 29, 1, 29, 5, 29, 388, 8, 29, 10, 29, 12, 29, 391, 9, 29, 
		    1, 30, 1, 30, 1, 30, 5, 30, 396, 8, 30, 10, 30, 12, 30, 399, 9, 30, 
		    1, 31, 1, 31, 1, 31, 3, 31, 404, 8, 31, 1, 31, 3, 31, 407, 8, 31, 
		    1, 32, 1, 32, 1, 32, 0, 3, 8, 10, 58, 33, 0, 2, 4, 6, 8, 10, 12, 14, 
		    16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 
		    50, 52, 54, 56, 58, 60, 62, 64, 0, 6, 1, 0, 35, 38, 1, 0, 33, 34, 
		    2, 0, 12, 13, 50, 50, 2, 0, 10, 10, 14, 14, 1, 0, 20, 25, 1, 0, 15, 
		    19, 448, 0, 66, 1, 0, 0, 0, 2, 70, 1, 0, 0, 0, 4, 88, 1, 0, 0, 0, 
		    6, 90, 1, 0, 0, 0, 8, 99, 1, 0, 0, 0, 10, 110, 1, 0, 0, 0, 12, 122, 
		    1, 0, 0, 0, 14, 135, 1, 0, 0, 0, 16, 161, 1, 0, 0, 0, 18, 163, 1, 
		    0, 0, 0, 20, 174, 1, 0, 0, 0, 22, 185, 1, 0, 0, 0, 24, 189, 1, 0, 
		    0, 0, 26, 192, 1, 0, 0, 0, 28, 202, 1, 0, 0, 0, 30, 207, 1, 0, 0, 
		    0, 32, 221, 1, 0, 0, 0, 34, 230, 1, 0, 0, 0, 36, 243, 1, 0, 0, 0, 
		    38, 248, 1, 0, 0, 0, 40, 264, 1, 0, 0, 0, 42, 266, 1, 0, 0, 0, 44, 
		    268, 1, 0, 0, 0, 46, 270, 1, 0, 0, 0, 48, 282, 1, 0, 0, 0, 50, 290, 
		    1, 0, 0, 0, 52, 305, 1, 0, 0, 0, 54, 307, 1, 0, 0, 0, 56, 311, 1, 
		    0, 0, 0, 58, 360, 1, 0, 0, 0, 60, 392, 1, 0, 0, 0, 62, 406, 1, 0, 
		    0, 0, 64, 408, 1, 0, 0, 0, 66, 67, 3, 2, 1, 0, 67, 68, 5, 0, 0, 1, 
		    68, 1, 1, 0, 0, 0, 69, 71, 3, 4, 2, 0, 70, 69, 1, 0, 0, 0, 71, 72, 
		    1, 0, 0, 0, 72, 70, 1, 0, 0, 0, 72, 73, 1, 0, 0, 0, 73, 3, 1, 0, 0, 
		    0, 74, 89, 3, 16, 8, 0, 75, 89, 3, 18, 9, 0, 76, 89, 3, 20, 10, 0, 
		    77, 89, 3, 22, 11, 0, 78, 89, 3, 24, 12, 0, 79, 89, 3, 26, 13, 0, 
		    80, 89, 3, 30, 15, 0, 81, 89, 3, 28, 14, 0, 82, 89, 3, 40, 20, 0, 
		    83, 89, 3, 42, 21, 0, 84, 89, 3, 44, 22, 0, 85, 89, 3, 46, 23, 0, 
		    86, 89, 3, 54, 27, 0, 87, 89, 3, 56, 28, 0, 88, 74, 1, 0, 0, 0, 88, 
		    75, 1, 0, 0, 0, 88, 76, 1, 0, 0, 0, 88, 77, 1, 0, 0, 0, 88, 78, 1, 
		    0, 0, 0, 88, 79, 1, 0, 0, 0, 88, 80, 1, 0, 0, 0, 88, 81, 1, 0, 0, 
		    0, 88, 82, 1, 0, 0, 0, 88, 83, 1, 0, 0, 0, 88, 84, 1, 0, 0, 0, 88, 
		    85, 1, 0, 0, 0, 88, 86, 1, 0, 0, 0, 88, 87, 1, 0, 0, 0, 89, 5, 1, 
		    0, 0, 0, 90, 94, 5, 1, 0, 0, 91, 93, 3, 4, 2, 0, 92, 91, 1, 0, 0, 
		    0, 93, 96, 1, 0, 0, 0, 94, 92, 1, 0, 0, 0, 94, 95, 1, 0, 0, 0, 95, 
		    97, 1, 0, 0, 0, 96, 94, 1, 0, 0, 0, 97, 98, 5, 2, 0, 0, 98, 7, 1, 
		    0, 0, 0, 99, 100, 6, 4, -1, 0, 100, 101, 5, 55, 0, 0, 101, 107, 1, 
		    0, 0, 0, 102, 103, 10, 1, 0, 0, 103, 104, 5, 3, 0, 0, 104, 106, 5, 
		    55, 0, 0, 105, 102, 1, 0, 0, 0, 106, 109, 1, 0, 0, 0, 107, 105, 1, 
		    0, 0, 0, 107, 108, 1, 0, 0, 0, 108, 9, 1, 0, 0, 0, 109, 107, 1, 0, 
		    0, 0, 110, 111, 6, 5, -1, 0, 111, 112, 3, 58, 29, 0, 112, 118, 1, 
		    0, 0, 0, 113, 114, 10, 1, 0, 0, 114, 115, 5, 3, 0, 0, 115, 117, 3, 
		    58, 29, 0, 116, 113, 1, 0, 0, 0, 117, 120, 1, 0, 0, 0, 118, 116, 1, 
		    0, 0, 0, 118, 119, 1, 0, 0, 0, 119, 11, 1, 0, 0, 0, 120, 118, 1, 0, 
		    0, 0, 121, 123, 5, 50, 0, 0, 122, 121, 1, 0, 0, 0, 122, 123, 1, 0, 
		    0, 0, 123, 130, 1, 0, 0, 0, 124, 125, 5, 4, 0, 0, 125, 126, 3, 58, 
		    29, 0, 126, 127, 5, 5, 0, 0, 127, 129, 1, 0, 0, 0, 128, 124, 1, 0, 
		    0, 0, 129, 132, 1, 0, 0, 0, 130, 128, 1, 0, 0, 0, 130, 131, 1, 0, 
		    0, 0, 131, 133, 1, 0, 0, 0, 132, 130, 1, 0, 0, 0, 133, 134, 3, 64, 
		    32, 0, 134, 13, 1, 0, 0, 0, 135, 142, 5, 55, 0, 0, 136, 137, 5, 4, 
		    0, 0, 137, 138, 3, 58, 29, 0, 138, 139, 5, 5, 0, 0, 139, 141, 1, 0, 
		    0, 0, 140, 136, 1, 0, 0, 0, 141, 144, 1, 0, 0, 0, 142, 140, 1, 0, 
		    0, 0, 142, 143, 1, 0, 0, 0, 143, 15, 1, 0, 0, 0, 144, 142, 1, 0, 0, 
		    0, 145, 146, 5, 28, 0, 0, 146, 147, 3, 8, 4, 0, 147, 148, 3, 12, 6, 
		    0, 148, 162, 1, 0, 0, 0, 149, 150, 5, 28, 0, 0, 150, 151, 3, 8, 4, 
		    0, 151, 152, 3, 12, 6, 0, 152, 153, 5, 30, 0, 0, 153, 154, 3, 10, 
		    5, 0, 154, 162, 1, 0, 0, 0, 155, 156, 5, 29, 0, 0, 156, 157, 3, 8, 
		    4, 0, 157, 158, 3, 12, 6, 0, 158, 159, 5, 30, 0, 0, 159, 160, 3, 10, 
		    5, 0, 160, 162, 1, 0, 0, 0, 161, 145, 1, 0, 0, 0, 161, 149, 1, 0, 
		    0, 0, 161, 155, 1, 0, 0, 0, 162, 17, 1, 0, 0, 0, 163, 168, 5, 55, 
		    0, 0, 164, 165, 5, 3, 0, 0, 165, 167, 5, 55, 0, 0, 166, 164, 1, 0, 
		    0, 0, 167, 170, 1, 0, 0, 0, 168, 166, 1, 0, 0, 0, 168, 169, 1, 0, 
		    0, 0, 169, 171, 1, 0, 0, 0, 170, 168, 1, 0, 0, 0, 171, 172, 5, 32, 
		    0, 0, 172, 173, 3, 10, 5, 0, 173, 19, 1, 0, 0, 0, 174, 179, 3, 14, 
		    7, 0, 175, 176, 5, 3, 0, 0, 176, 178, 3, 14, 7, 0, 177, 175, 1, 0, 
		    0, 0, 178, 181, 1, 0, 0, 0, 179, 177, 1, 0, 0, 0, 179, 180, 1, 0, 
		    0, 0, 180, 182, 1, 0, 0, 0, 181, 179, 1, 0, 0, 0, 182, 183, 5, 30, 
		    0, 0, 183, 184, 3, 10, 5, 0, 184, 21, 1, 0, 0, 0, 185, 186, 3, 14, 
		    7, 0, 186, 187, 7, 0, 0, 0, 187, 188, 3, 58, 29, 0, 188, 23, 1, 0, 
		    0, 0, 189, 190, 3, 14, 7, 0, 190, 191, 7, 1, 0, 0, 191, 25, 1, 0, 
		    0, 0, 192, 193, 5, 41, 0, 0, 193, 194, 3, 58, 29, 0, 194, 200, 3, 
		    6, 3, 0, 195, 198, 5, 42, 0, 0, 196, 199, 3, 6, 3, 0, 197, 199, 3, 
		    26, 13, 0, 198, 196, 1, 0, 0, 0, 198, 197, 1, 0, 0, 0, 199, 201, 1, 
		    0, 0, 0, 200, 195, 1, 0, 0, 0, 200, 201, 1, 0, 0, 0, 201, 27, 1, 0, 
		    0, 0, 202, 203, 5, 40, 0, 0, 203, 204, 5, 6, 0, 0, 204, 205, 3, 10, 
		    5, 0, 205, 206, 5, 7, 0, 0, 206, 29, 1, 0, 0, 0, 207, 208, 5, 43, 
		    0, 0, 208, 209, 3, 58, 29, 0, 209, 213, 5, 1, 0, 0, 210, 212, 3, 32, 
		    16, 0, 211, 210, 1, 0, 0, 0, 212, 215, 1, 0, 0, 0, 213, 211, 1, 0, 
		    0, 0, 213, 214, 1, 0, 0, 0, 214, 217, 1, 0, 0, 0, 215, 213, 1, 0, 
		    0, 0, 216, 218, 3, 34, 17, 0, 217, 216, 1, 0, 0, 0, 217, 218, 1, 0, 
		    0, 0, 218, 219, 1, 0, 0, 0, 219, 220, 5, 2, 0, 0, 220, 31, 1, 0, 0, 
		    0, 221, 222, 5, 44, 0, 0, 222, 223, 3, 10, 5, 0, 223, 227, 5, 8, 0, 
		    0, 224, 226, 3, 4, 2, 0, 225, 224, 1, 0, 0, 0, 226, 229, 1, 0, 0, 
		    0, 227, 225, 1, 0, 0, 0, 227, 228, 1, 0, 0, 0, 228, 33, 1, 0, 0, 0, 
		    229, 227, 1, 0, 0, 0, 230, 231, 5, 45, 0, 0, 231, 235, 5, 8, 0, 0, 
		    232, 234, 3, 4, 2, 0, 233, 232, 1, 0, 0, 0, 234, 237, 1, 0, 0, 0, 
		    235, 233, 1, 0, 0, 0, 235, 236, 1, 0, 0, 0, 236, 35, 1, 0, 0, 0, 237, 
		    235, 1, 0, 0, 0, 238, 244, 3, 16, 8, 0, 239, 244, 3, 18, 9, 0, 240, 
		    244, 3, 20, 10, 0, 241, 244, 3, 22, 11, 0, 242, 244, 3, 24, 12, 0, 
		    243, 238, 1, 0, 0, 0, 243, 239, 1, 0, 0, 0, 243, 240, 1, 0, 0, 0, 
		    243, 241, 1, 0, 0, 0, 243, 242, 1, 0, 0, 0, 244, 37, 1, 0, 0, 0, 245, 
		    249, 3, 20, 10, 0, 246, 249, 3, 22, 11, 0, 247, 249, 3, 24, 12, 0, 
		    248, 245, 1, 0, 0, 0, 248, 246, 1, 0, 0, 0, 248, 247, 1, 0, 0, 0, 
		    249, 39, 1, 0, 0, 0, 250, 251, 5, 46, 0, 0, 251, 265, 3, 6, 3, 0, 
		    252, 253, 5, 46, 0, 0, 253, 254, 3, 58, 29, 0, 254, 255, 3, 6, 3, 
		    0, 255, 265, 1, 0, 0, 0, 256, 257, 5, 46, 0, 0, 257, 258, 3, 36, 18, 
		    0, 258, 259, 5, 9, 0, 0, 259, 260, 3, 58, 29, 0, 260, 261, 5, 9, 0, 
		    0, 261, 262, 3, 38, 19, 0, 262, 263, 3, 6, 3, 0, 263, 265, 1, 0, 0, 
		    0, 264, 250, 1, 0, 0, 0, 264, 252, 1, 0, 0, 0, 264, 256, 1, 0, 0, 
		    0, 265, 41, 1, 0, 0, 0, 266, 267, 5, 47, 0, 0, 267, 43, 1, 0, 0, 0, 
		    268, 269, 5, 48, 0, 0, 269, 45, 1, 0, 0, 0, 270, 271, 5, 39, 0, 0, 
		    271, 272, 5, 55, 0, 0, 272, 274, 5, 6, 0, 0, 273, 275, 3, 48, 24, 
		    0, 274, 273, 1, 0, 0, 0, 274, 275, 1, 0, 0, 0, 275, 276, 1, 0, 0, 
		    0, 276, 278, 5, 7, 0, 0, 277, 279, 3, 52, 26, 0, 278, 277, 1, 0, 0, 
		    0, 278, 279, 1, 0, 0, 0, 279, 280, 1, 0, 0, 0, 280, 281, 3, 6, 3, 
		    0, 281, 47, 1, 0, 0, 0, 282, 287, 3, 50, 25, 0, 283, 284, 5, 3, 0, 
		    0, 284, 286, 3, 50, 25, 0, 285, 283, 1, 0, 0, 0, 286, 289, 1, 0, 0, 
		    0, 287, 285, 1, 0, 0, 0, 287, 288, 1, 0, 0, 0, 288, 49, 1, 0, 0, 0, 
		    289, 287, 1, 0, 0, 0, 290, 291, 5, 55, 0, 0, 291, 292, 3, 12, 6, 0, 
		    292, 51, 1, 0, 0, 0, 293, 306, 3, 12, 6, 0, 294, 295, 5, 6, 0, 0, 
		    295, 300, 3, 12, 6, 0, 296, 297, 5, 3, 0, 0, 297, 299, 3, 12, 6, 0, 
		    298, 296, 1, 0, 0, 0, 299, 302, 1, 0, 0, 0, 300, 298, 1, 0, 0, 0, 
		    300, 301, 1, 0, 0, 0, 301, 303, 1, 0, 0, 0, 302, 300, 1, 0, 0, 0, 
		    303, 304, 5, 7, 0, 0, 304, 306, 1, 0, 0, 0, 305, 293, 1, 0, 0, 0, 
		    305, 294, 1, 0, 0, 0, 306, 53, 1, 0, 0, 0, 307, 309, 5, 49, 0, 0, 
		    308, 310, 3, 10, 5, 0, 309, 308, 1, 0, 0, 0, 309, 310, 1, 0, 0, 0, 
		    310, 55, 1, 0, 0, 0, 311, 312, 5, 55, 0, 0, 312, 314, 5, 6, 0, 0, 
		    313, 315, 3, 10, 5, 0, 314, 313, 1, 0, 0, 0, 314, 315, 1, 0, 0, 0, 
		    315, 316, 1, 0, 0, 0, 316, 317, 5, 7, 0, 0, 317, 57, 1, 0, 0, 0, 318, 
		    319, 6, 29, -1, 0, 319, 320, 5, 10, 0, 0, 320, 361, 3, 58, 29, 21, 
		    321, 322, 5, 11, 0, 0, 322, 361, 3, 58, 29, 20, 323, 324, 5, 6, 0, 
		    0, 324, 325, 3, 58, 29, 0, 325, 326, 5, 7, 0, 0, 326, 361, 1, 0, 0, 
		    0, 327, 328, 3, 12, 6, 0, 328, 330, 5, 1, 0, 0, 329, 331, 3, 60, 30, 
		    0, 330, 329, 1, 0, 0, 0, 330, 331, 1, 0, 0, 0, 331, 332, 1, 0, 0, 
		    0, 332, 333, 5, 2, 0, 0, 333, 361, 1, 0, 0, 0, 334, 335, 5, 55, 0, 
		    0, 335, 337, 5, 6, 0, 0, 336, 338, 3, 10, 5, 0, 337, 336, 1, 0, 0, 
		    0, 337, 338, 1, 0, 0, 0, 338, 339, 1, 0, 0, 0, 339, 361, 5, 7, 0, 
		    0, 340, 341, 5, 51, 0, 0, 341, 361, 3, 14, 7, 0, 342, 343, 5, 50, 
		    0, 0, 343, 361, 3, 14, 7, 0, 344, 345, 5, 6, 0, 0, 345, 346, 3, 58, 
		    29, 0, 346, 347, 5, 7, 0, 0, 347, 361, 1, 0, 0, 0, 348, 349, 3, 12, 
		    6, 0, 349, 351, 5, 1, 0, 0, 350, 352, 3, 60, 30, 0, 351, 350, 1, 0, 
		    0, 0, 351, 352, 1, 0, 0, 0, 352, 353, 1, 0, 0, 0, 353, 354, 5, 2, 
		    0, 0, 354, 361, 1, 0, 0, 0, 355, 361, 5, 56, 0, 0, 356, 361, 5, 57, 
		    0, 0, 357, 361, 5, 52, 0, 0, 358, 361, 5, 53, 0, 0, 359, 361, 5, 55, 
		    0, 0, 360, 318, 1, 0, 0, 0, 360, 321, 1, 0, 0, 0, 360, 323, 1, 0, 
		    0, 0, 360, 327, 1, 0, 0, 0, 360, 334, 1, 0, 0, 0, 360, 340, 1, 0, 
		    0, 0, 360, 342, 1, 0, 0, 0, 360, 344, 1, 0, 0, 0, 360, 348, 1, 0, 
		    0, 0, 360, 355, 1, 0, 0, 0, 360, 356, 1, 0, 0, 0, 360, 357, 1, 0, 
		    0, 0, 360, 358, 1, 0, 0, 0, 360, 359, 1, 0, 0, 0, 361, 389, 1, 0, 
		    0, 0, 362, 363, 10, 16, 0, 0, 363, 364, 7, 2, 0, 0, 364, 388, 3, 58, 
		    29, 17, 365, 366, 10, 15, 0, 0, 366, 367, 7, 3, 0, 0, 367, 388, 3, 
		    58, 29, 16, 368, 369, 10, 14, 0, 0, 369, 370, 7, 4, 0, 0, 370, 388, 
		    3, 58, 29, 15, 371, 372, 10, 13, 0, 0, 372, 373, 5, 26, 0, 0, 373, 
		    388, 3, 58, 29, 14, 374, 375, 10, 12, 0, 0, 375, 376, 5, 27, 0, 0, 
		    376, 388, 3, 58, 29, 13, 377, 378, 10, 18, 0, 0, 378, 379, 5, 4, 0, 
		    0, 379, 380, 3, 58, 29, 0, 380, 381, 5, 5, 0, 0, 381, 388, 1, 0, 0, 
		    0, 382, 383, 10, 7, 0, 0, 383, 384, 5, 4, 0, 0, 384, 385, 3, 58, 29, 
		    0, 385, 386, 5, 5, 0, 0, 386, 388, 1, 0, 0, 0, 387, 362, 1, 0, 0, 
		    0, 387, 365, 1, 0, 0, 0, 387, 368, 1, 0, 0, 0, 387, 371, 1, 0, 0, 
		    0, 387, 374, 1, 0, 0, 0, 387, 377, 1, 0, 0, 0, 387, 382, 1, 0, 0, 
		    0, 388, 391, 1, 0, 0, 0, 389, 387, 1, 0, 0, 0, 389, 390, 1, 0, 0, 
		    0, 390, 59, 1, 0, 0, 0, 391, 389, 1, 0, 0, 0, 392, 397, 3, 62, 31, 
		    0, 393, 394, 5, 3, 0, 0, 394, 396, 3, 62, 31, 0, 395, 393, 1, 0, 0, 
		    0, 396, 399, 1, 0, 0, 0, 397, 395, 1, 0, 0, 0, 397, 398, 1, 0, 0, 
		    0, 398, 61, 1, 0, 0, 0, 399, 397, 1, 0, 0, 0, 400, 407, 3, 58, 29, 
		    0, 401, 403, 5, 1, 0, 0, 402, 404, 3, 60, 30, 0, 403, 402, 1, 0, 0, 
		    0, 403, 404, 1, 0, 0, 0, 404, 405, 1, 0, 0, 0, 405, 407, 5, 2, 0, 
		    0, 406, 400, 1, 0, 0, 0, 406, 401, 1, 0, 0, 0, 407, 63, 1, 0, 0, 0, 
		    408, 409, 7, 5, 0, 0, 409, 65, 1, 0, 0, 0, 36, 72, 88, 94, 107, 118, 
		    122, 130, 142, 161, 168, 179, 198, 200, 213, 217, 227, 235, 243, 248, 
		    264, 274, 278, 287, 300, 305, 309, 314, 330, 337, 351, 360, 387, 389, 
		    397, 403, 406];
		protected static $atn;
		protected static $decisionToDFA;
		protected static $sharedContextCache;

		public function __construct(TokenStream $input)
		{
			parent::__construct($input);

			self::initialize();

			$this->interp = new ParserATNSimulator($this, self::$atn, self::$decisionToDFA, self::$sharedContextCache);
		}

		private static function initialize(): void
		{
			if (self::$atn !== null) {
				return;
			}

			RuntimeMetaData::checkVersion('4.13.1', RuntimeMetaData::VERSION);

			$atn = (new ATNDeserializer())->deserialize(self::SERIALIZED_ATN);

			$decisionToDFA = [];
			for ($i = 0, $count = $atn->getNumberOfDecisions(); $i < $count; $i++) {
				$decisionToDFA[] = new DFA($atn->getDecisionState($i), $i);
			}

			self::$atn = $atn;
			self::$decisionToDFA = $decisionToDFA;
			self::$sharedContextCache = new PredictionContextCache();
		}

		public function getGrammarFileName(): string
		{
			return "Golampi.g4";
		}

		public function getRuleNames(): array
		{
			return self::RULE_NAMES;
		}

		public function getSerializedATN(): array
		{
			return self::SERIALIZED_ATN;
		}

		public function getATN(): ATN
		{
			return self::$atn;
		}

		public function getVocabulary(): Vocabulary
        {
            static $vocabulary;

			return $vocabulary = $vocabulary ?? new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
        }

		/**
		 * @throws RecognitionException
		 */
		public function inicio(): Context\InicioContext
		{
		    $localContext = new Context\InicioContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 0, self::RULE_inicio);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(66);
		        $this->instrucciones();
		        $this->setState(67);
		        $this->match(self::EOF);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function instrucciones(): Context\InstruccionesContext
		{
		    $localContext = new Context\InstruccionesContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 2, self::RULE_instrucciones);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(70); 
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        do {
		        	$this->setState(69);
		        	$this->instruccion();
		        	$this->setState(72); 
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        } while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 37096973370654720) !== 0));
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function instruccion(): Context\InstruccionContext
		{
		    $localContext = new Context\InstruccionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 4, self::RULE_instruccion);

		    try {
		        $this->setState(88);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 1, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(74);
		        	    $this->declaracion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(75);
		        	    $this->decl_corta();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(76);
		        	    $this->asignacion();
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(77);
		        	    $this->asig_compuesta();
		        	break;

		        	case 5:
		        	    $this->enterOuterAlt($localContext, 5);
		        	    $this->setState(78);
		        	    $this->inc_dec();
		        	break;

		        	case 6:
		        	    $this->enterOuterAlt($localContext, 6);
		        	    $this->setState(79);
		        	    $this->si_stmt();
		        	break;

		        	case 7:
		        	    $this->enterOuterAlt($localContext, 7);
		        	    $this->setState(80);
		        	    $this->switch();
		        	break;

		        	case 8:
		        	    $this->enterOuterAlt($localContext, 8);
		        	    $this->setState(81);
		        	    $this->imprimir();
		        	break;

		        	case 9:
		        	    $this->enterOuterAlt($localContext, 9);
		        	    $this->setState(82);
		        	    $this->for();
		        	break;

		        	case 10:
		        	    $this->enterOuterAlt($localContext, 10);
		        	    $this->setState(83);
		        	    $this->break();
		        	break;

		        	case 11:
		        	    $this->enterOuterAlt($localContext, 11);
		        	    $this->setState(84);
		        	    $this->continue();
		        	break;

		        	case 12:
		        	    $this->enterOuterAlt($localContext, 12);
		        	    $this->setState(85);
		        	    $this->func_dcl();
		        	break;

		        	case 13:
		        	    $this->enterOuterAlt($localContext, 13);
		        	    $this->setState(86);
		        	    $this->return_stmt();
		        	break;

		        	case 14:
		        	    $this->enterOuterAlt($localContext, 14);
		        	    $this->setState(87);
		        	    $this->llamada_stmt();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function bloque(): Context\BloqueContext
		{
		    $localContext = new Context\BloqueContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 6, self::RULE_bloque);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(90);
		        $this->match(self::T__0);
		        $this->setState(94);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 37096973370654720) !== 0)) {
		        	$this->setState(91);
		        	$this->instruccion();
		        	$this->setState(96);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(97);
		        $this->match(self::T__1);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function listids(): Context\ListidsContext
		{
			return $this->recursiveListids(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveListids(int $precedence): Context\ListidsContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\ListidsContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 8;
			$this->enterRecursionRule($localContext, 8, self::RULE_listids, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(100);
				$this->match(self::IDNAME);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(107);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 3, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\ListidsContext($parentContext, $parentState);
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_listids);
						$this->setState(102);

						if (!($this->precpred($this->ctx, 1))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
						}
						$this->setState(103);
						$this->match(self::T__2);
						$this->setState(104);
						$this->match(self::IDNAME); 
					}

					$this->setState(109);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 3, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function listaexp(): Context\ListaexpContext
		{
			return $this->recursiveListaexp(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveListaexp(int $precedence): Context\ListaexpContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\ListaexpContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 10;
			$this->enterRecursionRule($localContext, 10, self::RULE_listaexp, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(111);
				$this->recursiveExpresion(0);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(118);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 4, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\ListaexpContext($parentContext, $parentState);
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_listaexp);
						$this->setState(113);

						if (!($this->precpred($this->ctx, 1))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
						}
						$this->setState(114);
						$this->match(self::T__2);
						$this->setState(115);
						$this->recursiveExpresion(0); 
					}

					$this->setState(120);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 4, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function tipo_var(): Context\Tipo_varContext
		{
		    $localContext = new Context\Tipo_varContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 12, self::RULE_tipo_var);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(122);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::PUNTERO) {
		        	$this->setState(121);
		        	$this->match(self::PUNTERO);
		        }
		        $this->setState(130);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::T__3) {
		        	$this->setState(124);
		        	$this->match(self::T__3);
		        	$this->setState(125);
		        	$this->recursiveExpresion(0);
		        	$this->setState(126);
		        	$this->match(self::T__4);
		        	$this->setState(132);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(133);
		        $this->optipo();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function asignable(): Context\AsignableContext
		{
		    $localContext = new Context\AsignableContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 14, self::RULE_asignable);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(135);
		        $this->match(self::IDNAME);
		        $this->setState(142);
		        $this->errorHandler->sync($this);

		        $alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);

		        while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
		        	if ($alt === 1) {
		        		$this->setState(136);
		        		$this->match(self::T__3);
		        		$this->setState(137);
		        		$this->recursiveExpresion(0);
		        		$this->setState(138);
		        		$this->match(self::T__4); 
		        	}

		        	$this->setState(144);
		        	$this->errorHandler->sync($this);

		        	$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function declaracion(): Context\DeclaracionContext
		{
		    $localContext = new Context\DeclaracionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 16, self::RULE_declaracion);

		    try {
		        $this->setState(161);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 8, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(145);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(146);
		        	    $this->recursiveListids(0);
		        	    $this->setState(147);
		        	    $this->tipo_var();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(149);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(150);
		        	    $this->recursiveListids(0);
		        	    $this->setState(151);
		        	    $this->tipo_var();
		        	    $this->setState(152);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(153);
		        	    $this->recursiveListaexp(0);
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(155);
		        	    $this->match(self::TKCONST);
		        	    $this->setState(156);
		        	    $this->recursiveListids(0);
		        	    $this->setState(157);
		        	    $this->tipo_var();
		        	    $this->setState(158);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(159);
		        	    $this->recursiveListaexp(0);
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function decl_corta(): Context\Decl_cortaContext
		{
		    $localContext = new Context\Decl_cortaContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 18, self::RULE_decl_corta);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(163);
		        $this->match(self::IDNAME);
		        $this->setState(168);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::T__2) {
		        	$this->setState(164);
		        	$this->match(self::T__2);
		        	$this->setState(165);
		        	$this->match(self::IDNAME);
		        	$this->setState(170);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(171);
		        $this->match(self::DPIGUAL);
		        $this->setState(172);
		        $this->recursiveListaexp(0);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function asignacion(): Context\AsignacionContext
		{
		    $localContext = new Context\AsignacionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 20, self::RULE_asignacion);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(174);
		        $this->asignable();
		        $this->setState(179);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::T__2) {
		        	$this->setState(175);
		        	$this->match(self::T__2);
		        	$this->setState(176);
		        	$this->asignable();
		        	$this->setState(181);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(182);
		        $this->match(self::IGUAL);
		        $this->setState(183);
		        $this->recursiveListaexp(0);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function asig_compuesta(): Context\Asig_compuestaContext
		{
		    $localContext = new Context\Asig_compuestaContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 22, self::RULE_asig_compuesta);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(185);
		        $this->asignable();
		        $this->setState(186);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 515396075520) !== 0))) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		        $this->setState(187);
		        $this->recursiveExpresion(0);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function inc_dec(): Context\Inc_decContext
		{
		    $localContext = new Context\Inc_decContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 24, self::RULE_inc_dec);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(189);
		        $this->asignable();
		        $this->setState(190);

		        $_la = $this->input->LA(1);

		        if (!($_la === self::DEC || $_la === self::INC)) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function si_stmt(): Context\Si_stmtContext
		{
		    $localContext = new Context\Si_stmtContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 26, self::RULE_si_stmt);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(192);
		        $this->match(self::TKIF);
		        $this->setState(193);
		        $this->recursiveExpresion(0);
		        $this->setState(194);
		        $this->bloque();
		        $this->setState(200);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::TKELSE) {
		        	$this->setState(195);
		        	$this->match(self::TKELSE);
		        	$this->setState(198);
		        	$this->errorHandler->sync($this);

		        	switch ($this->input->LA(1)) {
		        	    case self::T__0:
		        	    	$this->setState(196);
		        	    	$this->bloque();
		        	    	break;

		        	    case self::TKIF:
		        	    	$this->setState(197);
		        	    	$this->si_stmt();
		        	    	break;

		        	default:
		        		throw new NoViableAltException($this);
		        	}
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function imprimir(): Context\ImprimirContext
		{
		    $localContext = new Context\ImprimirContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 28, self::RULE_imprimir);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(202);
		        $this->match(self::TKPRINT);
		        $this->setState(203);
		        $this->match(self::T__5);
		        $this->setState(204);
		        $this->recursiveListaexp(0);
		        $this->setState(205);
		        $this->match(self::T__6);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function switch(): Context\SwitchContext
		{
		    $localContext = new Context\SwitchContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 30, self::RULE_switch);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(207);
		        $this->match(self::TKSWITCH);
		        $this->setState(208);
		        $this->recursiveExpresion(0);
		        $this->setState(209);
		        $this->match(self::T__0);
		        $this->setState(213);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::TKCASE) {
		        	$this->setState(210);
		        	$this->case();
		        	$this->setState(215);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(217);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::TKDEFAULT) {
		        	$this->setState(216);
		        	$this->default();
		        }
		        $this->setState(219);
		        $this->match(self::T__1);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function case(): Context\CaseContext
		{
		    $localContext = new Context\CaseContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 32, self::RULE_case);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(221);
		        $this->match(self::TKCASE);
		        $this->setState(222);
		        $this->recursiveListaexp(0);
		        $this->setState(223);
		        $this->match(self::T__7);
		        $this->setState(227);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 37096973370654720) !== 0)) {
		        	$this->setState(224);
		        	$this->instruccion();
		        	$this->setState(229);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function default(): Context\DefaultContext
		{
		    $localContext = new Context\DefaultContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 34, self::RULE_default);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(230);
		        $this->match(self::TKDEFAULT);
		        $this->setState(231);
		        $this->match(self::T__7);
		        $this->setState(235);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 37096973370654720) !== 0)) {
		        	$this->setState(232);
		        	$this->instruccion();
		        	$this->setState(237);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function init(): Context\InitContext
		{
		    $localContext = new Context\InitContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 36, self::RULE_init);

		    try {
		        $this->setState(243);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 17, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(238);
		        	    $this->declaracion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(239);
		        	    $this->decl_corta();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(240);
		        	    $this->asignacion();
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(241);
		        	    $this->asig_compuesta();
		        	break;

		        	case 5:
		        	    $this->enterOuterAlt($localContext, 5);
		        	    $this->setState(242);
		        	    $this->inc_dec();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function post(): Context\PostContext
		{
		    $localContext = new Context\PostContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 38, self::RULE_post);

		    try {
		        $this->setState(248);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 18, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(245);
		        	    $this->asignacion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(246);
		        	    $this->asig_compuesta();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(247);
		        	    $this->inc_dec();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function for(): Context\ForContext
		{
		    $localContext = new Context\ForContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 40, self::RULE_for);

		    try {
		        $this->setState(264);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 19, $this->ctx)) {
		        	case 1:
		        	    $localContext = new Context\ForInfinitoContext($localContext);
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(250);
		        	    $this->match(self::TKFOR);
		        	    $this->setState(251);
		        	    $this->bloque();
		        	break;

		        	case 2:
		        	    $localContext = new Context\ForMientrasContext($localContext);
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(252);
		        	    $this->match(self::TKFOR);
		        	    $this->setState(253);
		        	    $this->recursiveExpresion(0);
		        	    $this->setState(254);
		        	    $this->bloque();
		        	break;

		        	case 3:
		        	    $localContext = new Context\ForClasicoContext($localContext);
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(256);
		        	    $this->match(self::TKFOR);
		        	    $this->setState(257);
		        	    $this->init();
		        	    $this->setState(258);
		        	    $this->match(self::T__8);
		        	    $this->setState(259);
		        	    $this->recursiveExpresion(0);
		        	    $this->setState(260);
		        	    $this->match(self::T__8);
		        	    $this->setState(261);
		        	    $this->post();
		        	    $this->setState(262);
		        	    $this->bloque();
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function break(): Context\BreakContext
		{
		    $localContext = new Context\BreakContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 42, self::RULE_break);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(266);
		        $this->match(self::TKBREAK);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function continue(): Context\ContinueContext
		{
		    $localContext = new Context\ContinueContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 44, self::RULE_continue);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(268);
		        $this->match(self::TKCONTINUE);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function func_dcl(): Context\Func_dclContext
		{
		    $localContext = new Context\Func_dclContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 46, self::RULE_func_dcl);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(270);
		        $this->match(self::TKFUNC);
		        $this->setState(271);
		        $this->match(self::IDNAME);
		        $this->setState(272);
		        $this->match(self::T__5);
		        $this->setState(274);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::IDNAME) {
		        	$this->setState(273);
		        	$this->parametros();
		        }
		        $this->setState(276);
		        $this->match(self::T__6);
		        $this->setState(278);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 1125899907858512) !== 0)) {
		        	$this->setState(277);
		        	$this->tipo_retorno();
		        }
		        $this->setState(280);
		        $this->bloque();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function parametros(): Context\ParametrosContext
		{
		    $localContext = new Context\ParametrosContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 48, self::RULE_parametros);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(282);
		        $this->parametro();
		        $this->setState(287);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::T__2) {
		        	$this->setState(283);
		        	$this->match(self::T__2);
		        	$this->setState(284);
		        	$this->parametro();
		        	$this->setState(289);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function parametro(): Context\ParametroContext
		{
		    $localContext = new Context\ParametroContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 50, self::RULE_parametro);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(290);
		        $this->match(self::IDNAME);
		        $this->setState(291);
		        $this->tipo_var();
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function tipo_retorno(): Context\Tipo_retornoContext
		{
		    $localContext = new Context\Tipo_retornoContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 52, self::RULE_tipo_retorno);

		    try {
		        $this->setState(305);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::T__3:
		            case self::TKINT:
		            case self::TKFLOAT:
		            case self::TKBOOL:
		            case self::TKRUNE:
		            case self::TKSTRING:
		            case self::PUNTERO:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(293);
		            	$this->tipo_var();
		            	break;

		            case self::T__5:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(294);
		            	$this->match(self::T__5);
		            	$this->setState(295);
		            	$this->tipo_var();
		            	$this->setState(300);
		            	$this->errorHandler->sync($this);

		            	$_la = $this->input->LA(1);
		            	while ($_la === self::T__2) {
		            		$this->setState(296);
		            		$this->match(self::T__2);
		            		$this->setState(297);
		            		$this->tipo_var();
		            		$this->setState(302);
		            		$this->errorHandler->sync($this);
		            		$_la = $this->input->LA(1);
		            	}
		            	$this->setState(303);
		            	$this->match(self::T__6);
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function return_stmt(): Context\Return_stmtContext
		{
		    $localContext = new Context\Return_stmtContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 54, self::RULE_return_stmt);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(307);
		        $this->match(self::TKRETURN);
		        $this->setState(309);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 25, $this->ctx)) {
		            case 1:
		        	    $this->setState(308);
		        	    $this->recursiveListaexp(0);
		        	break;
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function llamada_stmt(): Context\Llamada_stmtContext
		{
		    $localContext = new Context\Llamada_stmtContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 56, self::RULE_llamada_stmt);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(311);
		        $this->match(self::IDNAME);
		        $this->setState(312);
		        $this->match(self::T__5);
		        $this->setState(314);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 269090077736406096) !== 0)) {
		        	$this->setState(313);
		        	$this->recursiveListaexp(0);
		        }
		        $this->setState(316);
		        $this->match(self::T__6);
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function expresion(): Context\ExpresionContext
		{
			return $this->recursiveExpresion(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveExpresion(int $precedence): Context\ExpresionContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\ExpresionContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 58;
			$this->enterRecursionRule($localContext, 58, self::RULE_expresion, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(360);
				$this->errorHandler->sync($this);

				switch ($this->getInterpreter()->adaptivePredict($this->input, 30, $this->ctx)) {
					case 1:
					    $localContext = new Context\ExprUnariaContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;

					    $this->setState(319);
					    $this->match(self::T__9);
					    $this->setState(320);
					    $this->recursiveExpresion(21);
					break;

					case 2:
					    $localContext = new Context\ExprNotContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(321);
					    $this->match(self::T__10);
					    $this->setState(322);
					    $this->recursiveExpresion(20);
					break;

					case 3:
					    $localContext = new Context\ExprAgrupacionContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(323);
					    $this->match(self::T__5);
					    $this->setState(324);
					    $this->recursiveExpresion(0);
					    $this->setState(325);
					    $this->match(self::T__6);
					break;

					case 4:
					    $localContext = new Context\ExprArregloLiteralContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(327);
					    $this->tipo_var();
					    $this->setState(328);
					    $this->match(self::T__0);
					    $this->setState(330);
					    $this->errorHandler->sync($this);
					    $_la = $this->input->LA(1);

					    if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 269090077736406098) !== 0)) {
					    	$this->setState(329);
					    	$this->lista_valores();
					    }
					    $this->setState(332);
					    $this->match(self::T__1);
					break;

					case 5:
					    $localContext = new Context\ExprLlamadaContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(334);
					    $this->match(self::IDNAME);
					    $this->setState(335);
					    $this->match(self::T__5);
					    $this->setState(337);
					    $this->errorHandler->sync($this);
					    $_la = $this->input->LA(1);

					    if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 269090077736406096) !== 0)) {
					    	$this->setState(336);
					    	$this->recursiveListaexp(0);
					    }
					    $this->setState(339);
					    $this->match(self::T__6);
					break;

					case 6:
					    $localContext = new Context\ExprReferenciaContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(340);
					    $this->match(self::REFERENCIA);
					    $this->setState(341);
					    $this->asignable();
					break;

					case 7:
					    $localContext = new Context\ExprDesreferenciaContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(342);
					    $this->match(self::PUNTERO);
					    $this->setState(343);
					    $this->asignable();
					break;

					case 8:
					    $localContext = new Context\ExprAgrupacionContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(344);
					    $this->match(self::T__5);
					    $this->setState(345);
					    $this->recursiveExpresion(0);
					    $this->setState(346);
					    $this->match(self::T__6);
					break;

					case 9:
					    $localContext = new Context\ExprArregloLiteralContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(348);
					    $this->tipo_var();
					    $this->setState(349);
					    $this->match(self::T__0);
					    $this->setState(351);
					    $this->errorHandler->sync($this);
					    $_la = $this->input->LA(1);

					    if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 269090077736406098) !== 0)) {
					    	$this->setState(350);
					    	$this->lista_valores();
					    }
					    $this->setState(353);
					    $this->match(self::T__1);
					break;

					case 10:
					    $localContext = new Context\ExprEnteroContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(355);
					    $this->match(self::INT);
					break;

					case 11:
					    $localContext = new Context\ExprDecimalContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(356);
					    $this->match(self::FLOAT);
					break;

					case 12:
					    $localContext = new Context\ExprBooleanoContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(357);
					    $this->match(self::BOOL);
					break;

					case 13:
					    $localContext = new Context\ExprCadenaContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(358);
					    $this->match(self::STRING);
					break;

					case 14:
					    $localContext = new Context\ExprIdentificadorContext($localContext);
					    $this->ctx = $localContext;
					    $previousContext = $localContext;
					    $this->setState(359);
					    $this->match(self::IDNAME);
					break;
				}
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(389);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 32, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$this->setState(387);
						$this->errorHandler->sync($this);

						switch ($this->getInterpreter()->adaptivePredict($this->input, 31, $this->ctx)) {
							case 1:
							    $localContext = new Context\ExprMultiplicacionContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(362);

							    if (!($this->precpred($this->ctx, 16))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 16)");
							    }
							    $this->setState(363);

							    $_la = $this->input->LA(1);

							    if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 1125899906854912) !== 0))) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(364);
							    $this->recursiveExpresion(17);
							break;

							case 2:
							    $localContext = new Context\ExprSumaContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(365);

							    if (!($this->precpred($this->ctx, 15))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 15)");
							    }
							    $this->setState(366);

							    $_la = $this->input->LA(1);

							    if (!($_la === self::T__9 || $_la === self::T__13)) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(367);
							    $this->recursiveExpresion(16);
							break;

							case 3:
							    $localContext = new Context\ExprComparacionContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(368);

							    if (!($this->precpred($this->ctx, 14))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 14)");
							    }
							    $this->setState(369);

							    $_la = $this->input->LA(1);

							    if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 66060288) !== 0))) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(370);
							    $this->recursiveExpresion(15);
							break;

							case 4:
							    $localContext = new Context\ExprAndContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(371);

							    if (!($this->precpred($this->ctx, 13))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 13)");
							    }
							    $this->setState(372);
							    $this->match(self::TKAND);
							    $this->setState(373);
							    $this->recursiveExpresion(14);
							break;

							case 5:
							    $localContext = new Context\ExprOrContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(374);

							    if (!($this->precpred($this->ctx, 12))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 12)");
							    }
							    $this->setState(375);
							    $this->match(self::TKOR);
							    $this->setState(376);
							    $this->recursiveExpresion(13);
							break;

							case 6:
							    $localContext = new Context\ExprArregloAccesoContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(377);

							    if (!($this->precpred($this->ctx, 18))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 18)");
							    }
							    $this->setState(378);
							    $this->match(self::T__3);
							    $this->setState(379);
							    $this->recursiveExpresion(0);
							    $this->setState(380);
							    $this->match(self::T__4);
							break;

							case 7:
							    $localContext = new Context\ExprArregloAccesoContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(382);

							    if (!($this->precpred($this->ctx, 7))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 7)");
							    }
							    $this->setState(383);
							    $this->match(self::T__3);
							    $this->setState(384);
							    $this->recursiveExpresion(0);
							    $this->setState(385);
							    $this->match(self::T__4);
							break;
						} 
					}

					$this->setState(391);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 32, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function lista_valores(): Context\Lista_valoresContext
		{
		    $localContext = new Context\Lista_valoresContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 60, self::RULE_lista_valores);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(392);
		        $this->lista_valor();
		        $this->setState(397);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::T__2) {
		        	$this->setState(393);
		        	$this->match(self::T__2);
		        	$this->setState(394);
		        	$this->lista_valor();
		        	$this->setState(399);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function lista_valor(): Context\Lista_valorContext
		{
		    $localContext = new Context\Lista_valorContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 62, self::RULE_lista_valor);

		    try {
		        $this->setState(406);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::T__3:
		            case self::T__5:
		            case self::T__9:
		            case self::T__10:
		            case self::TKINT:
		            case self::TKFLOAT:
		            case self::TKBOOL:
		            case self::TKRUNE:
		            case self::TKSTRING:
		            case self::PUNTERO:
		            case self::REFERENCIA:
		            case self::BOOL:
		            case self::STRING:
		            case self::IDNAME:
		            case self::INT:
		            case self::FLOAT:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(400);
		            	$this->recursiveExpresion(0);
		            	break;

		            case self::T__0:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(401);
		            	$this->match(self::T__0);
		            	$this->setState(403);
		            	$this->errorHandler->sync($this);
		            	$_la = $this->input->LA(1);

		            	if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 269090077736406098) !== 0)) {
		            		$this->setState(402);
		            		$this->lista_valores();
		            	}
		            	$this->setState(405);
		            	$this->match(self::T__1);
		            	break;

		        default:
		        	throw new NoViableAltException($this);
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		/**
		 * @throws RecognitionException
		 */
		public function optipo(): Context\OptipoContext
		{
		    $localContext = new Context\OptipoContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 64, self::RULE_optipo);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(408);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 1015808) !== 0))) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		    } catch (RecognitionException $exception) {
		        $localContext->exception = $exception;
		        $this->errorHandler->reportError($this, $exception);
		        $this->errorHandler->recover($this, $exception);
		    } finally {
		        $this->exitRule();
		    }

		    return $localContext;
		}

		public function sempred(?RuleContext $localContext, int $ruleIndex, int $predicateIndex): bool
		{
			switch ($ruleIndex) {
					case 4:
						return $this->sempredListids($localContext, $predicateIndex);

					case 5:
						return $this->sempredListaexp($localContext, $predicateIndex);

					case 29:
						return $this->sempredExpresion($localContext, $predicateIndex);

				default:
					return true;
				}
		}

		private function sempredListids(?Context\ListidsContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 0:
			        return $this->precpred($this->ctx, 1);
			}

			return true;
		}

		private function sempredListaexp(?Context\ListaexpContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 1:
			        return $this->precpred($this->ctx, 1);
			}

			return true;
		}

		private function sempredExpresion(?Context\ExpresionContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 2:
			        return $this->precpred($this->ctx, 16);

			    case 3:
			        return $this->precpred($this->ctx, 15);

			    case 4:
			        return $this->precpred($this->ctx, 14);

			    case 5:
			        return $this->precpred($this->ctx, 13);

			    case 6:
			        return $this->precpred($this->ctx, 12);

			    case 7:
			        return $this->precpred($this->ctx, 18);

			    case 8:
			        return $this->precpred($this->ctx, 7);
			}

			return true;
		}
	}
}

namespace App\Language\Context {
	use Antlr\Antlr4\Runtime\ParserRuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;
	use Antlr\Antlr4\Runtime\Tree\TerminalNode;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;
	use App\Language\GolampiParser;
	use App\Language\GolampiVisitor;

	class InicioContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_inicio;
	    }

	    public function instrucciones(): ?InstruccionesContext
	    {
	    	return $this->getTypedRuleContext(InstruccionesContext::class, 0);
	    }

	    public function EOF(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::EOF, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitInicio($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class InstruccionesContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_instrucciones;
	    }

	    /**
	     * @return array<InstruccionContext>|InstruccionContext|null
	     */
	    public function instruccion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(InstruccionContext::class);
	    	}

	        return $this->getTypedRuleContext(InstruccionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitInstrucciones($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class InstruccionContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_instruccion;
	    }

	    public function declaracion(): ?DeclaracionContext
	    {
	    	return $this->getTypedRuleContext(DeclaracionContext::class, 0);
	    }

	    public function decl_corta(): ?Decl_cortaContext
	    {
	    	return $this->getTypedRuleContext(Decl_cortaContext::class, 0);
	    }

	    public function asignacion(): ?AsignacionContext
	    {
	    	return $this->getTypedRuleContext(AsignacionContext::class, 0);
	    }

	    public function asig_compuesta(): ?Asig_compuestaContext
	    {
	    	return $this->getTypedRuleContext(Asig_compuestaContext::class, 0);
	    }

	    public function inc_dec(): ?Inc_decContext
	    {
	    	return $this->getTypedRuleContext(Inc_decContext::class, 0);
	    }

	    public function si_stmt(): ?Si_stmtContext
	    {
	    	return $this->getTypedRuleContext(Si_stmtContext::class, 0);
	    }

	    public function switch(): ?SwitchContext
	    {
	    	return $this->getTypedRuleContext(SwitchContext::class, 0);
	    }

	    public function imprimir(): ?ImprimirContext
	    {
	    	return $this->getTypedRuleContext(ImprimirContext::class, 0);
	    }

	    public function for(): ?ForContext
	    {
	    	return $this->getTypedRuleContext(ForContext::class, 0);
	    }

	    public function break(): ?BreakContext
	    {
	    	return $this->getTypedRuleContext(BreakContext::class, 0);
	    }

	    public function continue(): ?ContinueContext
	    {
	    	return $this->getTypedRuleContext(ContinueContext::class, 0);
	    }

	    public function func_dcl(): ?Func_dclContext
	    {
	    	return $this->getTypedRuleContext(Func_dclContext::class, 0);
	    }

	    public function return_stmt(): ?Return_stmtContext
	    {
	    	return $this->getTypedRuleContext(Return_stmtContext::class, 0);
	    }

	    public function llamada_stmt(): ?Llamada_stmtContext
	    {
	    	return $this->getTypedRuleContext(Llamada_stmtContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitInstruccion($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class BloqueContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_bloque;
	    }

	    /**
	     * @return array<InstruccionContext>|InstruccionContext|null
	     */
	    public function instruccion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(InstruccionContext::class);
	    	}

	        return $this->getTypedRuleContext(InstruccionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitBloque($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ListidsContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_listids;
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    public function listids(): ?ListidsContext
	    {
	    	return $this->getTypedRuleContext(ListidsContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitListids($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ListaexpContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_listaexp;
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitListaexp($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Tipo_varContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_tipo_var;
	    }

	    public function optipo(): ?OptipoContext
	    {
	    	return $this->getTypedRuleContext(OptipoContext::class, 0);
	    }

	    public function PUNTERO(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::PUNTERO, 0);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitTipo_var($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class AsignableContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_asignable;
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitAsignable($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class DeclaracionContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_declaracion;
	    }

	    public function TKVAR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKVAR, 0);
	    }

	    public function listids(): ?ListidsContext
	    {
	    	return $this->getTypedRuleContext(ListidsContext::class, 0);
	    }

	    public function tipo_var(): ?Tipo_varContext
	    {
	    	return $this->getTypedRuleContext(Tipo_varContext::class, 0);
	    }

	    public function IGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IGUAL, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

	    public function TKCONST(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKCONST, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitDeclaracion($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Decl_cortaContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_decl_corta;
	    }

	    /**
	     * @return array<TerminalNode>|TerminalNode|null
	     */
	    public function IDNAME(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTokens(GolampiParser::IDNAME);
	    	}

	        return $this->getToken(GolampiParser::IDNAME, $index);
	    }

	    public function DPIGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::DPIGUAL, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitDecl_corta($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class AsignacionContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_asignacion;
	    }

	    /**
	     * @return array<AsignableContext>|AsignableContext|null
	     */
	    public function asignable(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(AsignableContext::class);
	    	}

	        return $this->getTypedRuleContext(AsignableContext::class, $index);
	    }

	    public function IGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IGUAL, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitAsignacion($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Asig_compuestaContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_asig_compuesta;
	    }

	    public function asignable(): ?AsignableContext
	    {
	    	return $this->getTypedRuleContext(AsignableContext::class, 0);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    public function MASIG(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::MASIG, 0);
	    }

	    public function MENOSIG(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::MENOSIG, 0);
	    }

	    public function PORIG(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::PORIG, 0);
	    }

	    public function DIVIG(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::DIVIG, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitAsig_compuesta($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Inc_decContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_inc_dec;
	    }

	    public function asignable(): ?AsignableContext
	    {
	    	return $this->getTypedRuleContext(AsignableContext::class, 0);
	    }

	    public function INC(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::INC, 0);
	    }

	    public function DEC(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::DEC, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitInc_dec($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Si_stmtContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_si_stmt;
	    }

	    public function TKIF(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKIF, 0);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    /**
	     * @return array<BloqueContext>|BloqueContext|null
	     */
	    public function bloque(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(BloqueContext::class);
	    	}

	        return $this->getTypedRuleContext(BloqueContext::class, $index);
	    }

	    public function TKELSE(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKELSE, 0);
	    }

	    public function si_stmt(): ?Si_stmtContext
	    {
	    	return $this->getTypedRuleContext(Si_stmtContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitSi_stmt($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ImprimirContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_imprimir;
	    }

	    public function TKPRINT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKPRINT, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitImprimir($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class SwitchContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_switch;
	    }

	    public function TKSWITCH(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKSWITCH, 0);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    /**
	     * @return array<CaseContext>|CaseContext|null
	     */
	    public function case(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(CaseContext::class);
	    	}

	        return $this->getTypedRuleContext(CaseContext::class, $index);
	    }

	    public function default(): ?DefaultContext
	    {
	    	return $this->getTypedRuleContext(DefaultContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitSwitch($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class CaseContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_case;
	    }

	    public function TKCASE(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKCASE, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

	    /**
	     * @return array<InstruccionContext>|InstruccionContext|null
	     */
	    public function instruccion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(InstruccionContext::class);
	    	}

	        return $this->getTypedRuleContext(InstruccionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitCase($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class DefaultContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_default;
	    }

	    public function TKDEFAULT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKDEFAULT, 0);
	    }

	    /**
	     * @return array<InstruccionContext>|InstruccionContext|null
	     */
	    public function instruccion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(InstruccionContext::class);
	    	}

	        return $this->getTypedRuleContext(InstruccionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitDefault($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class InitContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_init;
	    }

	    public function declaracion(): ?DeclaracionContext
	    {
	    	return $this->getTypedRuleContext(DeclaracionContext::class, 0);
	    }

	    public function decl_corta(): ?Decl_cortaContext
	    {
	    	return $this->getTypedRuleContext(Decl_cortaContext::class, 0);
	    }

	    public function asignacion(): ?AsignacionContext
	    {
	    	return $this->getTypedRuleContext(AsignacionContext::class, 0);
	    }

	    public function asig_compuesta(): ?Asig_compuestaContext
	    {
	    	return $this->getTypedRuleContext(Asig_compuestaContext::class, 0);
	    }

	    public function inc_dec(): ?Inc_decContext
	    {
	    	return $this->getTypedRuleContext(Inc_decContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitInit($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class PostContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_post;
	    }

	    public function asignacion(): ?AsignacionContext
	    {
	    	return $this->getTypedRuleContext(AsignacionContext::class, 0);
	    }

	    public function asig_compuesta(): ?Asig_compuestaContext
	    {
	    	return $this->getTypedRuleContext(Asig_compuestaContext::class, 0);
	    }

	    public function inc_dec(): ?Inc_decContext
	    {
	    	return $this->getTypedRuleContext(Inc_decContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitPost($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ForContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_for;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class ForInfinitoContext extends ForContext
	{
		public function __construct(ForContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function TKFOR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKFOR, 0);
	    }

	    public function bloque(): ?BloqueContext
	    {
	    	return $this->getTypedRuleContext(BloqueContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitForInfinito($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ForClasicoContext extends ForContext
	{
		public function __construct(ForContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function TKFOR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKFOR, 0);
	    }

	    public function init(): ?InitContext
	    {
	    	return $this->getTypedRuleContext(InitContext::class, 0);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    public function post(): ?PostContext
	    {
	    	return $this->getTypedRuleContext(PostContext::class, 0);
	    }

	    public function bloque(): ?BloqueContext
	    {
	    	return $this->getTypedRuleContext(BloqueContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitForClasico($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ForMientrasContext extends ForContext
	{
		public function __construct(ForContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function TKFOR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKFOR, 0);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    public function bloque(): ?BloqueContext
	    {
	    	return $this->getTypedRuleContext(BloqueContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitForMientras($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class BreakContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_break;
	    }

	    public function TKBREAK(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKBREAK, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitBreak($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ContinueContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_continue;
	    }

	    public function TKCONTINUE(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKCONTINUE, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitContinue($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Func_dclContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_func_dcl;
	    }

	    public function TKFUNC(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKFUNC, 0);
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    public function bloque(): ?BloqueContext
	    {
	    	return $this->getTypedRuleContext(BloqueContext::class, 0);
	    }

	    public function parametros(): ?ParametrosContext
	    {
	    	return $this->getTypedRuleContext(ParametrosContext::class, 0);
	    }

	    public function tipo_retorno(): ?Tipo_retornoContext
	    {
	    	return $this->getTypedRuleContext(Tipo_retornoContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitFunc_dcl($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ParametrosContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_parametros;
	    }

	    /**
	     * @return array<ParametroContext>|ParametroContext|null
	     */
	    public function parametro(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ParametroContext::class);
	    	}

	        return $this->getTypedRuleContext(ParametroContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitParametros($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ParametroContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_parametro;
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    public function tipo_var(): ?Tipo_varContext
	    {
	    	return $this->getTypedRuleContext(Tipo_varContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitParametro($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Tipo_retornoContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_tipo_retorno;
	    }

	    /**
	     * @return array<Tipo_varContext>|Tipo_varContext|null
	     */
	    public function tipo_var(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(Tipo_varContext::class);
	    	}

	        return $this->getTypedRuleContext(Tipo_varContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitTipo_retorno($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Return_stmtContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_return_stmt;
	    }

	    public function TKRETURN(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKRETURN, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitReturn_stmt($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Llamada_stmtContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_llamada_stmt;
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitLlamada_stmt($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class ExpresionContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_expresion;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class ExprAgrupacionContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprAgrupacion($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprArregloLiteralContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function tipo_var(): ?Tipo_varContext
	    {
	    	return $this->getTypedRuleContext(Tipo_varContext::class, 0);
	    }

	    public function lista_valores(): ?Lista_valoresContext
	    {
	    	return $this->getTypedRuleContext(Lista_valoresContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprArregloLiteral($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprEnteroContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function INT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::INT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprEntero($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprBooleanoContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function BOOL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::BOOL, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprBooleano($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprSumaContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprSuma($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprCadenaContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function STRING(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::STRING, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprCadena($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprIdentificadorContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprIdentificador($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprUnariaContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprUnaria($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprReferenciaContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function REFERENCIA(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::REFERENCIA, 0);
	    }

	    public function asignable(): ?AsignableContext
	    {
	    	return $this->getTypedRuleContext(AsignableContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprReferencia($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprLlamadaContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    public function listaexp(): ?ListaexpContext
	    {
	    	return $this->getTypedRuleContext(ListaexpContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprLlamada($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprNotContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprNot($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprAndContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

	    public function TKAND(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKAND, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprAnd($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprDecimalContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function FLOAT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::FLOAT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprDecimal($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprArregloAccesoContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprArregloAcceso($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprOrContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

	    public function TKOR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKOR, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprOr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprDesreferenciaContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function PUNTERO(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::PUNTERO, 0);
	    }

	    public function asignable(): ?AsignableContext
	    {
	    	return $this->getTypedRuleContext(AsignableContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprDesreferencia($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprMultiplicacionContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

	    public function PUNTERO(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::PUNTERO, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprMultiplicacion($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class ExprComparacionContext extends ExpresionContext
	{
		public function __construct(ExpresionContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExpresionContext>|ExpresionContext|null
	     */
	    public function expresion(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExpresionContext::class);
	    	}

	        return $this->getTypedRuleContext(ExpresionContext::class, $index);
	    }

	    public function IGUAL_IGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IGUAL_IGUAL, 0);
	    }

	    public function DIFERENTE(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::DIFERENTE, 0);
	    }

	    public function MENOR_IGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::MENOR_IGUAL, 0);
	    }

	    public function MAYOR_IGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::MAYOR_IGUAL, 0);
	    }

	    public function MENOR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::MENOR, 0);
	    }

	    public function MAYOR(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::MAYOR, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExprComparacion($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Lista_valoresContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_lista_valores;
	    }

	    /**
	     * @return array<Lista_valorContext>|Lista_valorContext|null
	     */
	    public function lista_valor(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(Lista_valorContext::class);
	    	}

	        return $this->getTypedRuleContext(Lista_valorContext::class, $index);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitLista_valores($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class Lista_valorContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_lista_valor;
	    }

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    public function lista_valores(): ?Lista_valoresContext
	    {
	    	return $this->getTypedRuleContext(Lista_valoresContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitLista_valor($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class OptipoContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_optipo;
	    }

	    public function TKINT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKINT, 0);
	    }

	    public function TKFLOAT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKFLOAT, 0);
	    }

	    public function TKBOOL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKBOOL, 0);
	    }

	    public function TKRUNE(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKRUNE, 0);
	    }

	    public function TKSTRING(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::TKSTRING, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitOptipo($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 
}