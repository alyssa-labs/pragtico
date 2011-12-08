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
		'filters'		=> $documento->getReportFilters($this->data),
		'orientation' 	=> 'landscape',
		'title' 		=> 'Relaciones Historicas'));

    $documento->setCellValue('A', 'Cuit', array('title' => '15'));
    $documento->setCellValue('B', 'Empleador', array('title' => '30'));
    $documento->setCellValue('C', 'Cuil', array('title' => '15'));
	$documento->setCellValue('D', 'F. Ingreso', array('title' => '15'));
    $documento->setCellValue('E', 'Apellido', array('title' => '20'));
    $documento->setCellValue('F', 'Nombre', array('title' => '25'));
	$documento->setCellValue('G', 'F. Nacimiento', array('title' => '15'));
    $documento->setCellValue('H', 'Area', array('title' => '30'));
	$documento->setCellValue('I', 'Estado', array('title' => '20'));
	$documento->setCellValue('J', 'Liq. Final', array('title' => '20'));
	$documento->setCellValue('K', 'Desde', array('title' => '15'));
	$documento->setCellValue('L', 'Hasta', array('title' => '15'));
	$documento->setCellValue('M', 'Motivo', array('title' => '40'));


    /** Body */
	$totals = array('Historicas' => 0, 'Suspendidas' => 0);
    foreach ($data as $k => $record) {

		$info = array(
				$record['Relacion']['Empleador']['cuit'],
				$record['Relacion']['Empleador']['nombre'],
				$record['Relacion']['Trabajador']['cuil'],
				$record['Relacion']['ingreso'],
				$record['Relacion']['Trabajador']['apellido'],
				$record['Relacion']['Trabajador']['nombre'],
				$record['Relacion']['Trabajador']['nacimiento'],
				(!empty($record['Relacion']['Area']['nombre'])?$record['Relacion']['Area']['nombre']:''),
				$record['Relacion']['estado'],
				$record['RelacionesHistorial']['liquidacion_final'],
				$record['RelacionesHistorial']['inicio'],
				$record['RelacionesHistorial']['fin'],
				$record['EgresosMotivo']['motivo']);

        $documento->setCellValueFromArray($info);
		if ($record['RelacionesHistorial']['liquidacion_final'] == 'Suspender') {
			$totals['Suspendidas']++;
		} else {
			$totals['Historicas']++;
		}
    }

	$t['Relaciones'] = array(array_sum($totals) => array('bold', 'right'));
    foreach ($totals as $name => $total) {
        $t[$name] = array($total => 'right');
    }
    $documento->setTotals($t);

    $documento->save($fileFormat);
} else {

	$conditions = null;

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

	$conditions['Condicion.Bar-desde'] = array('label' => 'Desde', 'type' => 'date');
	$conditions['Condicion.Bar-hasta'] = array('label' => 'Hasta', 'type' => 'date');

    $options = array('title' => 'Relaciones Historicas');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>