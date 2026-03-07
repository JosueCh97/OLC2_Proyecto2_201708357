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
		public const T__0 = 1, T__1 = 2, TKINT = 3, TKFLOAT = 4, TKBOOL = 5, TKRUNE = 6, 
               TKSTRING = 7, TKVAR = 8, IGUAL = 9, TKMAIN = 10, TKFUNC = 11, 
               TKPRINT = 12, TKLEN = 13, TKNOW = 14, TKSUBSTR = 15, TYPEOF = 16, 
               TKIF = 17, TKELSE = 18, TKSWITCH = 19, TKCASE = 20, TKDEFAULT = 21, 
               TKFOR = 22, TKBREAK = 23, TKCONTINUE = 24, TKRETURN = 25, 
               BOOL = 26, IDNAME = 27, INT = 28, FLOAT = 29, COMENT = 30, 
               MULTILINE_COMMENT = 31, WS = 32;

		public const RULE_inicio = 0, RULE_instrucciones = 1, RULE_instruccion = 2, 
               RULE_listids = 3, RULE_listaexp = 4, RULE_declaracion = 5, 
               RULE_asignacion = 6, RULE_expresion = 7, RULE_primitivos = 8;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'inicio', 'instrucciones', 'instruccion', 'listids', 'listaexp', 'declaracion', 
			'asignacion', 'expresion', 'primitivos'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "','", "':='", "'int32'", "'float32'", "'bool'", "'rune'", "'string'", 
		    "'var'", "'='", "'main'", "'func'", "'fmt.Print'", "'len'", "'now'", 
		    "'substr'", "'typeof'", "'if'", "'else'", "'switch'", "'case'", "'default'", 
		    "'for'", "'break'", "'continue'", "'return'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, null, null, "TKINT", "TKFLOAT", "TKBOOL", "TKRUNE", "TKSTRING", 
		    "TKVAR", "IGUAL", "TKMAIN", "TKFUNC", "TKPRINT", "TKLEN", "TKNOW", 
		    "TKSUBSTR", "TYPEOF", "TKIF", "TKELSE", "TKSWITCH", "TKCASE", "TKDEFAULT", 
		    "TKFOR", "TKBREAK", "TKCONTINUE", "TKRETURN", "BOOL", "IDNAME", "INT", 
		    "FLOAT", "COMENT", "MULTILINE_COMMENT", "WS"
		];

		private const SERIALIZED_ATN =
			[4, 1, 32, 80, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 2, 4, 
		    7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 1, 0, 1, 0, 
		    1, 0, 1, 1, 4, 1, 23, 8, 1, 11, 1, 12, 1, 24, 1, 2, 1, 2, 3, 2, 29, 
		    8, 2, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 1, 3, 5, 3, 37, 8, 3, 10, 3, 12, 
		    3, 40, 9, 3, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 1, 4, 5, 4, 48, 8, 4, 10, 
		    4, 12, 4, 51, 9, 4, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 
		    1, 5, 1, 5, 3, 5, 63, 8, 5, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 
		    6, 3, 6, 72, 8, 6, 1, 7, 1, 7, 3, 7, 76, 8, 7, 1, 8, 1, 8, 1, 8, 0, 
		    2, 6, 8, 9, 0, 2, 4, 6, 8, 10, 12, 14, 16, 0, 1, 1, 0, 3, 7, 77, 0, 
		    18, 1, 0, 0, 0, 2, 22, 1, 0, 0, 0, 4, 28, 1, 0, 0, 0, 6, 30, 1, 0, 
		    0, 0, 8, 41, 1, 0, 0, 0, 10, 62, 1, 0, 0, 0, 12, 71, 1, 0, 0, 0, 14, 
		    75, 1, 0, 0, 0, 16, 77, 1, 0, 0, 0, 18, 19, 3, 2, 1, 0, 19, 20, 5, 
		    0, 0, 1, 20, 1, 1, 0, 0, 0, 21, 23, 3, 4, 2, 0, 22, 21, 1, 0, 0, 0, 
		    23, 24, 1, 0, 0, 0, 24, 22, 1, 0, 0, 0, 24, 25, 1, 0, 0, 0, 25, 3, 
		    1, 0, 0, 0, 26, 29, 3, 10, 5, 0, 27, 29, 3, 12, 6, 0, 28, 26, 1, 0, 
		    0, 0, 28, 27, 1, 0, 0, 0, 29, 5, 1, 0, 0, 0, 30, 31, 6, 3, -1, 0, 
		    31, 32, 5, 27, 0, 0, 32, 38, 1, 0, 0, 0, 33, 34, 10, 1, 0, 0, 34, 
		    35, 5, 1, 0, 0, 35, 37, 5, 27, 0, 0, 36, 33, 1, 0, 0, 0, 37, 40, 1, 
		    0, 0, 0, 38, 36, 1, 0, 0, 0, 38, 39, 1, 0, 0, 0, 39, 7, 1, 0, 0, 0, 
		    40, 38, 1, 0, 0, 0, 41, 42, 6, 4, -1, 0, 42, 43, 3, 14, 7, 0, 43, 
		    49, 1, 0, 0, 0, 44, 45, 10, 1, 0, 0, 45, 46, 5, 1, 0, 0, 46, 48, 3, 
		    14, 7, 0, 47, 44, 1, 0, 0, 0, 48, 51, 1, 0, 0, 0, 49, 47, 1, 0, 0, 
		    0, 49, 50, 1, 0, 0, 0, 50, 9, 1, 0, 0, 0, 51, 49, 1, 0, 0, 0, 52, 
		    53, 5, 8, 0, 0, 53, 54, 3, 6, 3, 0, 54, 55, 3, 16, 8, 0, 55, 63, 1, 
		    0, 0, 0, 56, 57, 5, 8, 0, 0, 57, 58, 3, 6, 3, 0, 58, 59, 3, 16, 8, 
		    0, 59, 60, 5, 9, 0, 0, 60, 61, 3, 8, 4, 0, 61, 63, 1, 0, 0, 0, 62, 
		    52, 1, 0, 0, 0, 62, 56, 1, 0, 0, 0, 63, 11, 1, 0, 0, 0, 64, 65, 3, 
		    6, 3, 0, 65, 66, 5, 2, 0, 0, 66, 67, 3, 14, 7, 0, 67, 72, 1, 0, 0, 
		    0, 68, 69, 5, 27, 0, 0, 69, 70, 5, 9, 0, 0, 70, 72, 3, 14, 7, 0, 71, 
		    64, 1, 0, 0, 0, 71, 68, 1, 0, 0, 0, 72, 13, 1, 0, 0, 0, 73, 76, 3, 
		    16, 8, 0, 74, 76, 5, 28, 0, 0, 75, 73, 1, 0, 0, 0, 75, 74, 1, 0, 0, 
		    0, 76, 15, 1, 0, 0, 0, 77, 78, 7, 0, 0, 0, 78, 17, 1, 0, 0, 0, 7, 
		    24, 28, 38, 49, 62, 71, 75];
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
		        } while ($_la === self::TKVAR || $_la === self::IDNAME);
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
				$this->expresion();
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
						$this->expresion(); 
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
		        $this->setState(62);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 4, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(52);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(53);
		        	    $this->recursiveListids(0);
		        	    $this->setState(54);
		        	    $this->primitivos();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(56);
		        	    $this->match(self::TKVAR);
		        	    $this->setState(57);
		        	    $this->recursiveListids(0);
		        	    $this->setState(58);
		        	    $this->primitivos();
		        	    $this->setState(59);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(60);
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
		        $this->setState(71);
		        $this->errorHandler->sync($this);

		        switch ($this->getInterpreter()->adaptivePredict($this->input, 5, $this->ctx)) {
		        	case 1:
		        	    $this->enterOuterAlt($localContext, 1);
		        	    $this->setState(64);
		        	    $this->recursiveListids(0);
		        	    $this->setState(65);
		        	    $this->match(self::T__1);
		        	    $this->setState(66);
		        	    $this->expresion();
		        	break;

		        	case 2:
		        	    $this->enterOuterAlt($localContext, 2);
		        	    $this->setState(68);
		        	    $this->match(self::IDNAME);
		        	    $this->setState(69);
		        	    $this->match(self::IGUAL);
		        	    $this->setState(70);
		        	    $this->expresion();
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
		public function expresion(): Context\ExpresionContext
		{
		    $localContext = new Context\ExpresionContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 14, self::RULE_expresion);

		    try {
		        $this->setState(75);
		        $this->errorHandler->sync($this);

		        switch ($this->input->LA(1)) {
		            case self::TKINT:
		            case self::TKFLOAT:
		            case self::TKBOOL:
		            case self::TKRUNE:
		            case self::TKSTRING:
		            	$this->enterOuterAlt($localContext, 1);
		            	$this->setState(73);
		            	$this->primitivos();
		            	break;

		            case self::INT:
		            	$this->enterOuterAlt($localContext, 2);
		            	$this->setState(74);
		            	$this->match(self::INT);
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
		public function primitivos(): Context\PrimitivosContext
		{
		    $localContext = new Context\PrimitivosContext($this->ctx, $this->getState());

		    $this->enterRule($localContext, 16, self::RULE_primitivos);

		    try {
		        $this->enterOuterAlt($localContext, 1);
		        $this->setState(77);

		        $_la = $this->input->LA(1);

		        if (!(((($_la) & ~0x3f) === 0 && ((1 << $_la) & 248) !== 0))) {
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

	    public function primitivos(): ?PrimitivosContext
	    {
	    	return $this->getTypedRuleContext(PrimitivosContext::class, 0);
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

	    public function expresion(): ?ExpresionContext
	    {
	    	return $this->getTypedRuleContext(ExpresionContext::class, 0);
	    }

	    public function IDNAME(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IDNAME, 0);
	    }

	    public function IGUAL(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::IGUAL, 0);
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

	    public function primitivos(): ?PrimitivosContext
	    {
	    	return $this->getTypedRuleContext(PrimitivosContext::class, 0);
	    }

	    public function INT(): ?TerminalNode
	    {
	        return $this->getToken(GolampiParser::INT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof GolampiVisitor) {
			    return $visitor->visitExpresion($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 

	class PrimitivosContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return GolampiParser::RULE_primitivos;
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
			    return $visitor->visitPrimitivos($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 
}