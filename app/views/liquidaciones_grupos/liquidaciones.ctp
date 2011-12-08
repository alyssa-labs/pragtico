<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.views
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 1398 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-07-01 00:09:41 -0300 (Thu, 01 Jul 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Liquidacion'] as $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'imagen' => array('nombre' => 'liquidaciones.gif', 'alt' => 'liquidaciones'), 'url' => '../liquidaciones/recibo_html');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'imagen' => array('nombre' => 'liquidaciones.gif', 'alt' => 'liquidaciones (debug)'), 'url' => '../liquidaciones/recibo_html_debug');
	$fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('excel.gif', array('alt' => 'Generar Recibo para Pre-impreso', 'title' => 'Generar Recibo para Pre-impreso')), array('controller' => 'liquidaciones', 'action' => 'imprimir', 'tipo' => 'preimpreso', 'id' => $v['id'])));
    $fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Recibo para Impresion', 'title' => 'Generar Recibo para Impresion')), array('controller' => 'liquidaciones', 'action' => 'imprimir', 'id' => $v['id'])));
	$fila[] = array('model' => 'Liquidacion', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Liquidacion', 'field' => 'tipo', 'valor' => $v['tipo']);
	$fila[] = array('model' => 'Liquidacion', 'field' => 'fecha', 'valor'=>$v['fecha']);
	$fila[] = array('model' => 'Liquidacion', 'field' => 'ano', 'valor' => $v['ano'] . str_pad($v['mes'], 2, '0' ,STR_PAD_LEFT) . $v['periodo'], 'nombreEncabezado'=>'Periodo');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado'=>'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Trabajador']['nombre'] . ' ' . $v['Trabajador']['apellido'], 'nombreEncabezado'=>'Trabajador');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'remunerativo', 'valor'=>$v['remunerativo'], 'tipoDato' => 'moneda', 'nombreEncabezado' => 'Rem.');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'deduccion', 'valor'=>$v['deduccion'], 'tipoDato' => 'moneda', 'nombreEncabezado' => 'Ded.');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'no_remunerativo', 'valor'=>$v['no_remunerativo'], 'tipoDato' => 'moneda', 'nombreEncabezado' => 'No Rem.');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'total', 'valor' => $v['total'], 'tipoDato' => 'moneda');
	$cuerpo[] = $fila;
}

echo $this->element('desgloses/agregar', array('titulo' => "Liquidaciones", 'cuerpo' => $cuerpo));

?>