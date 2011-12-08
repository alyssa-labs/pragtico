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
 * @version			$Revision: 844 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-08-17 22:29:35 -0300 (lun 17 de ago de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Concepto'] as $v) {
	$fila = null;
	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('asignar.gif', array('alt' => 'Asignar este concepto a todos los Trabajadores', 'title' => 'Asignar este concepto a todos los Trabajadores')), array('action' => 'manipular_concepto/agregar', 'empleador_id' => $this->data['Empleador']['id'], 'concepto_id' => $v['id']), array(), 'Asignara este concepto a todos los trabajadores del empleador ' . $this->data['Empleador']['nombre'] . '. Desea continuar?'));
	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('quitar.gif', array('alt' => 'Asignar este concepto a todos los Trabajadores', 'title' => 'Quitar este concepto de todos los Trabajadores')), array('action' => 'manipular_concepto/quitar', 'empleador_id' => $this->data['Empleador']['id'], 'concepto_id' => $v['id']), array(), 'Quitara este concepto de todos los trabajadores del empleador ' . $this->data['Empleador']['nombre'] . '. Desea continuar?'));
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'id', 'valor' => $v['EmpleadoresConcepto']['id'], 'write' => $v['EmpleadoresConcepto']['write'], 'delete' => $v['EmpleadoresConcepto']['delete']);
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'codigo', 'valor' => $v['codigo']);
	$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'nombre', 'valor' => $v['nombre']);
	if (!empty($v['EmpleadoresConcepto']['formula'])) {
		$fila[] = array('model' => 'Bar', 'field' => 'foo', 'valor' => 'Empleador', 'nombreEncabezado' => 'Jerarquia');
		$fila[] = array('model' => 'EmpleadoresConcepto', 'field' => 'formula', 'valor' => $v['EmpleadoresConcepto']['formula']);
	} else {
		$fila[] = array('model' => 'Bar', 'field' => 'foo', 'valor' => 'Concepto', 'nombreEncabezado' => 'Jerarquia');
		$fila[] = array('model' => 'Concepto', 'field' => 'formula', 'valor' => $v['formula']);
	}
	$cuerpo[] = $fila;
}

$url[] = array('controller' => 'empleadores_conceptos', 'action' => 'add', 'EmpleadoresConcepto.empleador_id' => $this->data['Empleador']['id']);
$url[] = array('controller' => 'empleadores_conceptos', 'action' => 'add_rapido', 'EmpleadoresConcepto.empleador_id' => $this->data['Empleador']['id'], 'texto' => 'Carga Rapida');

echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Concepto', 'cuerpo' => $cuerpo));

?>