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
$campos['RolesAccion.rol_id'] = array("value"=>$rolId, "type"=>"hidden");

foreach($data as $k=>$v) {
	$options = $value = null;
	foreach($v as $k1=>$v1) {
		if($v1 === true) {
			$value[] = $k1;
		}
		$options[$k1] = $dataAcciones[$k1];
	}
	//debug($value);
	$campos['RolesAccion.controlador_' . $k] = array("value"=>$value, "label" => $dataControladores[$k], "options"=>$options, 'type' => 'select', 'multiple' => 'checkbox');
}
$campos['Form.tipo'] = array("value"=>"addRapido", "type"=>"hidden");
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "Accion", 'imagen' => 'acciones.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>