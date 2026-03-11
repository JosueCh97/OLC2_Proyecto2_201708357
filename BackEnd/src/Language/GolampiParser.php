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
               T__12 = 13, TKINT = 14, TKFLOAT = 15, TKBOOL = 16, TKRUNE = 17, 
               TKSTRING = 18, IGUAL_IGUAL = 19, DIFERENTE = 20, MENOR_IGUAL = 21, 
               MAYOR_IGUAL = 22, MENOR = 23, MAYOR = 24, TKAND = 25, TKOR = 26, 
               TKVAR = 27, TKCONST = 28, IGUAL = 29, NIL = 30, DEC = 31, 
               INC = 32, MASIG = 33, MENOSIG = 34, PORIG = 35, DIVIG = 36, 
               TKMAIN = 37, TKFUNC = 38, TKPRINT = 39, TKLEN = 40, TKNOW = 41, 
               TKSUBSTR = 42, TYPEOF = 43, TKIF = 44, TKELSE = 45, TKSWITCH = 46, 
               TKCASE = 47, TKDEFAULT = 48, TKFOR = 49, TKBREAK = 50, TKCONTINUE = 51, 
               TKRETURN = 52, BOOL = 53, STRING = 54, UNICODE = 55, IDNAME = 56, 
               INT = 57, FLOAT = 58, COMENT = 59, MULTILINE_COMMENT = 60, 
               WS = 61;

		public const RULE_inicio = 0, RULE_instrucciones = 1, RULE_instruccion = 2, 
               RULE_bloque = 3, RULE_listids = 4, RULE_listaexp = 5, RULE_declaracion = 6, 
               RULE_asignacion = 7, RULE_asig_compuesta = 8, RULE_inc_dec = 9, 
               RULE_si_stmt = 10, RULE_imprimir = 11, RULE_switch = 12, 
               RULE_case = 13, RULE_default = 14, RULE_init = 15, RULE_post = 16, 
               RULE_for = 17, RULE_break = 18, RULE_continue = 19, RULE_expresion = 20, 
               RULE_optipo = 21;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'inicio', 'instrucciones', 'instruccion', 'bloque', 'listids', 'listaexp', 
			'declaracion', 'asignacion', 'asig_compuesta', 'inc_dec', 'si_stmt', 
			'imprimir', 'switch', 'case', 'default', 'init', 'post', 'for', 'break', 
			'continue', 'expresion', 'optipo'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "'{'", "'}'", "','", "'('", "')'", "':'", "';'", "'-'", "'!'", 
		    "'*'", "'/'", "'%'", "'+'", "'int32'", "'float32'", "'bool'", "'rune'", 
		    "'string'", "'=='", "'!='", "'<='", "'>='", "'<'", "'>'", "'&&'", 
		    "'||'", "'var'", "'const'", "'='", "'nil'", "'--'", "'++'", "'+='", 
		    "'-='", "'*='", "'/='", "'main'", "'func'", "'fmt.Print'", "'len'", 
		    "'now'", "'substr'", "'typeof'", "'if'", "'else'", "'switch'", "'case'", 
		    "'default'", "'for'", "'break'", "'continue'", "'return'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, null, null, null, null, null, null, null, null, null, null, 
		    null, null, null, "TKINT", "TKFLOAT", "TKBOOL", "TKRUNE", "TKSTRING", 
		    "IGUAL_IGUAL", "DIFERENTE", "MENOR_IGUAL", "MAYOR_IGUAL", "MENOR", 
		    "MAYOR", "TKAND", "TKOR", "TKVAR", "TKCONST", "IGUAL", "NIL", "DEC", 
		    "INC", "MASIG", "MENOSIG", "PORIG", "DIVIG", "TKMAIN", "TKFUNC", "TKPRINT", 
		    "TKLEN", "TKNOW", "TKSUBSTR", "TYPEOF", "TKIF", "TKELSE", "TKSWITCH", 
		    "TKCASE", "TKDEFAULT", "TKFOR", "TKBREAK", "TKCONTINUE", "TKRETURN", 
		    "BOOL", "STRING", "UNICODE", "IDNAME", "INT", "FLOAT", "COMENT", "MULTILINE_COMMENT", 
		    "WS"
		];

		private const SERIALIZED_ATN =
			[4, 1, 61, 240, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 2, 4, 
		    7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 2, 9, 7, 9, 
		    2, 10, 7, 10, 2, 11, 7, 11, 2, 12, 7, 12, 2, 13, 7, 13, 2, 14, 7, 
		    14, 2, 15, 7, 15, 2, 16, 7, 16, 2, 17, 7, 17, 2, 18, 7, 18, 2, 19, 
		    7, 19, 2, 20, 7, 20, 2, 21, 7, 21, 1, 0, 1, 0, 1, 0, 1, 1, 4, 1, 49, 
		    8, 1, 11, 1, 12, 1, 50, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 1, 2, 
		    1, 2, 1, 2, 1, 2, 3, 2, 63, 8, 2, 1, 3, 1, 3, 5, 3, 67, 8, 3, 10, 
		    3, 12, 3, 70, 9, 3, 1, 3, 1, 3, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 
		    5, 4, 80, 8, 4, 10, 4, 12, 4, 83, 9, 4, 1, 5, 1, 5, 1, 5, 1, 5, 1, 
		    5, 1, 5, 5, 5, 91, 8, 5, 10, 5, 12, 5, 94, 9, 5, 1, 6, 1, 6, 1, 6, 
		    1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 
		    1, 6, 1, 6, 3, 6, 112, 8, 6, 1, 7, 1, 7, 1, 7, 1, 7, 1, 8, 1, 8, 1, 
		    8, 1, 8, 1, 9, 1, 9, 1, 9, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 
		    3, 10, 131, 8, 10, 3, 10, 133, 8, 10, 1, 11, 1, 11, 1, 11, 1, 11, 
		    1, 11, 1, 12, 1, 12, 1, 12, 1, 12, 5, 12, 144, 8, 12, 10, 12, 12, 
		    12, 147, 9, 12, 1, 12, 3, 12, 150, 8, 12, 1, 12, 1, 12, 1, 13, 1, 
		    13, 1, 13, 1, 13, 5, 13, 158, 8, 13, 10, 13, 12, 13, 161, 9, 13, 1, 
		    14, 1, 14, 1, 14, 5, 14, 166, 8, 14, 10, 14, 12, 14, 169, 9, 14, 1, 
		    15, 1, 15, 1, 15, 1, 15, 3, 15, 175, 8, 15, 1, 16, 1, 16, 1, 16, 3, 
		    16, 180, 8, 16, 1, 17, 1, 17, 1, 17, 1, 17, 1, 17, 1, 17, 1, 17, 1, 
		    17, 1, 17, 1, 17, 1, 17, 1, 17, 1, 17, 1, 17, 3, 17, 196, 8, 17, 1, 
		    18, 1, 18, 1, 19, 1, 19, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 
		    1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 3, 20, 216, 
		    8, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 
		    20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 1, 20, 5, 20, 233, 8, 20, 10, 
		    20, 12, 20, 236, 9, 20, 1, 21, 1, 21, 1, 21, 0, 3, 8, 10, 40, 22, 
		    0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 
		    36, 38, 40, 42, 0, 6, 1, 0, 33, 36, 1, 0, 31, 32, 1, 0, 10, 12, 2, 
		    0, 8, 8, 13, 13, 1, 0, 19, 24, 1, 0, 14, 18, 257, 0, 44, 1, 0, 0, 
		    0, 2, 48, 1, 0, 0, 0, 4, 62, 1, 0, 0, 0, 6, 64, 1, 0, 0, 0, 8, 73, 
		    1, 0, 0, 0, 10, 84, 1, 0, 0, 0, 12, 111, 1, 0, 0, 0, 14, 113, 1, 0, 
		    0, 0, 16, 117, 1, 0, 0, 0, 18, 121, 1, 0, 0, 0, 20, 124, 1, 0, 0, 
		    0, 22, 134, 1, 0, 0, 0, 24, 139, 1, 0, 0, 0, 26, 153, 1, 0, 0, 0, 
		    28, 162, 1, 0, 0, 0, 30, 174, 1, 0, 0, 0, 32, 179, 1, 0, 0, 0, 34, 
		    195, 1, 0, 0, 0, 36, 197, 1, 0, 0, 0, 38, 199, 1, 0, 0, 0, 40, 215, 
		    1, 0, 0, 0, 42, 237, 1, 0, 0, 0, 44, 45, 3, 2, 1, 0, 45, 46, 5, 0, 
		    0, 1, 46, 1, 1, 0, 0, 0, 47, 49, 3, 4, 2, 0, 48, 47, 1, 0, 0, 0, 49, 
		    50, 1, 0, 0, 0, 50, 48, 1, 0, 0, 0, 50, 51, 1, 0, 0, 0, 51, 3, 1, 
		    0, 0, 0, 52, 63, 3, 12, 6, 0, 53, 63, 3, 14, 7, 0, 54, 63, 3, 16, 
		    8, 0, 55, 63, 3, 18, 9, 0, 56, 63, 3, 20, 10, 0, 57, 63, 3, 24, 12, 
		    0, 58, 63, 3, 22, 11, 0, 59, 63, 3, 34, 17, 0, 60, 63, 3, 36, 18, 
		    0, 61, 63, 3, 38, 19, 0, 62, 52, 1, 0, 0, 0, 62, 53, 1, 0, 0, 0, 62, 
		    54, 1, 0, 0, 0, 62, 55, 1, 0, 0, 0, 62, 56, 1, 0, 0, 0, 62, 57, 1, 
		    0, 0, 0, 62, 58, 1, 0, 0, 0, 62, 59, 1, 0, 0, 0, 62, 60, 1, 0, 0, 
		    0, 62, 61, 1, 0, 0, 0, 63, 5, 1, 0, 0, 0, 64, 68, 5, 1, 0, 0, 65, 
		    67, 3, 4, 2, 0, 66, 65, 1, 0, 0, 0, 67, 70, 1, 0, 0, 0, 68, 66, 1, 
		    0, 0, 0, 68, 69, 1, 0, 0, 0, 69, 71, 1, 0, 0, 0, 70, 68, 1, 0, 0, 
		    0, 71, 72, 5, 2, 0, 0, 72, 7, 1, 0, 0, 0, 73, 74, 6, 4, -1, 0, 74, 
		    75, 5, 56, 0, 0, 75, 81, 1, 0, 0, 0, 76, 77, 10, 1, 0, 0, 77, 78, 
		    5, 3, 0, 0, 78, 80, 5, 56, 0, 0, 79, 76, 1, 0, 0, 0, 80, 83, 1, 0, 
		    0, 0, 81, 79, 1, 0, 0, 0, 81, 82, 1, 0, 0, 0, 82, 9, 1, 0, 0, 0, 83, 
		    81, 1, 0, 0, 0, 84, 85, 6, 5, -1, 0, 85, 86, 3, 40, 20, 0, 86, 92, 
		    1, 0, 0, 0, 87, 88, 10, 1, 0, 0, 88, 89, 5, 3, 0, 0, 89, 91, 3, 40, 
		    20, 0, 90, 87, 1, 0, 0, 0, 91, 94, 1, 0, 0, 0, 92, 90, 1, 0, 0, 0, 
		    92, 93, 1, 0, 0, 0, 93, 11, 1, 0, 0, 0, 94, 92, 1, 0, 0, 0, 95, 96, 
		    5, 27, 0, 0, 96, 97, 3, 8, 4, 0, 97, 98, 3, 42, 21, 0, 98, 112, 1, 
		    0, 0, 0, 99, 100, 5, 27, 0, 0, 100, 101, 3, 8, 4, 0, 101, 102, 3, 
		    42, 21, 0, 102, 103, 5, 29, 0, 0, 103, 104, 3, 10, 5, 0, 104, 112, 
		    1, 0, 0, 0, 105, 106, 5, 28, 0, 0, 106, 107, 3, 8, 4, 0, 107, 108, 
		    3, 42, 21, 0, 108, 109, 5, 29, 0, 0, 109, 110, 3, 10, 5, 0, 110, 112, 
		    1, 0, 0, 0, 111, 95, 1, 0, 0, 0, 111, 99, 1, 0, 0, 0, 111, 105, 1, 
		    0, 0, 0, 112, 13, 1, 0, 0, 0, 113, 114, 3, 8, 4, 0, 114, 115, 5, 29, 
		    0, 0, 115, 116, 3, 10, 5, 0, 116, 15, 1, 0, 0, 0, 117, 118, 5, 56, 
		    0, 0, 118, 119, 7, 0, 0, 0, 119, 120, 3, 40, 20, 0, 120, 17, 1, 0, 
		    0, 0, 121, 122, 5, 56, 0, 0, 122, 123, 7, 1, 0, 0, 123, 19, 1, 0, 
		    0, 0, 124, 125, 5, 44, 0, 0, 125, 126, 3, 40, 20, 0, 126, 132, 3, 
		    6, 3, 0, 127, 130, 5, 45, 0, 0, 128, 131, 3, 6, 3, 0, 129, 131, 3, 
		    20, 10, 0, 130, 128, 1, 0, 0, 0, 130, 129, 1, 0, 0, 0, 131, 133, 1, 
		    0, 0, 0, 132, 127, 1, 0, 0, 0, 132, 133, 1, 0, 0, 0, 133, 21, 1, 0, 
		    0, 0, 134, 135, 5, 39, 0, 0, 135, 136, 5, 4, 0, 0, 136, 137, 3, 10, 
		    5, 0, 137, 138, 5, 5, 0, 0, 138, 23, 1, 0, 0, 0, 139, 140, 5, 46, 
		    0, 0, 140, 141, 3, 40, 20, 0, 141, 145, 5, 1, 0, 0, 142, 144, 3, 26, 
		    13, 0, 143, 142, 1, 0, 0, 0, 144, 147, 1, 0, 0, 0, 145, 143, 1, 0, 
		    0, 0, 145, 146, 1, 0, 0, 0, 146, 149, 1, 0, 0, 0, 147, 145, 1, 0, 
		    0, 0, 148, 150, 3, 28, 14, 0, 149, 148, 1, 0, 0, 0, 149, 150, 1, 0, 
		    0, 0, 150, 151, 1, 0, 0, 0, 151, 152, 5, 2, 0, 0, 152, 25, 1, 0, 0, 
		    0, 153, 154, 5, 47, 0, 0, 154, 155, 3, 10, 5, 0, 155, 159, 5, 6, 0, 
		    0, 156, 158, 3, 4, 2, 0, 157, 156, 1, 0, 0, 0, 158, 161, 1, 0, 0, 
		    0, 159, 157, 1, 0, 0, 0, 159, 160, 1, 0, 0, 0, 160, 27, 1, 0, 0, 0, 
		    161, 159, 1, 0, 0, 0, 162, 163, 5, 48, 0, 0, 163, 167, 5, 6, 0, 0, 
		    164, 166, 3, 4, 2, 0, 165, 164, 1, 0, 0, 0, 166, 169, 1, 0, 0, 0, 
		    167, 165, 1, 0, 0, 0, 167, 168, 1, 0, 0, 0, 168, 29, 1, 0, 0, 0, 169, 
		    167, 1, 0, 0, 0, 170, 175, 3, 12, 6, 0, 171, 175, 3, 14, 7, 0, 172, 
		    175, 3, 16, 8, 0, 173, 175, 3, 18, 9, 0, 174, 170, 1, 0, 0, 0, 174, 
		    171, 1, 0, 0, 0, 174, 172, 1, 0, 0, 0, 174, 173, 1, 0, 0, 0, 175, 
		    31, 1, 0, 0, 0, 176, 180, 3, 14, 7, 0, 177, 180, 3, 16, 8, 0, 178, 
		    180, 3, 18, 9, 0, 179, 176, 1, 0, 0, 0, 179, 177, 1, 0, 0, 0, 179, 
		    178, 1, 0, 0, 0, 180, 33, 1, 0, 0, 0, 181, 182, 5, 49, 0, 0, 182, 
		    196, 3, 6, 3, 0, 183, 184, 5, 49, 0, 0, 184, 185, 3, 40, 20, 0, 185, 
		    186, 3, 6, 3, 0, 186, 196, 1, 0, 0, 0, 187, 188, 5, 49, 0, 0, 188, 
		    189, 3, 30, 15, 0, 189, 190, 5, 7, 0, 0, 190, 191, 3, 40, 20, 0, 191, 
		    192, 5, 7, 0, 0, 192, 193, 3, 32, 16, 0, 193, 194, 3, 6, 3, 0, 194, 
		    196, 1, 0, 0, 0, 195, 181, 1, 0, 0, 0, 195, 183, 1, 0, 0, 0, 195, 
		    187, 1, 0, 0, 0, 196, 35, 1, 0, 0, 0, 197, 198, 5, 50, 0, 0, 198, 
		    37, 1, 0, 0, 0, 199, 200, 5, 51, 0, 0, 200, 39, 1, 0, 0, 0, 201, 202, 
		    6, 20, -1, 0, 202, 203, 5, 8, 0, 0, 203, 216, 3, 40, 20, 13, 204, 
		    205, 5, 9, 0, 0, 205, 216, 3, 40, 20, 12, 206, 207, 5, 4, 0, 0, 207, 
		    208, 3, 40, 20, 0, 208, 209, 5, 5, 0, 0, 209, 216, 1, 0, 0, 0, 210, 
		    216, 5, 57, 0, 0, 211, 216, 5, 58, 0, 0, 212, 216, 5, 53, 0, 0, 213, 
		    216, 5, 54, 0, 0, 214, 216, 5, 56, 0, 0, 215, 201, 1, 0, 0, 0, 215, 
		    204, 1, 0, 0, 0, 215, 206, 1, 0, 0, 0, 215, 210, 1, 0, 0, 0, 215, 
		    211, 1, 0, 0, 0, 215, 212, 1, 0, 0, 0, 215, 213, 1, 0, 0, 0, 215, 
		    214, 1, 0, 0, 0, 216, 234, 1, 0, 0, 0, 217, 218, 10, 10, 0, 0, 218, 
		    219, 7, 2, 0, 0, 219, 233, 3, 40, 20, 11, 220, 221, 10, 9, 0, 0, 221, 
		    222, 7, 3, 0, 0, 222, 233, 3, 40, 20, 10, 223, 224, 10, 8, 0, 0, 224, 
		    225, 7, 4, 0, 0, 225, 233, 3, 40, 20, 9, 226, 227, 10, 7, 0, 0, 227, 
		    228, 5, 25, 0, 0, 228, 233, 3, 40, 20, 8, 229, 230, 10, 6, 0, 0, 230, 
		    231, 5, 26, 0, 0, 231, 233, 3, 40, 20, 7, 232, 217, 1, 0, 0, 0, 232, 
		    220, 1, 0, 0, 0, 232, 223, 1, 0, 0, 0, 232, 226, 1, 0, 0, 0, 232, 
		    229, 1, 0, 0, 0, 233, 236, 1, 0, 0, 0, 234, 232, 1, 0, 0, 0, 234, 
		    235, 1, 0, 0, 0, 235, 41, 1, 0, 0, 0, 236, 234, 1, 0, 0, 0, 237, 238, 
		    7, 5, 0, 0, 238, 43, 1, 0, 0, 0, 18, 50, 62, 68, 81, 92, 111, 130, 
		    132, 145, 149, 159, 167, 174, 179, 195, 215, 232, 234];
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
		        $this->setState(44);
		        $this->instrucciones();
		        $this->setState(45);
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
		        $this->setState(48); 
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        do {
		        	$this->setState(47);
		        	$this->instruccion();
		        	$this->setState(50); 
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        } while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 76086754800566272) !== 0));
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
		        $this->setState(62);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 1, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(52);
		        	    $this->declaracion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(53);
		        	    $this->asignacion();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(54);
		        	    $this->asig_compuesta();
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(55);
		        	    $this->inc_dec();
		        	break;

		        	case 5:
		        	    $this->enterOuterAlt($localContext, 5);
		        	    $this->setState(56);
		        	    $this->si_stmt();
		        	break;

		        	case 6:
		        	    $this->enterOuterAlt($localContext, 6);
		        	    $this->setState(57);
		        	    $this->switch();
		        	break;

		        	case 7:
		        	    $this->enterOuterAlt($localContext, 7);
		        	    $this->setState(58);
		        	    $this->imprimir();
		        	break;

		        	case 8:
		        	    $this->enterOuterAlt($localContext, 8);
		        	    $this->setState(59);
		        	    $this->for();
		        	break;

		        	case 9:
		        	    $this->enterOuterAlt($localContext, 9);
		        	    $this->setState(60);
		        	    $this->break();
		        	break;

		        	case 10:
		        	    $this->enterOuterAlt($localContext, 10);
		        	    $this->setState(61);
		        	    $this->continue();
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
		        $this->setState(64);
		        $this->match(self::T__0);
		        $this->setState(68);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 76086754800566272) !== 0)) {
		        	$this->setState(65);
		        	$this->instruccion();
		        	$this->setState(70);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(71);
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
				$this->setState(74);
				$this->match(self::IDNAME);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(81);
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
						$this->setState(76);

						if (!($this->precpred($this->ctx, 1))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
						}
						$this->setState(77);
						$this->match(self::T__2);
						$this->setState(78);
						$this->match(self::IDNAME); 
					}

					$this->setState(83);
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
				$this->setState(85);
				$this->recursiveExpresion(0);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(92);
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
						$this->setState(87);

						if (!($this->precpred($this->ctx, 1))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
						}
						$this->setState(88);
						$this->match(self::T__2);
						$this->setState(89);
						$this->recursiveExpresion(0); 
					}

					$this->setState(94);
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
		public function declaracion(): Context\DeclaracionContext
		{
		    $localContext = new Context\DeclaracionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 12, self::RULE_declaracion);

		    try {
		        $this->setState(111);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 5, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(95);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(96);
		        	    $this->recursiveListids(0);
		        	    $this->setState(97);
		        	    $this->optipo();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(99);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(100);
		        	    $this->recursiveListids(0);
		        	    $this->setState(101);
		        	    $this->optipo();
		        	    $this->setState(102);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(103);
		        	    $this->recursiveListaexp(0);
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(105);
		        	    $this->match(self::TKCONST);
		        	    $this->setState(106);
		        	    $this->recursiveListids(0);
		        	    $this->setState(107);
		        	    $this->optipo();
		        	    $this->setState(108);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(109);
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
		public function asignacion(): Context\AsignacionContext
		{
		    $localContext = new Context\AsignacionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 14, self::RULE_asignacion);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(113);
		        $this->recursiveListids(0);
		        $this->setState(114);
		        $this->match(self::IGUAL);
		        $this->setState(115);
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

		    $this->enterRule($localContext, 16, self::RULE_asig_compuesta);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(117);
		        $this->match(self::IDNAME);
		        $this->setState(118);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 128849018880) !== 0))) {
		        $this->errorHandler->recoverInline($this);
		        } else {
		        	if ($this->input->LA(1) === Token::EOF) {
		        	    $this->matchedEOF = true;
		            }

		        	$this->errorHandler->reportMatch($this);
		        	$this->consume();
		        }
		        $this->setState(119);
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

		    $this->enterRule($localContext, 18, self::RULE_inc_dec);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(121);
		        $this->match(self::IDNAME);
		        $this->setState(122);

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

		    $this->enterRule($localContext, 20, self::RULE_si_stmt);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(124);
		        $this->match(self::TKIF);
		        $this->setState(125);
		        $this->recursiveExpresion(0);
		        $this->setState(126);
		        $this->bloque();
		        $this->setState(132);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::TKELSE) {
		        	$this->setState(127);
		        	$this->match(self::TKELSE);
		        	$this->setState(130);
		        	$this->errorHandler->sync($this);

		        	switch ($this->input->LA(1)) {
		        	    case self::T__0:
		        	    	$this->setState(128);
		        	    	$this->bloque();
		        	    	break;

		        	    case self::TKIF:
		        	    	$this->setState(129);
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

		    $this->enterRule($localContext, 22, self::RULE_imprimir);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(134);
		        $this->match(self::TKPRINT);
		        $this->setState(135);
		        $this->match(self::T__3);
		        $this->setState(136);
		        $this->recursiveListaexp(0);
		        $this->setState(137);
		        $this->match(self::T__4);
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

		    $this->enterRule($localContext, 24, self::RULE_switch);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(139);
		        $this->match(self::TKSWITCH);
		        $this->setState(140);
		        $this->recursiveExpresion(0);
		        $this->setState(141);
		        $this->match(self::T__0);
		        $this->setState(145);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while ($_la === self::TKCASE) {
		        	$this->setState(142);
		        	$this->case();
		        	$this->setState(147);
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        }
		        $this->setState(149);
		        $this->errorHandler->sync($this);
		        $_la = $this->input->LA(1);

		        if ($_la === self::TKDEFAULT) {
		        	$this->setState(148);
		        	$this->default();
		        }
		        $this->setState(151);
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

		    $this->enterRule($localContext, 26, self::RULE_case);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(153);
		        $this->match(self::TKCASE);
		        $this->setState(154);
		        $this->recursiveListaexp(0);
		        $this->setState(155);
		        $this->match(self::T__5);
		        $this->setState(159);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 76086754800566272) !== 0)) {
		        	$this->setState(156);
		        	$this->instruccion();
		        	$this->setState(161);
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

		    $this->enterRule($localContext, 28, self::RULE_default);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(162);
		        $this->match(self::TKDEFAULT);
		        $this->setState(163);
		        $this->match(self::T__5);
		        $this->setState(167);
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 76086754800566272) !== 0)) {
		        	$this->setState(164);
		        	$this->instruccion();
		        	$this->setState(169);
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

		    $this->enterRule($localContext, 30, self::RULE_init);

		    try {
		        $this->setState(174);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 12, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(170);
		        	    $this->declaracion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(171);
		        	    $this->asignacion();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(172);
		        	    $this->asig_compuesta();
		        	break;

		        	case 4:
		        	    $this->enterOuterAlt($localContext, 4);
		        	    $this->setState(173);
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

		    $this->enterRule($localContext, 32, self::RULE_post);

		    try {
		        $this->setState(179);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 13, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(176);
		        	    $this->asignacion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(177);
		        	    $this->asig_compuesta();
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(178);
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

		    $this->enterRule($localContext, 34, self::RULE_for);

		    try {
		        $this->setState(195);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 14, $this->ctx)) {
		        	case 1:
		        	    $localContext = new Context\ForInfinitoContext($localContext);
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(181);
		        	    $this->match(self::TKFOR);
		        	    $this->setState(182);
		        	    $this->bloque();
		        	break;

		        	case 2:
		        	    $localContext = new Context\ForMientrasContext($localContext);
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(183);
		        	    $this->match(self::TKFOR);
		        	    $this->setState(184);
		        	    $this->recursiveExpresion(0);
		        	    $this->setState(185);
		        	    $this->bloque();
		        	break;

		        	case 3:
		        	    $localContext = new Context\ForClasicoContext($localContext);
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(187);
		        	    $this->match(self::TKFOR);
		        	    $this->setState(188);
		        	    $this->init();
		        	    $this->setState(189);
		        	    $this->match(self::T__6);
		        	    $this->setState(190);
		        	    $this->recursiveExpresion(0);
		        	    $this->setState(191);
		        	    $this->match(self::T__6);
		        	    $this->setState(192);
		        	    $this->post();
		        	    $this->setState(193);
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

		    $this->enterRule($localContext, 36, self::RULE_break);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(197);
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

		    $this->enterRule($localContext, 38, self::RULE_continue);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(199);
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
			$startState = 40;
			$this->enterRecursionRule($localContext, 40, self::RULE_expresion, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(215);
				$this->errorHandler->sync($this);

				switch ($this->input->LA(1)) {
				    case self::T__7:
				    	$localContext = new Context\ExprUnariaContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;

				    	$this->setState(202);
				    	$this->match(self::T__7);
				    	$this->setState(203);
				    	$this->recursiveExpresion(13);
				    	break;

				    case self::T__8:
				    	$localContext = new Context\ExprNotContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(204);
				    	$this->match(self::T__8);
				    	$this->setState(205);
				    	$this->recursiveExpresion(12);
				    	break;

				    case self::T__3:
				    	$localContext = new Context\ExprAgrupacionContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(206);
				    	$this->match(self::T__3);
				    	$this->setState(207);
				    	$this->recursiveExpresion(0);
				    	$this->setState(208);
				    	$this->match(self::T__4);
				    	break;

				    case self::INT:
				    	$localContext = new Context\ExprEnteroContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(210);
				    	$this->match(self::INT);
				    	break;

				    case self::FLOAT:
				    	$localContext = new Context\ExprDecimalContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(211);
				    	$this->match(self::FLOAT);
				    	break;

				    case self::BOOL:
				    	$localContext = new Context\ExprBooleanoContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(212);
				    	$this->match(self::BOOL);
				    	break;

				    case self::STRING:
				    	$localContext = new Context\ExprCadenaContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(213);
				    	$this->match(self::STRING);
				    	break;

				    case self::IDNAME:
				    	$localContext = new Context\ExprIdentificadorContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(214);
				    	$this->match(self::IDNAME);
				    	break;

				default:
					throw new NoViableAltException($this);
				}
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(234);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 17, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$this->setState(232);
						$this->errorHandler->sync($this);

						switch ($this->getInterpreter()->adaptivePredict($this->input, 16, $this->ctx)) {
							case 1:
							    $localContext = new Context\ExprMultiplicacionContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(217);

							    if (!($this->precpred($this->ctx, 10))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 10)");
							    }
							    $this->setState(218);

							    $_la = $this->input->LA(1);

							    if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 7168) !== 0))) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(219);
							    $this->recursiveExpresion(11);
							break;

							case 2:
							    $localContext = new Context\ExprSumaContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(220);

							    if (!($this->precpred($this->ctx, 9))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 9)");
							    }
							    $this->setState(221);

							    $_la = $this->input->LA(1);

							    if (!($_la === self::T__7 || $_la === self::T__12)) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(222);
							    $this->recursiveExpresion(10);
							break;

							case 3:
							    $localContext = new Context\ExprComparacionContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(223);

							    if (!($this->precpred($this->ctx, 8))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 8)");
							    }
							    $this->setState(224);

							    $_la = $this->input->LA(1);

							    if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 33030144) !== 0))) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(225);
							    $this->recursiveExpresion(9);
							break;

							case 4:
							    $localContext = new Context\ExprAndContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(226);

							    if (!($this->precpred($this->ctx, 7))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 7)");
							    }
							    $this->setState(227);
							    $this->match(self::TKAND);
							    $this->setState(228);
							    $this->recursiveExpresion(8);
							break;

							case 5:
							    $localContext = new Context\ExprOrContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(229);

							    if (!($this->precpred($this->ctx, 6))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 6)");
							    }
							    $this->setState(230);
							    $this->match(self::TKOR);
							    $this->setState(231);
							    $this->recursiveExpresion(7);
							break;
						} 
					}

					$this->setState(236);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 17, $this->ctx);
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
		public function optipo(): Context\OptipoContext
		{
		    $localContext = new Context\OptipoContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 42, self::RULE_optipo);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(237);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 507904) !== 0))) {
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

					case 20:
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
			        return $this->precpred($this->ctx, 10);

			    case 3:
			        return $this->precpred($this->ctx, 9);

			    case 4:
			        return $this->precpred($this->ctx, 8);

			    case 5:
			        return $this->precpred($this->ctx, 7);

			    case 6:
			        return $this->precpred($this->ctx, 6);
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

	    public function optipo(): ?OptipoContext
	    {
	    	return $this->getTypedRuleContext(OptipoContext::class, 0);
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

	    public function listids(): ?ListidsContext
	    {
	    	return $this->getTypedRuleContext(ListidsContext::class, 0);
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

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
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

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
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