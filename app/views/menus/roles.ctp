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
 * @version			$Revision: 652 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-07-03 10:36:22 -0300 (vie 03 de jul de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Rol'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'RolesMenu', 'field' => 'id', 'valor' => $v['RolesMenu']['id'], 'write' => $v['RolesMenu']['write'], 'delete' => $v['RolesMenu']['delete']);
	$fila[] = array('model' => 'Grupo', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Grupo', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'roles_menus', 'action' => 'add', 'RolesMenu.menu_id' => $this->data['Menu']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Roles', 'cuerpo' => $cuerpo));

?>