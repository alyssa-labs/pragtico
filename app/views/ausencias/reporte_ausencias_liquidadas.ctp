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
		'title' 		=> 'Listado de Ausencias Liquidadas'));

    $documento->setCellValue('A', 'Empleador', array('title' => '40'));
    $documento->setCellValue('B', 'Cuil', array('title' => '20'));
    $documento->setCellValue('C', 'Apellido', array('title' => '20'));
    $documento->setCellValue('D', 'Nombre', array('title' => '20'));
    $documento->setCellValue('E', 'Tipo', array('title' => '40'));
    $documento->setCellValue('F', 'Dias Liq.', array('title' => '15'));
    $documento->setCellValue('G', 'Monto', array('title' => '20'));
    

    /** Body */
    foreach ($data as $k => $detail) {

        $currentRow =$documento->moveCurrentRow();
        $documento->setCellValue('A' . $currentRow .':A' . (($currentRow - 1) + $detail['lines']), $detail['employer']);
        unset($detail['employer']);
        $documento->setCellValue('B' . $currentRow .':B' . (($currentRow - 1) + $detail['lines']), $detail['cuil']);
        unset($detail['cuil']);
        $documento->setCellValue('C' . $currentRow .':C' . (($currentRow - 1) + $detail['lines']), $detail['last_name']);
        unset($detail['last_name']);
        $documento->setCellValue('D' . $currentRow .':D' . (($currentRow - 1) + $detail['lines']), $detail['name']);
        unset($detail['name']);
        unset($detail['lines']);
        
        foreach ($detail as $type => $d) {
            if (empty($totals[$type]['days'])) {
                $totals[$type]['days'] = 0;
                $totals[$type]['amount'] = 0;
            }
            
            $totals[$type]['days'] += $d['days'];
            $totals[$type]['amount'] += $d['amount'];
            
            $documento->setCellValue('E', $type);
            $documento->setCellValue('F', $d['days']);
            $documento->setCellValue('G', $d['amount'], 'currency');
            $documento->moveCurrentRow();
        }
        $documento->moveCurrentRow(-1);
    }


    $documento->moveCurrentRow(3);
    $documento->setCellValue('A' . $documento->getCurrentRow() . ':D' . $documento->getCurrentRow(), 'TOTALES', 'title');
    foreach ($totals as $label => $total) {
        $documento->moveCurrentRow();
        $documento->setCellValue('B', $label. ':', array('bold', 'right'));
        $documento->setCellValue('C', $total['days'], array('bold', 'right'));
        $documento->setCellValue('D', $total['amount'], array('bold', 'currency'));
    }

    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo', 'type' => 'periodo', 'periodo' => array('soloAAAAMM'));

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));
    
    $options = array('title' => 'Ausencias Liquidadas');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>