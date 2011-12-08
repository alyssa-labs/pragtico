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
 * @version			$Revision: 675 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-07-10 12:00:54 -0300 (vie 10 de jul de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Pago'] as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Pago', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Pago', 'field' => 'fecha', 'valor' => $v['fecha']);
	$fila[] = array('model' => 'Pago', 'field' => 'moneda', 'valor' => $v['moneda']);
	$fila[] = array('model' => 'Pago', 'field' => 'monto', 'valor' => $v['monto'], 'tipoDato' => 'moneda');
	$fila[] = array('model' => 'Pago', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'Pagos', 'action' => 'add', 'Pago.liquidacion_id' => $this->data['Liquidacion']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Pagos', 'cuerpo' => $cuerpo));

?>