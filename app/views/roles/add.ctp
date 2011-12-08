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
 * @version			$Revision: 810 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-07-31 15:42:05 -0300 (vie 31 de jul de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Rol.id'] = array();
$campos['Rol.nombre'] = array();
$campos['Rol.prioridad'] = array();
$campos['Rol.estado'] = array();
$campos['Rol.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "Rol", 'imagen' => 'roles.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => 'Rol.nombre'));
?>