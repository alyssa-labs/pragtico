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
		'title' 		=> 'Listado de Liquidaciones Finales a Realizar',
		'filters'		=> $documento->getReportFilters($this->data),
		'orientation' 	=> 'landscape'));

    $documento->setCellValue('A', 'Cuil', array('title' => '20'));
    $documento->setCellValue('B', 'Apellido', array('title' => '30'));
    $documento->setCellValue('C', 'Nombre', array('title' => '30'));
    $documento->setCellValue('D', 'Empleador', array('title' => '40'));
    $documento->setCellValue('E', 'F. Ingreso', array('title' => '15'));
    $documento->setCellValue('F', 'F. Egreso', array('title' => '15'));
	$documento->setCellValue('G', 'Liq. Final', array('title' => '20'));

    /** Body */
    foreach ($data as $k => $detail) {

        $documento->setCellValueFromArray(
            array(  $detail['Trabajador']['cuil'],
                    $detail['Trabajador']['apellido'],
                    $detail['Trabajador']['nombre'],
                    $detail['Empleador']['nombre'],
                    $detail['Relacion']['ingreso'],
                    $detail['RelacionesHistorial']['fin'],
					$detail['RelacionesHistorial']['liquidacion_final']));
	}

    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-liquidacion_final'] = array('options' => array('si' => 'Si', 'suspender' => 'Suspender'), 'multiple' => 'checkbox');

    $conditions['Condicion.Bar-desde'] = array('label' => 'Desde', 'type' => 'date');
	$conditions['Condicion.Bar-hasta'] = array('label' => 'Hasta', 'type' => 'date');

    $options = array('title' => 'Liquidaciones Finales a Realizar');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>