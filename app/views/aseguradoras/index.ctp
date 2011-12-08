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
 * @version			$Revision: 562 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-05-31 21:28:09 -0300 (dom 31 de may de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Aseguradora-codigo'] = array();
$condiciones['Condicion.Aseguradora-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'aseguradoras.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Aseguradora', 'field' => 'id', 'valor' => $v['Aseguradora']['id'], 'write' => $v['Aseguradora']['write'], 'delete' => $v['Aseguradora']['delete']);
	$fila[] = array('model' => 'Aseguradora', 'field' => 'codigo', 'valor' => $v['Aseguradora']['codigo']);
	$fila[] = array('model' => 'Aseguradora', 'field' => 'nombre', 'valor' => $v['Aseguradora']['nombre']);
	$fila[] = array('model' => 'Aseguradora', 'field' => 'observacion', 'valor' => $v['Aseguradora']['observacion']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>