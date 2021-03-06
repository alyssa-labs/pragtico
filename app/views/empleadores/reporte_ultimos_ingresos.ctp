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
		'title' 		=> 'Ultimos Ingresos'));
    
    $documento->setCellValue('A', 'Cuit', array('title' => 20));
    $documento->setCellValue('B', 'Empleador', array('title' => 35));
    $documento->setCellValue('C', 'Cuil', array('title' => 20));
    $documento->setCellValue('D', 'Apellido', array('title' => 20));
    $documento->setCellValue('E', 'Nombre', array('title' => 20));
    $documento->setCellValue('F', 'Ingreso', array('title' => 15));

    /** Body */
    foreach ($data as $k => $detail) {
        $documento->setCellValueFromArray(
            array(  $detail['Empleador']['cuit'],
                    $detail['Empleador']['nombre'],
                    (!empty($detail['Trabajador']['cuil']))?$detail['Trabajador']['cuil']:'',
                    (!empty($detail['Trabajador']['apellido']))?$detail['Trabajador']['apellido']:'',
                    (!empty($detail['Trabajador']['nombre']))?$detail['Trabajador']['nombre']:'',
                    (!empty($detail[0]['ingreso']))?$detail[0]['ingreso']:''));
    }

    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

    $options = array('title' => 'Ultimos Ingresos');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>