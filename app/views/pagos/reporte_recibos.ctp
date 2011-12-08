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
 * @version         $Revision: 1398 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-07-01 00:09:41 -0300 (Thu, 01 Jul 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

    $documento->create(array('header' => false));

    $documento->activeSheet->getDefaultStyle()->getFont()->setName('Arial');
    $documento->activeSheet->getDefaultStyle()->getFont()->setSize(8);


    $styleBorderBottom = array('style' => array(
        'borders' => array( 'bottom'     => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
    $styleBorderTop = array('style' => array(
        'borders' => array( 'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
    
    $styleLeft = array('style' => array(
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));
    
    $styleLeftBold = array('style' => array(
        'font'      => array('bold' => true),
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT)));
    
    $styleRight = array('style' => array(
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
    
    $styleRightBold = array('style' => array(
        'font'      => array('bold' => true),
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)));
    
    $styleCenter = array('style' => array(
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));

    $styleCenterBold = array('style' => array(
        'font'      => array('bold' => true),
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)));
    

	$userGroups = User::getUserGroups('all');
	if (count($userGroups) > 1) {
		foreach ($userGroups as $userGroup => $userGroupName) {
			$groupParams[$userGroup] = User::getGroupParams($userGroup);
		}
	}


    foreach ($data as $payment) {

		for ($i = 0; $i <= 1; $i++) {

            for ($ti = 0; $ti <= 10; $ti++) {
                $documento->setCellValue($ti . ',' . $documento->getCurrentRow(), '', $styleBorderTop);
            }

			$documento->moveCurrentRow();
			$documento->setCellValue('K', $payment['Pago']['fecha']);
			$documento->moveCurrentRow(2);

			if (!empty($groupParams)) {
				$documento->setCellValue('A', $groupParams[$payment['Relacion']['group_id']]['nombre_fantasia'], 'bold');
				$documento->moveCurrentRow(2);
			}
			$documento->setCellValue('A', 'Trabajador:');
			$documento->setCellValue('C', $payment['Relacion']['Trabajador']['apellido'] . ' ' . $payment['Relacion']['Trabajador']['nombre']);
			$documento->moveCurrentRow();
			$documento->setCellValue('A', 'Empleador:');
			$documento->setCellValue('C', $payment['Relacion']['Empleador']['nombre']);

			$documento->moveCurrentRow(2);
			$documento->setCellValue('A', 'Detalle de Pagos:', 'bold');

			$initialRow = $documento->getCurrentRow();
			foreach ($payment['PagosForma'] as $pagoForma) {

				$documento->moveCurrentRow();
				if (!empty($pagoForma['cheque_numero'])) {
					$documento->setCellValue('B', 'Cheque Numero:');
					$documento->setCellValue('C', $pagoForma['cheque_numero']);
				} else {
					$documento->setCellValue('B', $pagoForma['forma'] . ':');
				}
				
				$documento->setCellValue('D', $pagoForma['monto'], 'total');
			}

			$documento->moveCurrentRow(2);
			$documento->setCellValue('A', 'Total de Pagos', 'bold');
			$documento->setCellValue('D', '=SUM(D'.($initialRow+1).':D'.($documento->getCurrentRow()-1).')', 'total');

			$documento->moveCurrentRow(5);
			$documento->setCellValue('H', 'Firma');

			$documento->moveCurrentRow();
			if ($i == 0) {
				$documento->setCellValue('K', 'Original');
			} else {
				$documento->setCellValue('K', 'Duplicado');
			}

			$documento->moveCurrentRow();
            for ($ti = 0; $ti <= 10; $ti++) {
                $documento->setCellValue($ti . ',' . $documento->getCurrentRow(), '', $styleBorderBottom);
            }
			if ($i == 0) {
				$documento->moveCurrentRow(2);
			}
		}

		$documento->activeSheet->setBreak('A' . ($documento->getCurrentRow() + 2), PHPExcel_Worksheet::BREAK_ROW);

    }
    $documento->save('Excel5');

?>