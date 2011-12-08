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
 * @version         $Revision: 528 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-05-20 16:56:44 -0300 (Wed, 20 May 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
if (!empty($data)) {

    $documento->create(array(
		'password' 		=> false,
		'orientation' 	=> 'landscape',
		'filters'		=> $documento->getReportFilters($this->data),
		'title' 		=> 'Relaciones Activas'));

    $documento->setCellValue('A', 'Cuit', array('title' => '15'));
    $documento->setCellValue('B', 'Empleador', array('title' => '30'));
    $documento->setCellValue('C', 'Cuil', array('title' => '15'));
	$documento->setCellValue('D', 'F. Ingreso', array('title' => '15'));
    $documento->setCellValue('E', 'Apellido', array('title' => '20'));
    $documento->setCellValue('F', 'Nombre', array('title' => '25'));
	$documento->setCellValue('G', 'F. Nacimiento', array('title' => '15'));
    $documento->setCellValue('H', 'Area', array('title' => '30'));
	$documento->setCellValue('I', 'Estado', array('title' => '20'));


    /** Body */
	$totals = array('Activa' => 0, 'Suspendida' => 0);
    foreach ($data as $k => $record) {

		$info = array(
				$record['Empleador']['cuit'],
				$record['Empleador']['nombre'],
				$record['Trabajador']['cuil'],
				$record['Relacion']['ingreso'],
				$record['Trabajador']['apellido'],
				$record['Trabajador']['nombre'],
				$record['Trabajador']['nacimiento'],
				$record['Area']['nombre'],
				$record['Relacion']['estado']);

        $documento->setCellValueFromArray($info);
		$totals[$record['Relacion']['estado']]++;
    }

	$t['Relaciones'] = array(array_sum($totals) => array('bold', 'right'));
    foreach ($totals as $name => $total) {
        $t[$name . 's'] = array($total => 'right');
    }
    $documento->setTotals($t);

    $documento->save($fileFormat);
} else {

	$conditions = null;

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

	$conditions['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo', 'type' => 'periodo', 'periodo' => array('soloAAAAMM'));

	$conditions['Condicion.Bar-con_liquidacion_periodo'] = array('label' => 'Liquidacion en el Periodo', 'type' => 'radio', 'options' => array('Si' => 'Si', 'Indistinto' => 'Indistinto'), 'default' => 'Indistinto');

	$conditions['Condicion.Bar-state'] = array('label' => 'Estado', 'multiple' => 'checkbox', 'options' => array('Activa' => 'Activa', 'Suspendida' => 'Suspendida'));

    $options = array('title' => 'Relaciones Activas');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>