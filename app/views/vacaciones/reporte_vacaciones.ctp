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
		'title' 		=> 'Listado de Vacaciones'));

    $documento->setCellValue('A', 'Cuit', array('title' => '20'));
	$documento->setCellValue('B', 'Empleador', array('title' => '30'));
    $documento->setCellValue('C', 'Cuil', array('title' => '20'));
    $documento->setCellValue('D', 'Apellido', array('title' => '30'));
    $documento->setCellValue('E', 'Nombre', array('title' => '30'));
	$documento->setCellValue('F', 'Corresponde', array('title' => '15'));
    $documento->setCellValue('G', 'Desde', array('title' => '15'));
    $documento->setCellValue('H', 'Dias', array('title' => '15'));

    /** Body */
    foreach ($data as $k => $detail) {

        $documento->setCellValueFromArray(
            array(  $detail['Empleador']['cuit'],
                    $detail['Empleador']['nombre'],
					$detail['Trabajador']['cuil'],
                    $detail['Trabajador']['apellido'],
                    $detail['Trabajador']['nombre'],
                    $detail['Vacacion']['corresponde'],
                    $detail['VacacionesDetalle']['desde'],
                    $detail['VacacionesDetalle']['dias']));
    }

    $documento->save($fileFormat);
} else {

    $conditions['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo', 'type' => 'periodo', 'periodo' => array('soloAAAAMM', 'A'), 'aclaracion' => 'Puede ingresar un periodo mensual o anual.');

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

    $options = array('title' => 'Vacaciones');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>