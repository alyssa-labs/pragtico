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
		'title' 		=> 'Listado de Liquidaciones'));

    $documento->setCellValue('A', 'CC', array('title' => '15'));
    $documento->setCellValue('B', 'Empleador', array('title' => '15'));
    $documento->setCellValue('C', 'Area', array('title' => '45'));
    $documento->setCellValue('D', 'Trabaj.', array('title' => '10'));
    $documento->setCellValue('E', 'Remuner.', array('title' => '15'));
    $documento->setCellValue('F', 'No Remuner.', array('title' => '15'));
    $documento->setCellValue('G', 'Facturado', array('title' => '15'));
    $documento->setCellValue('H', 'Contrib.', array('title' => '15'));
    $documento->setCellValue('I', 'ART Variable', array('title' => '15'));
    $documento->setCellValue('J', 'ART Fijo', array('title' => '15'));
    $documento->setCellValue('K', 'Resultado', array('title' => '15'));
    $documento->setCellValue('L', 'Coef. Rem.', array('title' => '15'));
    $documento->setCellValue('M', 'R.B. s/ Fact.', array('title' => '15'));

    /** Body */
    $totalRows = array();
    foreach ($data as $cc => $detail) {

        $documento->moveCurrentRow();
        $documento->setCellValue('A', $cc, 'bold');
        $initialRow = $documento->getCurrentRow() + 1;

        foreach ($detail as $employer => $areas) {

            $documento->moveCurrentRow();
            $documento->setCellValue('B', $employer, 'bold');
            
            foreach ($areas as $area => $values) {

                list($areaName, $groupId) = explode('||', $area);

                $documento->setCellValueFromArray(
                    array(  '',
                            '',
                            $areaName,
                            $values['trabajadores'],
                            array('value' => $values['remunerativo'], 'options' => 'currency'),
                            array('value' => $values['no_remunerativo'], 'options' => 'currency'),
                            array('value' => $values['facturado'], 'options' => 'currency'),
                            array('value' => '=E' . ($documento->getCurrentRow() + 1) . '*' . $groupParams[$groupId]['porcentaje_contribuciones'] . '/100', 'options' => 'currency'),
                            array('value' => '=E' . ($documento->getCurrentRow() + 1) . '*' . $groupParams[$groupId]['porcentaje_art_variable'] . '/100', 'options' => 'currency'),
                            array('value' => '=D' . ($documento->getCurrentRow() + 1) . '*' . $groupParams[$groupId]['valor_art_fijo'], 'options' => 'currency'),
                            array('value' => '=IF(G' . ($documento->getCurrentRow() + 1) . ' > 0, G' . ($documento->getCurrentRow() + 1) . '-E' . ($documento->getCurrentRow() + 1) . '-F' . ($documento->getCurrentRow() + 1) . '-H' . ($documento->getCurrentRow() + 1) . '-I' . ($documento->getCurrentRow() + 1) . '-J' . ($documento->getCurrentRow() + 1) . ', 0)', 'options' => 'currency'),
                            '=IF(G' . ($documento->getCurrentRow() + 1) . ' > 0, (G' . ($documento->getCurrentRow() + 1) . '-(1.15*F' . ($documento->getCurrentRow() + 1) . '))/E' . ($documento->getCurrentRow() + 1) . ', 0)',
                            '=IF(G' . ($documento->getCurrentRow() + 1) . ' > 0, K' . ($documento->getCurrentRow() + 1) . '/G' . ($documento->getCurrentRow() + 1) . ', 0)'
                    ));
            }
        }
        
        /** Sub-totals */
        $documento->moveCurrentRow(2);
        $documento->setCellValue('C', 'Sub-Total', 'bold');
        $documento->setCellValue('D', '=SUM(D' . $initialRow . ':D' . ($documento->getCurrentRow() - 1) . ')', array('bold', 'right'));
        $documento->setCellValue('E', '=SUM(E' . $initialRow . ':E' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('F', '=SUM(F' . $initialRow . ':F' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('G', '=SUM(G' . $initialRow . ':G' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('H', '=SUM(H' . $initialRow . ':H' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('I', '=SUM(I' . $initialRow . ':I' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('J', '=SUM(J' . $initialRow . ':J' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('K', '=SUM(K' . $initialRow . ':K' . ($documento->getCurrentRow() - 1) . ')', 'total');
        $documento->setCellValue('L', '=(G' . $documento->getCurrentRow() . '-(1.15*F' . $documento->getCurrentRow() . '))/E' . $documento->getCurrentRow(), array('bold', 'right'));
        $documento->setCellValue('M', '=K' . $documento->getCurrentRow() . '/G' . $documento->getCurrentRow(), array('bold', 'right'));
        $totalRows[$cc] = $documento->getCurrentRow();

        /** Adverage salaries */
        $documento->moveCurrentRow();
        $documento->setCellValue('C', 'Promedios', 'bold');
        $documento->setCellValue('E', '=E' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->setCellValue('F', '=F' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->setCellValue('G', '=G' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->setCellValue('H', '=H' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->setCellValue('I', '=I' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->setCellValue('J', '=J' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->setCellValue('K', '=K' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
        $documento->moveCurrentRow(2);
    }

    $documento->moveCurrentRow(4);
    $documento->setCellValue('A' . $documento->getCurrentRow() . ':M' . $documento->getCurrentRow(), 'RESUMEN', 'title');
    $initialResumeRow = $documento->getCurrentRow() + 1;
    foreach ($totalRows as $cc => $row) {
        $documento->moveCurrentRow();
        $documento->setCellValue('A', $cc, array('bold'));
        $documento->setCellValue('D', '=D' . $row, array('bold', 'right'));
        $documento->setCellValue('E', '=E' . $row, 'total');
        $documento->setCellValue('F', '=F' . $row, 'total');
        $documento->setCellValue('G', '=G' . $row, 'total');
        $documento->setCellValue('H', '=H' . $row, 'total');
        $documento->setCellValue('I', '=I' . $row, 'total');
        $documento->setCellValue('J', '=J' . $row, 'total');
        $documento->setCellValue('K', '=K' . $row, 'total');
        $documento->setCellValue('L', '=L' . $row, array('bold', 'right'));
        $documento->setCellValue('M', '=M' . $row, array('bold', 'right'));
    }
    $documento->moveCurrentRow();
    $documento->setCellValue('C', 'Totales', 'bold');
    $documento->setCellValue('D', '=SUM(D' . $initialResumeRow . ':D' . ($documento->getCurrentRow() - 1) . ')', array('bold', 'right'));
    $documento->setCellValue('E', '=SUM(E' . $initialResumeRow . ':E' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('F', '=SUM(F' . $initialResumeRow . ':F' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('G', '=SUM(G' . $initialResumeRow . ':G' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('H', '=SUM(H' . $initialResumeRow . ':H' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('I', '=SUM(I' . $initialResumeRow . ':I' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('J', '=SUM(J' . $initialResumeRow . ':J' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('K', '=SUM(K' . $initialResumeRow . ':K' . ($documento->getCurrentRow() - 1) . ')', 'total');
    $documento->setCellValue('L', '=(G' . $documento->getCurrentRow() . '-(1.15*F' . $documento->getCurrentRow() . '))/E' . $documento->getCurrentRow(), array('bold', 'right'));
    $documento->setCellValue('M', '=K' . $documento->getCurrentRow() . '/G' . $documento->getCurrentRow(), array('bold', 'right'));


    /** Adverage salaries */
    $documento->moveCurrentRow();
    $documento->setCellValue('C', 'Promedios Generales', 'bold');
    $documento->setCellValue('E', '=E' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('F', '=F' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('G', '=G' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('H', '=H' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('I', '=I' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('J', '=J' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('K', '=K' . ($documento->getCurrentRow() - 1) . '/D' . ($documento->getCurrentRow() - 1), 'total');
    
    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo', 'type' => 'periodo', 'periodo' => array('soloAAAAMM'));

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));
	$conditions['Condicion.Bar-desde'] = array('type' => 'date');
	$conditions['Condicion.Bar-hasta'] = array('type' => 'date');

    $options = array('title' => 'Liquidaciones', 'conditions' => array('Bar-grupo_id' => 'multiple'));
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>