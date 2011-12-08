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
 * @version			$Revision: 248 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-03 13:42:43 -0200 (mar 03 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Rol-nombre'] = array();
$condiciones['Condicion.RolesMenu-estado'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'roles.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['RolesMenu']['id'], 'imagen' => array('nombre' => 'usuarios.gif', 'alt' => 'Usuarios'), 'url' => 'usuarios');
	$fila[] = array('model' => 'Rol', 'field' => 'id', 'valor' => $v['RolesMenu']['id'], 'write' => $v['RolesMenu']['write'], 'delete' => $v['RolesMenu']['delete']);
	$fila[] = array('model' => 'Rol', 'field' => 'nombre', 'valor' => $v['Rol']['nombre']);
	$fila[] = array('model' => 'RolesMenu', 'field' => 'estado', 'valor' => $v['RolesMenu']['estado']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>