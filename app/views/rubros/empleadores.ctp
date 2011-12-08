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
foreach ($this->data['Empleador'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'Empleador', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['cuit']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Empleador', 'field' => 'telefono', 'valor' => $v['telefono']);
	$fila[] = array('model' => 'Empleador', 'field' => 'email', 'valor' => $v['email']);
$cuerpo[] = $fila;
}

$url = array('controller' => "empleadores_rubros", 'action' => 'add', "EmpleadoresRubro.rubro_id"=>$this->data['Rubro']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Empleadores", 'cuerpo' => $cuerpo));

?>