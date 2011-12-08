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
foreach ($this->data['Accion'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'RolesAccion', 'field' => 'id', 'valor' => $v['RolesAccion']['id'], 'write' => $v['RolesAccion']['write'], 'delete' => $v['RolesAccion']['delete']);
	$fila[] = array('model' => 'Controlador', 'field' => 'etiqueta', 'valor' => $v['Controlador']['nombre'], "nombreEncabezado"=>"Controlador");
	$fila[] = array('model' => 'Accion', 'field' => 'etiqueta', 'valor' => $v['etiqueta'], "nombreEncabezado"=>"Accion");
	$fila[] = array('model' => 'RolesAccion', 'field' => 'estado', 'valor' => $v['RolesAccion']['estado']);
	$cuerpo[] = $fila;
}

$url[] = array('controller' => "roles_acciones", 'action' => 'add', "RolesAccion.rol_id"=>$this->data['Rol']['id']);
$url[] = array('controller' => "roles_acciones", 'action' => "add_rapido", "RolesAccion.rol_id"=>$this->data['Rol']['id'], "texto"=>"Carga Rapida");
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Acciones", 'cuerpo' => $cuerpo));

?>