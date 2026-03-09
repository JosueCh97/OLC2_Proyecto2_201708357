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
               T__6 = 7, T__7 = 8, TKINT = 9, TKFLOAT = 10, TKBOOL = 11, 
               TKRUNE = 12, TKSTRING = 13, TKVAR = 14, TKCONST = 15, IGUAL = 16, 
               NIL = 17, TKMAIN = 18, TKFUNC = 19, TKPRINT = 20, TKLEN = 21, 
               TKNOW = 22, TKSUBSTR = 23, TYPEOF = 24, TKIF = 25, TKELSE = 26, 
               TKSWITCH = 27, TKCASE = 28, TKDEFAULT = 29, TKFOR = 30, TKBREAK = 31, 
               TKCONTINUE = 32, TKRETURN = 33, BOOL = 34, STRING = 35, UNICODE = 36, 
               IDNAME = 37, INT = 38, FLOAT = 39, COMENT = 40, MULTILINE_COMMENT = 41, 
               WS = 42;

		public const RULE_inicio = 0, RULE_instrucciones = 1, RULE_instruccion = 2, 
               RULE_listids = 3, RULE_listaexp = 4, RULE_declaracion = 5, 
               RULE_asignacion = 6, RULE_expresion = 7, RULE_optipo = 8;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'inicio', 'instrucciones', 'instruccion', 'listids', 'listaexp', 'declaracion', 
			'asignacion', 'expresion', 'optipo'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "','", "'-'", "'('", "')'", "'*'", "'/'", "'%'", "'+'", "'int32'", 
		    "'float32'", "'bool'", "'rune'", "'string'", "'var'", "'const'", "'='", 
		    "'nil'", "'main'", "'func'", "'fmt.Print'", "'len'", "'now'", "'substr'", 
		    "'typeof'", "'if'", "'else'", "'switch'", "'case'", "'default'", "'for'", 
		    "'break'", "'continue'", "'return'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, null, null, null, null, null, null, null, null, "TKINT", "TKFLOAT", 
		    "TKBOOL", "TKRUNE", "TKSTRING", "TKVAR", "TKCONST", "IGUAL", "NIL", 
		    "TKMAIN", "TKFUNC", "TKPRINT", "TKLEN", "TKNOW", "TKSUBSTR", "TYPEOF", 
		    "TKIF", "TKELSE", "TKSWITCH", "TKCASE", "TKDEFAULT", "TKFOR", "TKBREAK", 
		    "TKCONTINUE", "TKRETURN", "BOOL", "STRING", "UNICODE", "IDNAME", "INT", 
		    "FLOAT", "COMENT", "MULTILINE_COMMENT", "WS"
		];

		private const SERIALIZED_ATN =
			[4, 1, 42, 102, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 2, 4, 
		    7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 1, 0, 1, 0, 
		    1, 0, 1, 1, 4, 1, 23, 8, 1, 11, 1, 12, 1, 24, 1, 2, 1, 2, 3, 2, 29, 
		    8, 2, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 5, 3, 37, 8, 3, 10, 3, 12, 
		    3, 40, 9, 3, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 5, 4, 48, 8, 4, 10, 
		    4, 12, 4, 51, 9, 4, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 
		    1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 3, 5, 69, 8, 5, 1, 
		    6, 1, 6, 1, 6, 1, 6, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 7, 1, 
		    7, 1, 7, 1, 7, 1, 7, 1, 7, 3, 7, 87, 8, 7, 1, 7, 1, 7, 1, 7, 1, 7, 
		    1, 7, 1, 7, 5, 7, 95, 8, 7, 10, 7, 12, 7, 98, 9, 7, 1, 8, 1, 8, 1, 
		    8, 0, 3, 6, 8, 14, 9, 0, 2, 4, 6, 8, 10, 12, 14, 16, 0, 3, 1, 0, 5, 
		    7, 2, 0, 2, 2, 8, 8, 1, 0, 9, 13, 106, 0, 18, 1, 0, 0, 0, 2, 22, 1, 
		    0, 0, 0, 4, 28, 1, 0, 0, 0, 6, 30, 1, 0, 0, 0, 8, 41, 1, 0, 0, 0, 
		    10, 68, 1, 0, 0, 0, 12, 70, 1, 0, 0, 0, 14, 86, 1, 0, 0, 0, 16, 99, 
		    1, 0, 0, 0, 18, 19, 3, 2, 1, 0, 19, 20, 5, 0, 0, 1, 20, 1, 1, 0, 0, 
		    0, 21, 23, 3, 4, 2, 0, 22, 21, 1, 0, 0, 0, 23, 24, 1, 0, 0, 0, 24, 
		    22, 1, 0, 0, 0, 24, 25, 1, 0, 0, 0, 25, 3, 1, 0, 0, 0, 26, 29, 3, 
		    10, 5, 0, 27, 29, 3, 12, 6, 0, 28, 26, 1, 0, 0, 0, 28, 27, 1, 0, 0, 
		    0, 29, 5, 1, 0, 0, 0, 30, 31, 6, 3, -1, 0, 31, 32, 5, 37, 0, 0, 32, 
		    38, 1, 0, 0, 0, 33, 34, 10, 1, 0, 0, 34, 35, 5, 1, 0, 0, 35, 37, 5, 
		    37, 0, 0, 36, 33, 1, 0, 0, 0, 37, 40, 1, 0, 0, 0, 38, 36, 1, 0, 0, 
		    0, 38, 39, 1, 0, 0, 0, 39, 7, 1, 0, 0, 0, 40, 38, 1, 0, 0, 0, 41, 
		    42, 6, 4, -1, 0, 42, 43, 3, 14, 7, 0, 43, 49, 1, 0, 0, 0, 44, 45, 
		    10, 1, 0, 0, 45, 46, 5, 1, 0, 0, 46, 48, 3, 14, 7, 0, 47, 44, 1, 0, 
		    0, 0, 48, 51, 1, 0, 0, 0, 49, 47, 1, 0, 0, 0, 49, 50, 1, 0, 0, 0, 
		    50, 9, 1, 0, 0, 0, 51, 49, 1, 0, 0, 0, 52, 53, 5, 14, 0, 0, 53, 54, 
		    3, 6, 3, 0, 54, 55, 3, 16, 8, 0, 55, 69, 1, 0, 0, 0, 56, 57, 5, 14, 
		    0, 0, 57, 58, 3, 6, 3, 0, 58, 59, 3, 16, 8, 0, 59, 60, 5, 16, 0, 0, 
		    60, 61, 3, 8, 4, 0, 61, 69, 1, 0, 0, 0, 62, 63, 5, 15, 0, 0, 63, 64, 
		    3, 6, 3, 0, 64, 65, 3, 16, 8, 0, 65, 66, 5, 16, 0, 0, 66, 67, 3, 8, 
		    4, 0, 67, 69, 1, 0, 0, 0, 68, 52, 1, 0, 0, 0, 68, 56, 1, 0, 0, 0, 
		    68, 62, 1, 0, 0, 0, 69, 11, 1, 0, 0, 0, 70, 71, 3, 6, 3, 0, 71, 72, 
		    5, 16, 0, 0, 72, 73, 3, 8, 4, 0, 73, 13, 1, 0, 0, 0, 74, 75, 6, 7, 
		    -1, 0, 75, 76, 5, 2, 0, 0, 76, 87, 3, 14, 7, 9, 77, 78, 5, 3, 0, 0, 
		    78, 79, 3, 14, 7, 0, 79, 80, 5, 4, 0, 0, 80, 87, 1, 0, 0, 0, 81, 87, 
		    5, 38, 0, 0, 82, 87, 5, 39, 0, 0, 83, 87, 5, 34, 0, 0, 84, 87, 5, 
		    35, 0, 0, 85, 87, 5, 37, 0, 0, 86, 74, 1, 0, 0, 0, 86, 77, 1, 0, 0, 
		    0, 86, 81, 1, 0, 0, 0, 86, 82, 1, 0, 0, 0, 86, 83, 1, 0, 0, 0, 86, 
		    84, 1, 0, 0, 0, 86, 85, 1, 0, 0, 0, 87, 96, 1, 0, 0, 0, 88, 89, 10, 
		    7, 0, 0, 89, 90, 7, 0, 0, 0, 90, 95, 3, 14, 7, 8, 91, 92, 10, 6, 0, 
		    0, 92, 93, 7, 1, 0, 0, 93, 95, 3, 14, 7, 7, 94, 88, 1, 0, 0, 0, 94, 
		    91, 1, 0, 0, 0, 95, 98, 1, 0, 0, 0, 96, 94, 1, 0, 0, 0, 96, 97, 1, 
		    0, 0, 0, 97, 15, 1, 0, 0, 0, 98, 96, 1, 0, 0, 0, 99, 100, 7, 2, 0, 
		    0, 100, 17, 1, 0, 0, 0, 8, 24, 28, 38, 49, 68, 86, 94, 96];
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
		        $this->setState(18);
		        $this->instrucciones();
		        $this->setState(19);
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
		        $this->setState(22); 
		        $this->errorHandler->sync($this);

		        $_la = $this->input->LA(1);
		        do {
		        	$this->setState(21);
		        	$this->instruccion();
		        	$this->setState(24); 
		        	$this->errorHandler->sync($this);
		        	$_la = $this->input->LA(1);
		        } while (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 137439002624) !== 0));
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
		        $this->setState(28);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::TKVAR:
		            case self::TKCONST:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(26);
		            	$this->declaracion();
		            	break;

		            case self::IDNAME:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(27);
		            	$this->asignacion();
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
			$startState = 6;
			$this->enterRecursionRule($localContext, 6, self::RULE_listids, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(31);
				$this->match(self::IDNAME);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(38);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 2, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\ListidsContext($parentContext, $parentState);
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_listids);
						$this->setState(33);

						if (!($this->precpred($this->ctx, 1))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
						}
						$this->setState(34);
						$this->match(self::T__0);
						$this->setState(35);
						$this->match(self::IDNAME); 
					}

					$this->setState(40);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 2, $this->ctx);
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
			$startState = 8;
			$this->enterRecursionRule($localContext, 8, self::RULE_listaexp, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(42);
				$this->recursiveExpresion(0);
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(49);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 3, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$localContext = new Context\ListaexpContext($parentContext, $parentState);
						$this->pushNewRecursionContext($localContext, $startState, self::RULE_listaexp);
						$this->setState(44);

						if (!($this->precpred($this->ctx, 1))) {
						    throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
						}
						$this->setState(45);
						$this->match(self::T__0);
						$this->setState(46);
						$this->recursiveExpresion(0); 
					}

					$this->setState(51);
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
		public function declaracion(): Context\DeclaracionContext
		{
		    $localContext = new Context\DeclaracionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 10, self::RULE_declaracion);

		    try {
		        $this->setState(68);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 4, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(52);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(53);
		        	    $this->recursiveListids(0);
		        	    $this->setState(54);
		        	    $this->optipo();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(56);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(57);
		        	    $this->recursiveListids(0);
		        	    $this->setState(58);
		        	    $this->optipo();
		        	    $this->setState(59);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(60);
		        	    $this->recursiveListaexp(0);
		        	break;

		        	case 3:
		        	    $this->enterOuterAlt($localContext, 3);
		        	    $this->setState(62);
		        	    $this->match(self::TKCONST);
		        	    $this->setState(63);
		        	    $this->recursiveListids(0);
		        	    $this->setState(64);
		        	    $this->optipo();
		        	    $this->setState(65);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(66);
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

		    $this->enterRule($localContext, 12, self::RULE_asignacion);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(70);
		        $this->recursiveListids(0);
		        $this->setState(71);
		        $this->match(self::IGUAL);
		        $this->setState(72);
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
			$startState = 14;
			$this->enterRecursionRule($localContext, 14, self::RULE_expresion, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(86);
				$this->errorHandler->sync($this);

				switch ($this->input->LA(1)) {
				    case self::T__1:
				    	$localContext = new Context\ExprUnariaContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;

				    	$this->setState(75);
				    	$this->match(self::T__1);
				    	$this->setState(76);
				    	$this->recursiveExpresion(9);
				    	break;

				    case self::T__2:
				    	$localContext = new Context\ExprAgrupacionContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(77);
				    	$this->match(self::T__2);
				    	$this->setState(78);
				    	$this->recursiveExpresion(0);
				    	$this->setState(79);
				    	$this->match(self::T__3);
				    	break;

				    case self::INT:
				    	$localContext = new Context\ExprEnteroContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(81);
				    	$this->match(self::INT);
				    	break;

				    case self::FLOAT:
				    	$localContext = new Context\ExprDecimalContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(82);
				    	$this->match(self::FLOAT);
				    	break;

				    case self::BOOL:
				    	$localContext = new Context\ExprBooleanoContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(83);
				    	$this->match(self::BOOL);
				    	break;

				    case self::STRING:
				    	$localContext = new Context\ExprCadenaContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(84);
				    	$this->match(self::STRING);
				    	break;

				    case self::IDNAME:
				    	$localContext = new Context\ExprIdentificadorContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(85);
				    	$this->match(self::IDNAME);
				    	break;

				default:
					throw new NoViableAltException($this);
				}
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(96);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$this->setState(94);
						$this->errorHandler->sync($this);

						switch ($this->getInterpreter()->adaptivePredict($this->input, 6, $this->ctx)) {
							case 1:
							    $localContext = new Context\ExprMultiplicacionContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(88);

							    if (!($this->precpred($this->ctx, 7))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 7)");
							    }
							    $this->setState(89);

							    $_la = $this->input->LA(1);

							    if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 224) !== 0))) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(90);
							    $this->recursiveExpresion(8);
							break;

							case 2:
							    $localContext = new Context\ExprSumaContext(new Context\ExpresionContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expresion);
							    $this->setState(91);

							    if (!($this->precpred($this->ctx, 6))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 6)");
							    }
							    $this->setState(92);

							    $_la = $this->input->LA(1);

							    if (!($_la === self::T__1 || $_la === self::T__7)) {
							    $this->errorHandler->recoverInline($this);
							    } else {
							    	if ($this->input->LA(1) === Token::EOF) {
							    	    $this->matchedEOF = true;
							        }

							    	$this->errorHandler->reportMatch($this);
							    	$this->consume();
							    }
							    $this->setState(93);
							    $this->recursiveExpresion(7);
							break;
						} 
					}

					$this->setState(98);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);
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

		    $this->enterRule($localContext, 16, self::RULE_optipo);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(99);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 15872) !== 0))) {
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
					case 3:
						return $this->sempredListids($localContext, $predicateIndex);

					case 4:
						return $this->sempredListaexp($localContext, $predicateIndex);

					case 7:
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
			        return $this->precpred($this->ctx, 7);

			    case 3:
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

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitInstruccion($this);
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