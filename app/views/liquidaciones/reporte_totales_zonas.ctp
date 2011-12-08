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

    $gridTitles['A'] = array('Zona' => array('title' => '50'));
    $gridTitles['B'] = array('Total Rem.' => array('title' => '20'));
	$gridTitles['C'] = array('Total No Rem.' => array('title' => '20'));
	$gridTitles['D'] = array('Total Deduc.' => array('title' => '20'));
	$gridTitles['E'] = array('Total' => array('title' => '20'));

    $documento->create(array(
		'password' 		=> false,
		'title' 		=> 'Totales Liquidados por Zona',
		'filters'		=> $documento->getReportFilters($this->data),
		'gridTitles' 	=> $gridTitles));


    /** Body */
	$total = 0;
	$start = $documento->getCurrentRow();
    foreach ($data as $detail) {

        $documento->setCellValueFromArray(
            array(
				$detail['Zone']['name'],
				array('value' => $detail['Liquidacion']['total_remunerativo'], 'options' => 'currency'),
				array('value' => $detail['Liquidacion']['total_no_remunerativo'], 'options' => 'currency'),
				array('value' => $detail['Liquidacion']['total_deduccion'], 'options' => 'currency'),
				array('value' => $detail['Liquidacion']['total'], 'options' => 'currency'),
			));
    }
	$end = $documento->getCurrentRow();

	$documento->moveCurrentRow(3);
	$documento->setCellValue('A' . $documento->getCurrentRow() . ':E' . $documento->getCurrentRow(), 'TOTALES', 'title');
	$documento->moveCurrentRow();
	$documento->setCellValue('B', '=SUM(B' . ($start + 1). ':B' . $end . ')', 'total');
	$documento->setCellValue('C', '=SUM(C' . ($start + 1). ':C' . $end . ')', 'total');
	$documento->setCellValue('D', '=SUM(D' . ($start + 1). ':D' . $end . ')', 'total');
	$documento->setCellValue('E', '=SUM(E' . ($start + 1). ':E' . $end . ')', 'total');
    $documento->save($fileFormat);

} else {

	$conditions = null;
    $conditions['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo', 'type' => 'periodo', 'periodo' => array('soloAAAAMM'));

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));


	$options = array(
		'title' => 'Totales Liquidados por Zona',
		'conditions' => array('Bar-grupo_id' => 'multiple')
	);

    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}

?>