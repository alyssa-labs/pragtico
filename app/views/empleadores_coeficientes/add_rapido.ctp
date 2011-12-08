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
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (Tue, 27 Jan 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
$appForm->addCrumb($this->name, 
        array('controller'  => $this->params['controller'], 
              'action'      => 'index'));
$appForm->addCrumb(__('Edit', true));
$appForm->addCrumb('Coefientes para <h5>' . $empleador['Empleador']['nombre'] . '</h5>');
 
foreach($coefientes as $k => $v) {
	$fila = null;
    $fila[] = array('model' => 'Concepto', 'field' => 'tipo', 'valor' => $v['Coeficiente']['tipo']);
	$fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'valor' => $v['Coeficiente']['nombre']);
    $fila[] = array('model' => 'Concepto', 'field' => 'valor', 'valor' => $v['Coeficiente']['valor'], 'opciones' => array('class' => 'derecha'));
    $fila[] = array('model' => 'Concepto', 'field' => 'valor', 'valor' =>
	$appForm->input('EmpleadoresCoeficiente.' . $k . '.id', array('type' => 'hidden', 'value' => $v['EmpleadoresCoeficiente']['id'])) .
    $appForm->input('EmpleadoresCoeficiente.' . $k . '.coeficiente_id', array('type' => 'hidden', 'value' => $v['Coeficiente']['id'])) .
    $appForm->input('EmpleadoresCoeficiente.' . $k . '.empleador_id', array('type' => 'hidden', 'value' => $this->params['named']['EmpleadoresCoeficiente.empleador_id'])) .
    $appForm->input('EmpleadoresCoeficiente.' . $k . '.porcentaje', array('label' => false, 'class' => 'derecha', 'value' => $v['EmpleadoresCoeficiente']['porcentaje'], 'after' => '%')));
    $fila[] = array('model' => 'Concepto', 'field' => 'delete', 'valor' => $appForm->input('EmpleadoresCoeficiente.' . $k . '.delete', array('type' => 'checkbox', 'label' => false, 'div' => false)));
	$cuerpo[] = $fila;
}
$fila = null;
$fila[] = array('type' => 'header', 'model' => 'Concepto', 'field' => 'tipo', 'valor' => 'Tipo');
$fila[] = array('type' => 'header', 'model' => 'Concepto', 'field' => 'nombre', 'valor' => 'Coeficiente');
$fila[] = array('type' => 'header', 'model' => 'Concepto', 'field' => 'nombre', 'valor' => 'Valor');
$fila[] = array('type' => 'header', 'model' => 'Concepto', 'field' => 'nombre', 'valor' => 'Porcentaje');
$fila[] = array('type' => 'header', 'model' => 'Concepto', 'field' => 'delete', 'valor' => 'Eliminar');
$cuerpo[] = $fila;
$datos['tabla']['simple'] = true;
$datos['cuerpo'] = $cuerpo;
$acciones = $appForm->tag('div', $this->element('add/acciones'), array('class'=>'botones_tablas_from_to'));
$add = $appForm->tag('div', $appForm->form($appForm->tabla($datos) . $acciones, array('action' => 'save')), array('class' => 'unica'));
echo $appForm->tag('div', $add, array('class' => 'add'));

?>