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
		'title' 		=> 'Resumen de Liquidacion'));

    /** Set array with definitios values. */
    $definitions = array(   array(  'width' => 60,
                                    'title' => ($group_option == 'trabajador')?'Trabajador':'Coeficiente' . ' / Concepto',
                                    'option' => null),
                            array(  'width' => 15,
                                    'title' => 'Cant.',
                                    'option' => 'decimal'),
                            array(  'width' => 15,
                                    'title' => 'Total',
                                    'option' => 'currency'),
                            array(  'width' => 15,
                                    'title' => 'Coeficiente',
                                    'option' => 'decimal'),
                            array(  'width' => 15,
                                    'title' => 'Total',
                                    'option' => 'currency'));

    /** Create headers */
    $column = 'A';
    foreach ($definitions as $definition) {
        /** Set title columns. */
        $documento->setCellValue($column, $definition['title'], array('title' => $definition['width']));
        $column++;
    }


    $extraTotals['Remunerativo'] = 0;
    $extraTotals['No Remunerativo'] = 0;
    $extraTotals['Deduccion'] = 0;


    /** Body */
    foreach ($data as $k => $detail) {

		$documento->moveCurrentRow();

        if (!empty($detail[0]['Liquidacion']['trabajador_cuil'])) {
            $documento->setCellValue('A', ($detail[0]['Liquidacion']['relacion_legajo']) . ' - ' . $detail[0]['Liquidacion']['trabajador_apellido'] . ', ' . $detail[0]['Liquidacion']['trabajador_nombre'], 'bold');
        } else {
            $documento->setCellValue('A', $k, 'bold');
        }

        $beginRow = $documento->getCurrentRow() + 1;
        foreach ($detail as $r) {

            $extraTotals[$r['LiquidacionesDetalle']['concepto_tipo']] += $r['LiquidacionesDetalle']['valor'];

            if ($r['LiquidacionesDetalle']['concepto_tipo'] === 'Deduccion') {
                $r['LiquidacionesDetalle']['valor'] = $r['LiquidacionesDetalle']['valor'] * -1;
            }

			$currentRow = $documento->getCurrentRow();
            $documento->setCellValueFromArray(
                array(  '    ' . $r['LiquidacionesDetalle']['concepto_nombre'],
                        $r['LiquidacionesDetalle']['suma_cantidad'],
                        array('value' => $r['LiquidacionesDetalle']['valor'], 'options' => 'currency'),
                        $r['LiquidacionesDetalle']['coeficiente_valor'],
                        array('value' => '=C' . ($currentRow + 1). '*' . 'D' . ($currentRow + 1), 'options' => 'currency')
				)
			);
        }

        $totals['C'][] = 'C' . ($currentRow + 2);
        $totals['E'][] = 'E' . ($currentRow + 2);
        $documento->setCellValueFromArray(
            array(  '2' => 
                array('value' => '=SUM(C' . $beginRow . ':C' . ($currentRow + 1) . ')', 'options' => 'total'),
                    '4' =>
                array('value' => '=SUM(E' . $beginRow . ':E' . ($currentRow + 1) . ')', 'options' => 'total')
			)
		);
    }

	$currentRow = $documento->getCurrentRow() + 1;
    $documento->setCellValue('A' . $currentRow . ':E' . $currentRow, 'TOTALES', 'title');

	$documento->moveCurrentRow(2);
    $documento->setCellValue('A', 'Trabajadores', 'bold');
    $documento->setCellValue('E', $totalWorkers, 'bold');
	$documento->moveCurrentRow();
    $documento->setCellValue('A', 'Liquidado', 'bold');
    $documento->setCellValue('E', '=SUM('.implode('+', $totals['C']).')', 'total');

    foreach ($extraTotals as $t => $v) {
		$documento->moveCurrentRow();
        $documento->setCellValue('A', '    ' . $t, 'bold');
        $documento->setCellValue('E', $v, 'total');
    }

	$documento->moveCurrentRow();
    $documento->setCellValue('A', 'A Facturar', 'bold');
    $documento->setCellValue('E', '=SUM('.implode('+', $totals['E']).')', 'total');


	if (!empty($fileName)) {
		$documento->save($fileName);
	} else {
		$documento->save($fileFormat);
	}

} else {

	$conditions = null;
    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => 0,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));
    
    $conditions['Condicion.Bar-trabajador_id'] = array(
            'lov'   => array(   'controller'   => 'trabajadores',
                                'seleccionMultiple'    => 0,
                                'camposRetorno' => array('Trabajador.cuil', 'Trabajador.nombre', 'Trabajador.apellido')));

	$conditions['Condicion.Bar-area_id'] = array(
			'label' => 'Area',
			'mask'          =>  '%s, %s',
			'lov'	=> array('controller'	=> 'areas',
							'camposRetorno'	=> array(   'Empleador.nombre',
														'Area.nombre')));


    $conditions['Condicion.Bar-concepto_id'] = array( 'lov' => array(
            'controller'        => 'conceptos',
            'seleccionMultiple' => 1,
            'camposRetorno'     => array('Concepto.nombre')));

    $conditions['Condicion.Bar-agrupamiento'] = array('type' => 'radio', 'default' => 'coeficiente', 'options' => $options, 'label' => 'Opcion');

    $conditions['Condicion.Bar-tipo'] = array('label' => 'Tipo', 'multiple' => 'checkbox', 'type' => 'select', 'options' => $types);

    $conditions['Condicion.Bar-periodo_largo_desde'] = array('label' => 'Periodo Desde', 'type' => 'periodo', 'periodo' => array('1Q', '2Q', 'M', '1S', '2S', 'F'), 'aclaracion' => 'Si no desea especificar un rango, solo especifique el periodo desde.');

	$conditions['Condicion.Bar-periodo_largo_hasta'] = array('label' => 'Periodo Hasta', 'type' => 'periodo', 'periodo' => array('1Q', '2Q', 'M', '1S', '2S', 'F'));

    $conditions['Condicion.Bar-estado'] = array('label' => 'Estado', 'multiple' => 'checkbox', 'type' => 'select', 'options' => $states);

    $options = array('title' => 'Resumen de Liquidacion');

    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));

}
 
?>