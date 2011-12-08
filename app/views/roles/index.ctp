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
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Rol-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Roles", 'imagen' => 'roles.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Rol']['id'], 'imagen' => array('nombre' => 'usuarios.gif', 'alt' => "Usuarios"), 'url' => 'usuarios');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Rol']['id'], 'imagen' => array('nombre' => 'menus.gif', 'alt' => "Menus"), 'url' => 'menus');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Rol']['id'], 'imagen' => array('nombre' => 'acciones.gif', 'alt' => "Acciones"), 'url' => 'acciones');
	$fila[] = array('model' => 'Rol', 'field' => 'id', 'valor' => $v['Rol']['id'], 'write' => $v['Rol']['write'], 'delete' => $v['Rol']['delete']);
	$fila[] = array('model' => 'Rol', 'field' => 'nombre', 'valor' => $v['Rol']['nombre']);
    $fila[] = array('model' => 'Rol', 'field' => 'prioridad', 'valor' => $v['Rol']['prioridad']);
	$fila[] = array('model' => 'Rol', 'field' => 'estado', 'valor' => $v['Rol']['estado']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>