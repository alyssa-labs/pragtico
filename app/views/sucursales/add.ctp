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
 * @version			$Revision: 293 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-19 12:21:14 -0200 (jue 19 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Sucursal.id'] = array();
$campos['Sucursal.banco_id'] = array('options' => 'listable', "displayField"=>array("Banco.nombre"), "model"=>"Banco");
$campos['Sucursal.codigo'] = array();
$campos['Sucursal.direccion'] = array();
$campos['Sucursal.barrio'] = array();
$campos['Sucursal.localidad'] = array();
$campos['Sucursal.provincia_id'] = array('options' => 'listable', 'model' => 'Provincia', 'displayField' => array('Provincia.nombre'));
$campos['Sucursal.telefono'] = array();
$campos['Sucursal.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'sucursales.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = 'Sucursal.codigo';
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => $miga));
?>