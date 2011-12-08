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
 * @version			$Revision: 332 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-25 16:33:58 -0200 (Wed, 25 Feb 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.EmployersType-code'] = array();
$condiciones['Condicion.EmployersType-name'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'EmployersTypes.gif')));

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'EmployersType', 'field' => 'id', 'valor' => $v['EmployersType']['id'], 'write' => $v['EmployersType']['write'], 'delete' => $v['EmployersType']['delete']);
	$fila[] = array('model' => 'EmployersType', 'field' => 'code', 'valor' => $v['EmployersType']['code']);
	$fila[] = array('model' => 'EmployersType', 'field' => 'name', 'valor' => $v['EmployersType']['name']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));
?>