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
if(!isset($noVerificar)) {
	$campos = null;
	$campos['Usuario.clave_anterior'] = array("type"=>"password", "label"=>"Clave Actual");
	$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array("class"=>"subset"), 'fieldset' => array('legend' => "Actual", 'imagen' => 'cambiar_clave.gif')));
}

$campos = null;
$campos['Usuario.id'] = array("value"=>$usuario['Usuario']['id']);
$campos['Usuario.clave_nueva'] = array("type"=>"password", "label"=>"Nueva Clave");
$campos['Usuario.clave_nueva_reingreso'] = array("type"=>"password", "label"=>"Reingrese");
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array("class"=>"subset"), 'fieldset' => array('legend' => "Nueva", 'imagen' => 'cambiar_clave.gif')));

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "Cambio de clave para " . $usuario['Usuario']['nombre_completo'] . " (" . $usuario['Usuario']['nombre'] . ")", 'imagen' => 'usuarios.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, "opcionesForm"=>array("action"=>"cambiar_clave")));
?>