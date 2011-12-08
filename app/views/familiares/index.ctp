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
 * @version			$Revision: 1032 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-29 19:51:14 -0300 (Tue, 29 Sep 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Familiar-apellido'] = array();
$condiciones['Condicion.Familiar-nombre'] = array();
$condiciones['Condicion.Familiar-parentezco'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Familiar-numero_documento'] = array('label' => 'Documento');
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'familiares.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Familiar', 'field' => 'id', 'valor' => $v['Familiar']['id'], 'write' => $v['Familiar']['write'], 'delete' => $v['Familiar']['delete']);
	$fila[] = array('model' => 'Familiar', 'field' => 'parentezco', 'valor' => $v['Familiar']['parentezco']);
	$fila[] = array('model' => 'Familiar', 'field' => 'apellido', 'valor' => $v['Familiar']['apellido']);
	$fila[] = array('model' => 'Familiar', 'field' => 'nombre', 'valor' => $v['Familiar']['nombre']);
	$fila[] = array('model' => 'Familiar', 'field' => 'telefono', 'valor' => $v['Familiar']['telefono']);
	$fila[] = array('model' => 'Familiar', 'field' => 'celular', 'valor' => $v['Familiar']['celular']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>