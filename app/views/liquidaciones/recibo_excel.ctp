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
 * @version         $Revision: 1314 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-19 07:46:29 -0300 (mié 19 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

    $documento->create(array('header' => false, 'orientation' => 'landscape'));
    /*
    $documento->setActiveSheet();
    $documento->activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    $documento->activeSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
    
    //$documento->activeSheet->getDefaultStyle()->getFont()->setName('Courier New');
    $documento->activeSheet->getDefaultRowDimension()->setRowHeight(10);
*/
    $documento->activeSheet->getDefaultStyle()->getFont()->setName('Arial');
    $documento->activeSheet->getDefaultStyle()->getFont()->setSize(8);

    $documento->setWidth('A:AS', 4);

    $pageMargins = $documento->activeSheet->getPageMargins();
    $pageMargins->setTop(0.2);
    $pageMargins->setBottom(0.2);
    $pageMargins->setLeft(0.05);
    $pageMargins->setRight(0.2);


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
    
    $initialRow = 0;
    foreach ($this->data as $receipt) {

        for ($i = 0; $i <= 1; $i++) {
            $fila = $initialRow;
            $fila+=2;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderTop);
            }
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, $receipt['Liquidacion']['empleador_nombre'], $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('N') -1 + ($i * 23)) . ',' . $fila, 'Liquidacion:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('R') -1 + ($i * 23)) . ',' . $fila, ucfirst($receipt['Liquidacion']['tipo']));
            
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, $receipt['Liquidacion']['empleador_direccion'], $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('N') -1 + ($i * 23)) . ',' . $fila, 'Periodo:', $styleLeftBold);
            if ($receipt['Liquidacion']['tipo'] !== 'Final') {
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('R') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['ano'] . str_pad($receipt['Liquidacion']['mes'], 2, '0', STR_PAD_LEFT) . $receipt['Liquidacion']['periodo'], array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'ucfirst')), $styleLeftBold);
            }

            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, $receipt['Liquidacion']['empleador_cuit'], $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('N') -1 + ($i * 23)) . ',' . $fila, 'Fecha Pago:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('R') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['pago'], 'date'));

            $fila++;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderBottom);
            }
            

            $fila+=4;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderTop);
            }
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'Nombre:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('E') -1 + ($i * 23)) . ',' . $fila, sprintf('%s, %s', $receipt['Liquidacion']['trabajador_apellido'], $receipt['Liquidacion']['trabajador_nombre']), $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('N') -1 + ($i * 23)) . ',' . $fila, 'Legajo:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('R') -1 + ($i * 23)) . ',' . $fila, $receipt['Liquidacion']['relacion_legajo'] . ' ');

            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'Categoria:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('E') -1 + ($i * 23)) . ',' . $fila, $receipt['Liquidacion']['convenio_categoria_nombre']);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('N') -1 + ($i * 23)) . ',' . $fila, 'Cuil:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('R') -1 + ($i * 23)) . ',' . $fila, $receipt['Liquidacion']['trabajador_cuil']);

            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('N') -1 + ($i * 23)) . ',' . $fila, 'Fec. Ingreso:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('R') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['relacion_ingreso'], 'date'));
            $fila++;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderBottom);
            }
            
            

            $fila+=2;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderTop);
            }
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'Cantidad', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('E') -1 + ($i * 23)) . ',' . $fila, 'Concepto', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('S') -1 + ($i * 23)) . ',' . $fila, 'Haberes', $styleRightBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('V') -1 + ($i * 23)) . ',' . $fila, 'Deducc.', $styleRightBold);
            $fila++;
            foreach ($receipt['LiquidacionesDetalle'] as $detail) {
                if($detail['concepto_imprimir'] === 'Si' || ($detail['concepto_imprimir'] === 'Solo con valor') && abs($detail['valor']) > 0) {
                    if (abs($detail['valor_cantidad']) > 0) {
                        $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, $detail['valor_cantidad']);
                    }
                    $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('E') -1 + ($i * 23)) . ',' . $fila, $detail['concepto_nombre']);
                    if ($detail['concepto_tipo'] !== 'Deduccion') {
                        $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('S') -1 + ($i * 23)) . ',' . $fila, $formato->format($detail['valor'], 'currency'), $styleRight);
                    } else {
                        $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('V') -1 + ($i * 23)) . ',' . $fila, $formato->format($detail['valor'], 'currency'), $styleRight);
                    }
                    $fila++;
                }
            }

            $fila = $initialRow + 41;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderBottom);
            }

            $fila += 3;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderTop);
            }
            
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'TOTALES:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('K') -1 + ($i * 23)) . ',' . $fila, 'No Remunerativo', $styleRightBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('O') -1 + ($i * 23)) . ',' . $fila, 'Remunerativo', $styleRightBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('S') -1 + ($i * 23)) . ',' . $fila, 'Haberes', $styleRightBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('V') -1 + ($i * 23)) . ',' . $fila, 'Deducc.', $styleRightBold);
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('K') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['no_remunerativo'], 'currency'), $styleRight);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('O') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['remunerativo'], 'currency'), $styleRight);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('S') -1 + ($i * 23)) . ',' . $fila, $formato->format(($receipt['Liquidacion']['remunerativo'] + $receipt['Liquidacion']['no_remunerativo']), 'currency'), $styleRight);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('V') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['deduccion'], 'currency'), $styleRight);
            
            $fila+=2;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('S') -1 + ($i * 23)) . ',' . $fila, 'Neto:', $styleLeftBold);
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('V') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['total_pesos'], 'currency'), $styleRightBold);

            $fila+=2;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'Son Pesos:', $styleLeftBold);
            $fila++;
            $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Liquidacion']['total_pesos'], array('type' => 'numeroEnLetras', 'case' => 'ucfirst')));
            
            $fila+=2;
            if (!empty($receipt['Suss'])) {
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('M') -1 + ($i * 23)) . ',' . $fila, 'Ultimo Periodo Aporte Jubilatorio:', $styleLeftBold);
                $fila++;
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('M') -1 + ($i * 23)) . ',' . $fila, 'Periodo:', $styleLeftBold);
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('P') -1 + ($i * 23)) . ',' . $fila, 'Fecha:', $styleLeftBold);
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('T') -1 + ($i * 23)) . ',' . $fila, 'Banco:', $styleLeftBold);
                $fila++;
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('M') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Suss']['periodo'], array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'ucfirst')));
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('P') -1 + ($i * 23)) . ',' . $fila, $formato->format($receipt['Suss']['fecha'], 'date'));
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('T') -1 + ($i * 23)) . ',' . $fila, $receipt['Banco']['nombre']);
            } else {
                $fila+=2;
            }
            $fila+=2;
            if ($i === 0) {
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'Firma Trabajador');
            } else {
                $documento->setCellValue((PHPExcel_Cell::columnIndexFromString('B') -1 + ($i * 23)) . ',' . $fila, 'Firma Empleador');
            }
            
            $fila++;
            for ($ti = 0; $ti <= 21; $ti++) {
                $documento->setCellValue($ti + ($i * 23) . ',' . $fila, '', $styleBorderBottom);
            }
        }

        $fila+=2;
		if ($receipt['Liquidacion']['estado'] != 'Confirmada') {
			$documento->addImage('H' . ($fila - 40), 'invalid.png');
			$documento->addImage('AE' .  ($fila - 40), 'invalid.png');
		}
        $documento->activeSheet->setBreak('A' . $fila, PHPExcel_Worksheet::BREAK_ROW);
        $initialRow = $fila;
    }
    $documento->save('Excel5');

?>