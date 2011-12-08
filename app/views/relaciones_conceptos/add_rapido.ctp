<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.views
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1019 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-24 16:55:57 -0300 (jue 24 de sep de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
$appForm->addCrumb($this->name, 
        array('controller'  => $this->params['controller'], 
              'action'      => 'index'));
$appForm->addCrumb(__('Edit', true));
$appForm->addCrumb('Conceptos para la Relacion <h5> ' . $relacion['Empleador']['nombre'] . " - " . $relacion['Trabajador']['apellido'] . ' ' . $relacion['Trabajador']['nombre'] . '</h5>');
 
foreach($concepts as $k => $v) {
    $fila = null;
    $checked = (in_array($v['Concepto']['codigo'], $assignedConcepts))?true:false;
    $fila[] = array('model' => 'Concepto', 'field' => 'included', 'valor' => $appForm->input('Concepto.' . $v['Concepto']['id'] . '|' . $v['Concepto']['codigo'], array('type' => 'checkbox', 'checked' => $checked, 'label' => false, 'div' => false)));
    $fila[] = array('model' => 'Concepto', 'field' => 'tipo', 'valor' => $v['Concepto']['tipo']);
    $fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'valor' => $v['Concepto']['nombre']);
    $cuerpo[] = $fila;
}
$fila = null;
$fila[] = array('type' => 'header', 'model' => 'RelacionesConcepto', 'field' => 'included', 'valor' => 'Incluido');
$fila[] = array('type' => 'header', 'model' => 'RelacionesConcepto', 'field' => 'tipo', 'valor' => 'Tipo');
$fila[] = array('type' => 'header', 'model' => 'RelacionesConcepto', 'field' => 'nombre', 'valor' => 'Concepto');
$cuerpo[] = $fila;
$datos['tabla']['simple'] = true;
$datos['cuerpo'] = $cuerpo;

$extra = $appForm->input('Form.tipo', array('type' => 'hidden', 'value' => 'addRapido'));
$extra .= $appForm->input('RelacionesConcepto.relacion_id', array('type' => 'hidden', 'value' => $relacion['Relacion']['id']));

$acciones = $appForm->tag('div', $this->element('add/acciones'), array('class'=>'botones_tablas_from_to'));
$add = $appForm->tag('div', $appForm->form($appForm->tabla($datos) . $extra . $acciones, array('action' => 'save')), array('class' => 'unica'));
echo $appForm->tag('div', $add, array('class' => 'add'));

?>