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

    $documento->create(array('password' => true, 'orientation' => 'landscape', 'title' => 'Listado de Descuentos Confirmados'));

    $total = 0;
    $flag = null;
    $inicio = 0;
    $flagCoeficiente = null;


    $documento->setCellValue('A', 'Empleador', array('title' => 35));
    $documento->setCellValue('B', 'Fecha', array('title' => 20));
    $documento->setCellValue('C', 'Cuil', array('title' => 25));
    $documento->setCellValue('D', 'Trabajador', array('title' => 35));
    $documento->setCellValue('E', 'Total', array('title' => 15));
    $documento->setCellValue('F', 'Cuenta', array('title' => 35));
            
    /** Body */
    $startRow = $documento->getCurrentRow() + 1;
    foreach ($data as $detail) {

        $account = '';
        if (preg_match('/(\d\d\d)(\d\d\d\d)\d(\d\d\d\d\d\d\d\d\d\d\d\d\d)\d/', $detail['Relacion']['Trabajador']['cbu'], $matches)) {
            unset($matches[0]);
            $account = implode(' ', $matches);
        }

        $documento->setCellValueFromArray(
            array(  $detail['Relacion']['Empleador']['nombre'],
                    $detail['Descuento']['alta'],
                    array('value' => $detail['Relacion']['Trabajador']['cuil'], 'options' => 'center'),
                    $detail['Relacion']['Trabajador']['apellido'] . ', ' . $detail['Relacion']['Trabajador']['nombre'],
                    array('value' => $detail['Descuento']['monto'], 'options' => 'currency'),
                    array('value' => $account, 'options' => 'center')));
    }
    $endRow = $documento->getCurrentRow();

    $t['Anticipos'] = array(count($data) => array('bold', 'right'));
    $t['Total'] = sprintf('=SUM(E%s:E%s)', $startRow, $endRow);
    $documento->setTotals($t);

    $documento->moveCurrentRow(4);
    $documento->setCellValue('A', 'Observaciones:', 'bold');
    $documento->moveCurrentRow(1);
    $styleArray = array(
        'font'      => array('size' => 11, 'bold' => true),
        'borders'   => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                'color' => array('argb' => '00000000'),
            ),
        ),
    );
    $documento->activeSheet->getStyle('A' . $documento->getCurrentRow() . ':F' . ($documento->getCurrentRow() + 6))->applyFromArray($styleArray);
    for ($i = 'A'; $i <= 'F'; $i++) {
        for ($j = $documento->getCurrentRow(); $j <= $documento->getCurrentRow() + 6; $j++) {
            $documento->doc->getActiveSheet()->getRowDimension($j)->setRowHeight(15);
            $documento->doc->getActiveSheet()->getStyle($i . $j)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        }
    }
    
    $documento->save('Excel5');

}
?>