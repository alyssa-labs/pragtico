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

$documento->create(array(
	'password' 		=> true,
	'filters'		=> $documento->getReportFilters($this->data),
	'orientation'	=> 'portrait',
	'title' 		=> 'Reporte de Facturacion'));

$documento->setWidth('A', 10);
$documento->setWidth('B', 30);
$documento->setWidth('C', 35);
$documento->setWidth('D', 10);
$documento->setWidth('E', 11);
$documento->setWidth('F', 11);
$documento->setWidth('G', 11);
$documento->setWidth('H', 11);

$documento->setCellValue('A:D', 'Empleador: ' . $data['employer']['nombre'], array('bold'));
$documento->moveCurrentRow();
$documento->setCellValue('A:D', 'Periodo: ' . $data['invoice']['ano'] . str_pad($data['invoice']['mes'], 2, '0' ,STR_PAD_LEFT) . $data['invoice']['periodo'], array('bold'));


$documento->moveCurrentRow(4);
$documento->setCellValue('A', 'Legajo', array('title' => '10'));
$documento->setCellValue('B', 'Apellido y Nombre', array('title' => '30'));
$documento->setCellValue('C', 'Concepto', array('title' => '35'));
$documento->setCellValue('D', 'Cantidad', array('title' => '10'));
$documento->setCellValue('E', 'Liquidado', array('title' => '10'));
$documento->setCellValue('F', 'F. Rem.', array('title' => '10'));
$documento->setCellValue('G', 'F. No Rem.', array('title' => '10'));
$documento->setCellValue('H', 'F. Benef.', array('title' => '10'));


$fila = $documento->getCurrentRow() + 1;
foreach ($data['details'] as $detail) {

	$col = 'A';
	$documento->setCellValue($col . $fila, $detail['Trabajador']['legajo'], 'bold');
	$col++;
	$documento->setCellValue($col . $fila, sprintf('%s %s', $detail['Trabajador']['apellido'], $detail['Trabajador']['nombre']), 'bold');
	$col++;

	foreach ($detail['Concepto'] as $concept) {
		foreach ($concept as $k => $v) {
			if (!in_array($col, array('C', 'D'))) {
				$documento->setCellValue($col . $fila, $v, array('right', 'currency'));
			} else {
				$documento->setCellValue($col . $fila, $v);
			}
			$col++;
			continue;
		}
		$fila++;
		$col = 'C';
	}

	$col = 'E';
	foreach ($detail['Totales'] as $k => $v) {
		$documento->setCellValue($col . $fila, $v, 'total');
		$col++;
	}
	$fila++;
}

$fila+=2;
$documento->setCellValue('B' . $fila . ':F' . $fila, 'TOTALES', array('bold', 'center'));
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Total de Empleados Facturados', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Total de Empleados Facturados'], array('bold', 'right'));
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Facturado Remunerativo', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Facturado Remunerativo'], 'total');
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Facturado No Remunerativo', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Facturado No Remunerativo'], 'total');
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Facturado Beneficios', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Facturado Beneficios'], 'total');
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Iva', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Iva'], 'total');
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Total', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Total'], 'total');
$fila+=2;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Liquidado Remunerativo', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Liquidado Remunerativo'], 'total');
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Liquidado No Remunerativo', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Liquidado No Remunerativo'], 'total');
$fila++;
$documento->setCellValue('B' . $fila . ':D' . $fila, 'Total Liquidado', 'bold');
$documento->setCellValue('E' . $fila . ':F' . $fila, $data['totals']['Total Liquidado'], 'total');

if (!empty($fileName)) {
	$documento->save($fileName);
} else {
	$documento->save('Excel5');
}

?>