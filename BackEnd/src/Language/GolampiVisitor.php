<?php

/*
 * Generated from Golampi.g4 by ANTLR 4.13.1
 */

namespace App\Language;

use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced by {@see GolampiParser}.
 */
interface GolampiVisitor extends ParseTreeVisitor
{
	/**
	 * Visit a parse tree produced by {@see GolampiParser::inicio()}.
	 *
	 * @param Context\InicioContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitInicio(Context\InicioContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::instrucciones()}.
	 *
	 * @param Context\InstruccionesContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitInstrucciones(Context\InstruccionesContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::instruccion()}.
	 *
	 * @param Context\InstruccionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitInstruccion(Context\InstruccionContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::bloque()}.
	 *
	 * @param Context\BloqueContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBloque(Context\BloqueContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::listids()}.
	 *
	 * @param Context\ListidsContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitListids(Context\ListidsContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::listaexp()}.
	 *
	 * @param Context\ListaexpContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitListaexp(Context\ListaexpContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::declaracion()}.
	 *
	 * @param Context\DeclaracionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitDeclaracion(Context\DeclaracionContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::asignacion()}.
	 *
	 * @param Context\AsignacionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAsignacion(Context\AsignacionContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::asig_compuesta()}.
	 *
	 * @param Context\Asig_compuestaContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAsig_compuesta(Context\Asig_compuestaContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::inc_dec()}.
	 *
	 * @param Context\Inc_decContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitInc_dec(Context\Inc_decContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::si_stmt()}.
	 *
	 * @param Context\Si_stmtContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitSi_stmt(Context\Si_stmtContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::imprimir()}.
	 *
	 * @param Context\ImprimirContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitImprimir(Context\ImprimirContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::switch()}.
	 *
	 * @param Context\SwitchContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitSwitch(Context\SwitchContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::case()}.
	 *
	 * @param Context\CaseContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitCase(Context\CaseContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::default()}.
	 *
	 * @param Context\DefaultContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitDefault(Context\DefaultContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::init()}.
	 *
	 * @param Context\InitContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitInit(Context\InitContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::post()}.
	 *
	 * @param Context\PostContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPost(Context\PostContext $context);

	/**
	 * Visit a parse tree produced by the `ForInfinito` labeled alternative
	 * in {@see GolampiParser::for()}.
	 *
	 * @param Context\ForInfinitoContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitForInfinito(Context\ForInfinitoContext $context);

	/**
	 * Visit a parse tree produced by the `ForMientras` labeled alternative
	 * in {@see GolampiParser::for()}.
	 *
	 * @param Context\ForMientrasContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitForMientras(Context\ForMientrasContext $context);

	/**
	 * Visit a parse tree produced by the `ForClasico` labeled alternative
	 * in {@see GolampiParser::for()}.
	 *
	 * @param Context\ForClasicoContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitForClasico(Context\ForClasicoContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::break()}.
	 *
	 * @param Context\BreakContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBreak(Context\BreakContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::continue()}.
	 *
	 * @param Context\ContinueContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitContinue(Context\ContinueContext $context);

	/**
	 * Visit a parse tree produced by the `ExprAgrupacion` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprAgrupacionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprAgrupacion(Context\ExprAgrupacionContext $context);

	/**
	 * Visit a parse tree produced by the `ExprEntero` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprEnteroContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprEntero(Context\ExprEnteroContext $context);

	/**
	 * Visit a parse tree produced by the `ExprBooleano` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprBooleanoContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprBooleano(Context\ExprBooleanoContext $context);

	/**
	 * Visit a parse tree produced by the `ExprSuma` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprSumaContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprSuma(Context\ExprSumaContext $context);

	/**
	 * Visit a parse tree produced by the `ExprCadena` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprCadenaContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprCadena(Context\ExprCadenaContext $context);

	/**
	 * Visit a parse tree produced by the `ExprIdentificador` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprIdentificadorContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprIdentificador(Context\ExprIdentificadorContext $context);

	/**
	 * Visit a parse tree produced by the `ExprUnaria` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprUnariaContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprUnaria(Context\ExprUnariaContext $context);

	/**
	 * Visit a parse tree produced by the `ExprNot` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprNotContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprNot(Context\ExprNotContext $context);

	/**
	 * Visit a parse tree produced by the `ExprAnd` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprAndContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprAnd(Context\ExprAndContext $context);

	/**
	 * Visit a parse tree produced by the `ExprDecimal` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprDecimalContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprDecimal(Context\ExprDecimalContext $context);

	/**
	 * Visit a parse tree produced by the `ExprOr` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprOrContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprOr(Context\ExprOrContext $context);

	/**
	 * Visit a parse tree produced by the `ExprMultiplicacion` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprMultiplicacionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprMultiplicacion(Context\ExprMultiplicacionContext $context);

	/**
	 * Visit a parse tree produced by the `ExprComparacion` labeled alternative
	 * in {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExprComparacionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExprComparacion(Context\ExprComparacionContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::optipo()}.
	 *
	 * @param Context\OptipoContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitOptipo(Context\OptipoContext $context);
}