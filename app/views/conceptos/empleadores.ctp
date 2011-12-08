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
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'id', 'valor' => $v['EmpleadoresConcepto']['id'], 'write' => $v['EmpleadoresConcepto']['write'], 'delete' => $v['EmpleadoresConcepto']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['cuit'], "class"=>"centro");
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'formula', 'valor' => $v['EmpleadoresConcepto']['formula']);
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'desde', 'valor' => $v['EmpleadoresConcepto']['desde']);
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'hasta', 'valor' => $v['EmpleadoresConcepto']['hasta']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "empleadores_conceptos", 'action' => 'add', "EmpleadoresConcepto.concepto_id"=>$this->data['Concepto']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Empleadores", 'cuerpo' => $cuerpo));

?>