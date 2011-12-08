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
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Menu'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'RolesMenu', 'field' => 'id', 'valor' => $v['RolesMenu']['id'], 'write' => $v['RolesMenu']['write'], 'delete' => $v['RolesMenu']['delete']);
	$fila[] = array('model' => 'Menu', 'field' => 'etiqueta', 'valor' => $v['etiqueta']);
	$fila[] = array('model' => 'Menu', 'field' => 'orden', 'valor' => $v['orden']);
	$fila[] = array('model' => 'RolesMenu', 'field' => 'estado', 'valor' => $v['RolesMenu']['estado']);
 	$fila[] = array('model' => 'Menu', 'field' => 'controller', 'valor' => $v['controller']);
 	$fila[] = array('model' => 'Menu', 'field' => 'action', 'valor' => $v['action']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "roles_menus", 'action' => 'add', "RolesMenu.rol_id"=>$this->data['Rol']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Menus", 'cuerpo' => $cuerpo));

?>