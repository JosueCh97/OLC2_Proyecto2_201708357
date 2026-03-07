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
	 * Visit a parse tree produced by {@see GolampiParser::expresion()}.
	 *
	 * @param Context\ExpresionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitExpresion(Context\ExpresionContext $context);

	/**
	 * Visit a parse tree produced by {@see GolampiParser::primitivos()}.
	 *
	 * @param Context\PrimitivosContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitPrimitivos(Context\PrimitivosContext $context);
}