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
 * @version			$Revision: 843 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-08-16 21:28:26 -0300 (dom 16 de ago de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Usuario.id'] = array();
$campos['Usuario.nombre'] = array();
if($this->action === 'add') {
	$campos['Usuario.clave'] = array('type' => 'password');
}
$campos['Usuario.nombre_completo'] = array();
$campos['Usuario.email'] = array('label' => 'E-Mail');
$campos['Usuario.estado'] = array();
if($this->action === 'add') {
	$campos['Rol.id'] = array('type' => 'select', 'multiple' => 'checkbox', 'options' => 'listable', 'model' => 'Rol', 'displayField' => array('Rol.nombre'), 'order' => array('Rol.nombre'), 'label' => 'Rol');
	$campos['Grupo.id'] = array('type' => 'select', 'multiple' => 'checkbox', 'options' => 'listable', 'model' => 'Grupo', 'displayField' => array('Grupo.nombre'), 'order' => array('Grupo.nombre'), 'label' => 'Grupo');
}
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div'=>array('class' => 'unica'), 'fieldset'=>array('imagen' => 'usuarios.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = array('format' 	=> '%s (%s)', 
			  'content' => array('Usuario.nombre', 'Usuario.nombre_completo'));
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => $miga));
?>