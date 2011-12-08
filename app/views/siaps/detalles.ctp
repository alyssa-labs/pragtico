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
 * @version			$Revision: 608 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-06-08 12:58:44 -0300 (lun 08 de jun de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['SiapsDetalle'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'descripcion', 'valor' => $v['descripcion']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'elemento', 'valor' => $v['elemento']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'valor', 'valor' => $v['valor']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'desde', 'valor' => $v['desde']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'longitud', 'valor' => $v['longitud']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'caracter_relleno', 'valor' => $v['caracter_relleno']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'direccion_relleno', 'valor' => $v['direccion_relleno']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "siaps_detalles", 'action' => 'add', "SiapsDetalle.siap_id"=>$this->data['Siap']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Detalles", 'cuerpo' => $cuerpo));

?>