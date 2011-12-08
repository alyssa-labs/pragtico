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
 * @version			$Revision: 236 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar 27 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Menu.id'] = array();
$campos['Menu.nombre'] = array();
$campos['Menu.etiqueta'] = array();
$campos['Menu.ayuda'] = array();
$campos['Menu.imagen'] = array();
$campos['Menu.orden'] = array();
$campos['Menu.controller'] = array();
$campos['Menu.action'] = array();
$campos['Menu.parent_id'] = array('options' => 'listable', "displayField"=>array("Menu.etiqueta"), "conditions"=>array("Menu.parent_id is null"), "order"=>array("Menu.nombre"), "empty"=>true, "label"=>"Padre");
$campos['Menu.estado'] = array();
$fieldsets[] = array('campos' => $campos);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'menus.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => 'Menu.nombre'));
?>