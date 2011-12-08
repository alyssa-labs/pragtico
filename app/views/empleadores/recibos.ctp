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
 * @version			$Revision: 1385 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-29 19:23:36 -0300 (mar 29 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Recibo'] as $k=>$v) {
	$fila = null;

	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'update' => 'desglose_1', 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Conceptos (Detalle del Recibo)'), 'url' => '../recibos/conceptos');

	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('asignar.gif', array('alt' => 'Sincronizar este recibo en todas las relaciones que lo tengan asigando', 'title' => 'Sincronizar este recibo en todas las relaciones que lo tengan asigando')), array('controller' => 'recibos', 'action' => 'sync', $v['id']), array(), 'Sincronizara los conceptos del recibo ' . $v['nombre'] . ' en todas las relaciones que lo tengan asignado. Desea continuar?'));

	$fila[] = array('model' => 'Recibo', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Recibo', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Recibo', 'field' => 'descripcion', 'valor' => $v['descripcion']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'recibos', 'action' => 'add', 'Recibo.empleador_id' => $this->data['Empleador']['id']);

echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Recibos', 'cuerpo' => $cuerpo));

?>