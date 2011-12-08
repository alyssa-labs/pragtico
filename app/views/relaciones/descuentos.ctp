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
 * @version			$Revision: 247 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-03 10:21:49 -0200 (mar 03 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Descuento'] as $k=>$v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'update'=>'desglose_1', 'imagen' => array('nombre' => 'descuentos.gif', 'alt' => 'Detalle de los descuentos'), 'url' => 'descuentos_detalle');
	$fila[] = array('model' => 'Descuento', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Descuento', 'field' => 'alta', 'valor' => $v['alta']);
	$fila[] = array('model' => 'Descuento', 'field' => 'desde', 'valor' => $v['desde']);
	$fila[] = array('model' => 'Descuento', 'field' => 'monto', 'valor'=>$v['monto'], 'tipoDato'=>'moneda');
	$fila[] = array('model' => 'Descuento', 'field' => 'descontar', 'valor' => $v['descontar']);
	$fila[] = array('model' => 'Descuento', 'field' => 'maximo', 'valor' => $v['maximo'] . ' %');
	$fila[] = array('model' => 'Descuento', 'field' => 'tipo', 'valor' => $v['tipo']);
	$fila[] = array('model' => 'Descuento', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'descuentos', 'action' => 'add', 'Descuento.relacion_id'=>$this->data['Relacion']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Descuentos', 'cuerpo' => $cuerpo));

?>