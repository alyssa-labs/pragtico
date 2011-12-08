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
		'title' 		=> 'Listado de Familiares',
		'filters'		=> $documento->getReportFilters($this->data),
		'orientation' 	=> 'landscape'));

    $documento->setCellValue('A', 'Cuil', array('title' => '20'));
    $documento->setCellValue('B', 'Trabajador', array('title' => '25'));
    $documento->setCellValue('C', 'Parentezco', array('title' => '20'));
    $documento->setCellValue('D', 'Doc', array('title' => '15'));
    $documento->setCellValue('E', 'Nro.', array('title' => '15'));
    $documento->setCellValue('F', 'Apellido', array('title' => '20'));
    $documento->setCellValue('G', 'Nombre', array('title' => '20'));
    $documento->setCellValue('H', 'Sexo', array('title' => '15'));
    $documento->setCellValue('I', 'F. Nacimiento', array('title' => '15'));
    $documento->setCellValue('J', 'Edad', array('title' => '15'));

    /** Body */
    foreach ($data as $k => $detail) {

        $documento->setCellValueFromArray(
            array(  $detail['Trabajador']['cuil'],
                    $detail['Trabajador']['apellido'] . ', ' . $detail['Trabajador']['nombre'],
                    $detail['Familiar']['parentezco'],  
                    $detail['Familiar']['tipo_documento'],
                    $detail['Familiar']['numero_documento'],
                    $detail['Familiar']['apellido'],
                    $detail['Familiar']['nombre'],
                    $detail['Familiar']['sexo'],
                    $detail['Familiar']['nacimiento'],
                    vsprintf('=INT((TODAY()-date(%s,%s,%s))/365.25)', explode('-', $detail['Familiar']['nacimiento']))));
    }
    $documento->save($fileFormat);
} else {

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));
    
    $conditions['Condicion.Bar-trabajador_id'] = array(
            'lov'   => array(   'controller'   => 'trabajadores',
                                'seleccionMultiple'    => true,
                                'camposRetorno' => array('Trabajador.cuil', 'Trabajador.nombre', 'Trabajador.apellido')));
    
    $options = array('title' => 'Familiares', 'conditions' => array('Bar-grupo_id' => false));
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>