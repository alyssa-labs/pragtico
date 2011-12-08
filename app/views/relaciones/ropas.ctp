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
foreach ($this->data['Ropa'] as $k=>$v) {
	$fila = null;
	$accionImprimir = $appForm->link($appForm->image('print.gif', array('alt' => "Imprimir Orden de Ropa", "title"=>"Imprimir Orden de Ropa")), "../ropas/imprimirOrden/" . $v['id'], array("target"=>"_blank"));
	$fila[] = array("tipo"=>"accion", "valor"=>$accionImprimir);
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], "update"=>"desglose_1", 'imagen' => array('nombre' => 'prendas.gif', 'alt' => "Prendas entregadas"), 'url' => '../ropas/prendas');
	$fila[] = array('model' => 'Ropa', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Ropa', 'field' => 'fecha', 'valor' => $v['fecha']);
	$fila[] = array('model' => 'Ropa', 'field' => 'observacion', 'valor' => $v['observacion']);
	$cuerpo[] = $fila;
}


$url = array('controller' => "ropas", 'action' => 'add', "Ropa.relacion_id"=>$this->data['Relacion']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Entrega de ropa", 'cuerpo' => $cuerpo));

?>