<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.views
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 24 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-10-17 15:49:35 -0300 (vie 17 de oct de 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

//d($documento);
$documento->objPHPExcel->getProperties()->setCreator("Maarten Balliauw");
$documento->objPHPExcel->getProperties()->setLastModifiedBy("Maarten Balliauw");
$documento->objPHPExcel->getProperties()->setTitle("Office 2007 XLSX Test Document");
$documento->objPHPExcel->getProperties()->setSubject("Office 2007 XLSX Test Document");
$documento->objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
$documento->objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");
$documento->objPHPExcel->getProperties()->setCategory("Test result file");

foreach($registros as $k=>$v) {
	$k++;
	//d($v);
	$documento->objPHPExcel->getActiveSheet()->setCellValue('B' . $k, "XXXXXXXXXXXXXXX" . $v['Trabajador']['nombre']);
	$documento->objPHPExcel->getActiveSheet()->setCellValue('C' . $k, $v['Trabajador']['apellido']);
	$documento->objPHPExcel->getActiveSheet()->setCellValue('D' . $k, $v['Trabajador']['numero_documento']);
}
$documento->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$documento->objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$documento->objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

//$documento->objPHPExcel->getActiveSheet()->setCellValue('B2', 'col2');
//$documento->objPHPExcel->getActiveSheet()->setCellValue('B3', 'col3 jahskjda kjdsahk dsajkjsa');
//$documento->objPHPExcel->getActiveSheet()->setCellValue('B4', 'col4');


//$documento->objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
/*
$documento->objPHPExcel->getActiveSheet()->duplicateStyleArray(
		array(
			'font'    => array(
				'bold'      => true
			),
			'alignment' => array(
				'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
			),
			'borders' => array(
				'top'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				)
			),
			'fill' => array(
	 			'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
	  			'rotation'   => 90,
	 			'startcolor' => array(
	 				'argb' => 'FFA0A0A0'
	 			),
	 			'endcolor'   => array(
	 				'argb' => 'FFFFFFFF'
	 			)
	 		)
		),
		'B1:C4'
);
*/
$documento->sendToBrowser();
/*
$documento->test();
*/

?>