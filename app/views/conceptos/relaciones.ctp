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
foreach ($this->data['Relacion'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'id', 'valor' => $v['RelacionesConcepto']['id'], 'write' => $v['RelacionesConcepto']['write'], 'delete' => $v['RelacionesConcepto']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['Empleador']['cuit'] . " - " . $v['Empleador']['nombre'], "nombreEncabezado"=>"Empleador");
	$fila[] = array('model' => 'Trabajador', 'field' => 'cuil', 'valor' => $v['Trabajador']['cuil'] . " - " . $v['Trabajador']['nombre'] . " " . $v['Trabajador']['apellido'], "nombreEncabezado"=>"Trabajador");
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'formula', 'valor' => $v['RelacionesConcepto']['formula']);
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'desde', 'valor' => $v['RelacionesConcepto']['desde']);
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'hasta', 'valor' => $v['RelacionesConcepto']['hasta']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "relaciones_conceptos", 'action' => 'add', "RelacionesConcepto.concepto_id"=>$this->data['Concepto']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Formulas de las Relaciones Laborales", 'cuerpo' => $cuerpo));

?>