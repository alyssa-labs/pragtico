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
		'title' 		=> 'Coeficientes',
		'filters'		=> $documento->getReportFilters($this->data),
		'orientation' 	=> 'landscape'));
    $documento->setCellValue('A', 'Cuit', array('title' => '20'));
    $documento->setCellValue('B', 'Empleador', array('title' => '30'));
	$documento->setCellValue('C', 'Area', array('title' => '30'));
    $documento->setCellValue('D', 'Coeficiente', array('title' => '30'));
    $documento->setCellValue('E', 'Valor', array('title' => '20'));
    $documento->setCellValue('F', 'Porcentaje (Empleador)', array('title' => '20'));
	$documento->setCellValue('G', 'Porcentaje (Area)', array('title' => '20'));
    $documento->setCellValue('H', 'Total', array('title' => '15'));

    /** Body */
    foreach ($data as $record) {

		foreach ($record['Area'] as $area) {

        	foreach ($area['Coeficiente'] as $coeficiente) {

				$valorEmpleador = $valorArea = 0;
				if (!empty($coeficiente['AreasCoeficiente']['porcentaje'])) {
					$valorArea = $coeficiente['AreasCoeficiente']['porcentaje'];
				}
				if (!empty($coeficiente['EmpleadoresCoeficiente']['porcentaje'])) {
					$valorEmpleador = $coeficiente['EmpleadoresCoeficiente']['porcentaje'];
				}

            	$documento->setCellValueFromArray(
                	array(  $record['Empleador']['cuit'],
                        	$record['Empleador']['nombre'],
							$area['nombre'],
                        	$coeficiente['nombre'],
                        	$coeficiente['valor'],
							$valorEmpleador,
                        	$valorArea,
                        	'=(E' . ($documento->getCurrentRow() + 1) . ' * (IF(G' . ($documento->getCurrentRow() + 1) . ' <> 0, G' . ($documento->getCurrentRow() + 1) . ', F' . ($documento->getCurrentRow() + 1) . ') / 100'));
        	}

		}
    }

    $documento->save($fileFormat);
} else {

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));
    
    $conditions['Condicion.Bar-solo_con_valor'] = array('label' => 'Solo Con Valor', 'aclaracion' => 'Solo muestra aquellos coeficientes que tienen valor de porcentaje en el Empleador', 'type' => 'radio', 'options' => array('Si' => 'Si', 'No' => 'No'));
    
    $options = array('title' => 'Coeficientes');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>