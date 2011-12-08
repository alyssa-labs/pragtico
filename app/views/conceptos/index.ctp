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
 * @version			$Revision: 1345 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-04 16:17:50 -0300 (vie 04 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Concepto-codigo'] = array();
$condiciones['Condicion.Concepto-nombre'] = array();
$condiciones['Condicion.Concepto-tipo'] = array();
$condiciones['Condicion.Concepto-formula'] = array();
$condiciones['Condicion.Concepto-coeficiente_id'] = array(	'lov'=>array('controller'	=>	'coeficientes',
																				'camposRetorno'	=>array('Coeficiente.nombre',
																										'Coeficiente.tipo')));
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'conceptos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Concepto']['id'], 'imagen' => array('nombre' => 'convenios.gif', 'alt' => 'Convenios'), 'url' => 'convenios');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Concepto']['id'], 'imagen' => array('nombre' => 'empleadores.gif', 'alt' => 'Empleadores asociados al Concepto'), 'url' => 'empleadores');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Concepto']['id'], 'imagen' => array('nombre' => 'relaciones.gif', 'alt' => 'Relaciones asociadas al Concepto'), 'url' => 'relaciones');
	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('asignar.gif', array('alt' => 'Asignar este concepto a todos los Trabajadores', 'title' => 'Asignar este concepto a todos los Trabajadores')), array('action' => 'manipular_concepto', 'agregar', 'concepto_id' => $v['Concepto']['id'])));
	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('quitar.gif', array('alt' => 'Quitara este concepto de todos los Trabajadores', 'title' => 'Quitara este concepto de todos los Trabajadores')), array('action' => 'manipular_concepto', 'quitar', 'concepto_id' => $v['Concepto']['id'])));
	$fila[] = array('model' => 'Concepto', 'field' => 'id', 'valor' => $v['Concepto']['id'], 'write' => $v['Concepto']['write'], 'delete' => $v['Concepto']['delete']);
	$fila[] = array('model' => 'Concepto', 'field' => 'codigo', 'valor' => $v['Concepto']['codigo']);
	$fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'valor' => $v['Concepto']['nombre']);
	$fila[] = array('model' => 'Concepto', 'field' => 'desde', 'valor' => $v['Concepto']['desde']);
	$fila[] = array('model' => 'Concepto', 'field' => 'hasta', 'valor' => $v['Concepto']['hasta']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>