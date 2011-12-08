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
$campos['Usuario.id'] = array("value"=>$usuario['Usuario']['id']);
$campos['Usuario.grupos'] = array('type' => 'select', 'multiple' => 'checkbox', "options"=>$grupos);

$fieldsets[] = array('campos' => $campos);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Cambio de grupo para " . $usuario['Usuario']['nombre_completo'] . " (" . $usuario['Usuario']['nombre'] . ")", 'imagen' => 'usuarios.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, "opcionesForm"=>array("action"=>"cambiar_grupo")));
?>