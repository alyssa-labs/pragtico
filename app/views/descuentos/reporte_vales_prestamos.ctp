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
		'title' 		=> 'Anticipos',
		'filters'		=> $documento->getReportFilters($this->data),
		'orientation' 	=> 'landscape'));
    
    $documento->setCellValue('A', 'Cuit', array('title' => 15));
    $documento->setCellValue('B', 'Empleador', array('title' => 30));
    $documento->setCellValue('C', 'Cuil', array('title' => 15));
    $documento->setCellValue('D', 'Apellido', array('title' => 20));
    $documento->setCellValue('E', 'Nombre', array('title' => 20));
    $documento->setCellValue('F', 'Estado', array('title' => 15));
    $documento->setCellValue('G', 'Tipo', array('title' => 15));
    $documento->setCellValue('H', 'Alta', array('title' => 15));
    $documento->setCellValue('I', 'Monto', array('title' => 15));
    $documento->setCellValue('J', 'Fecha', array('title' => 15));
    $documento->setCellValue('K', 'Descontado', array('title' => 15));
    $documento->setCellValue('L', 'Saldo', array('title' => 15));

    /** Body */
    foreach ($data as $k => $detail) {

        $array = array(
            $detail['Relacion']['Empleador']['cuit'],
            $detail['Relacion']['Empleador']['nombre'],
            $detail['Relacion']['Trabajador']['cuil'],
            $detail['Relacion']['Trabajador']['apellido'],
            $detail['Relacion']['Trabajador']['nombre'],
            $detail['Descuento']['estado'],
            $detail['Descuento']['tipo'],
            $detail['Descuento']['alta'],
            array('value' => $detail['Descuento']['monto'], 'options' => 'currency'));

        $acumulado = 0;
        foreach ($detail['DescuentosDetalle'] as $k => $v) {
            $acumulado+= $v['monto'];
            if ($k == 0) {
                $array[] = $v['fecha'];
                $array[] = array('value' => $v['monto'], 'options' => 'currency');
                $array[] = array('value' => ($detail['Descuento']['monto'] - $acumulado), 'options' => 'currency');
            } else {
                $array = array(
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $v['fecha'],
                    array('value' => $v['monto'], 'options' => 'currency'),
                    array('value' => ($detail['Descuento']['monto'] - $acumulado), 'options' => 'currency'));
            }
            $documento->setCellValueFromArray($array);
        }
    }
    
    $documento->save($fileFormat);
} else {

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

    $conditions['Condicion.Bar-desde'] = array('type' => 'date');
    $conditions['Condicion.Bar-hasta'] = array('type' => 'date');

    $conditions['Condicion.Bar-tipo'] = array('type' => 'select', 'multiple' => 'checkbox', 'options' => array('Vale' => 'Vale', 'Prestamo' => 'Prestamo'));
    $conditions['Condicion.Bar-estado'] = array('type' => 'select', 'multiple' => 'checkbox', 'options' => array('Activo' => 'Activo', 'Finalizado' => 'Finalizado', 'Bloqueado' => 'Bloqueado'));
            
    $options = array('title' => 'Anticipos');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>