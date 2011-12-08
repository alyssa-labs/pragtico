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
 
$usuario = $session->read("__Usuario");
$pdf->ezInicio(array("usuario"=>$usuario['Usuario']['nombre']));

if(!empty($data)) {
	$columnas		= array(
		"Trabajador" 	=> array('justification'=>'left'),
		"Pesos" 		=> array('justification'=>'right'),
		"Beneficios" 	=> array('justification'=>'right'),
		"Total" 		=> array('justification'=>'right'),
		"Banco" 		=> array('justification'=>'left'),
		"Suc." 			=> array('justification'=>'left'),
		"Cuenta" 		=> array('justification'=>'left'));
		
	$opciones = array(	'cols'				=>$columnas,
						'headerFontSize'	=>7,
						'shaded'			=>0,
						'width'				=>550,
						'separadorHeader'	=>true,
						'xOrientation'		=>'center',
						'showLines'			=>0,
						'fontSize'			=>6);

	$kAnterior = array_shift(array_keys($data));
	$tabla = null;
	foreach($data as $k=>$v) {
		if($kAnterior != $k) {
			$kAnterior = $k;
			$pdf->ezText("Periodo Liquidado: <b>" . $condiciones['Liquidacion-ano'] . " " . $condiciones['Liquidacion-mes'] . " " . $condiciones['Liquidacion-periodo'] . "</b>", 6);
			$pdf->ezText("Empleador Liquidado: <b>" . $empleadorNombre . "</b>", 6);
			$pdf->ezText("");
			$pdf->ezTable($tabla, null, null, $opciones);
			$pdf->ezText("Total en Pesos: <b>" . $formato->format($totalPesos, array("before"=>"$ ", "places"=>2)) . "</b>", 6, array("justification"=>"right"));
			$pdf->ezText("Total en Beneficios: <b>" . $formato->format($totalBeneficios, array("before"=>"$ ", "places"=>2)) . "</b>", 6, array("justification"=>"right"));
			$pdf->ezNewPage();
			$tabla = null;
		}
		foreach($v as $k1=>$v1) {
			$empleadorNombre = $v1['empleador'];
			$totalBeneficios = $v1['total_beneficios'];
			$totalPesos = $v1['total_pesos'];
			$tabla[] = array(
				"Trabajador" 	=> $k1 . " " . $v1['apellido'] . " " . $v1['nombre'],
				"Pesos" 		=> $formato->format($v1['pesos'], array("before"=>"$ ", "places"=>2)),
				"Beneficios" 	=> $formato->format($v1['beneficios'], array("before"=>"$ ", "places"=>2)),
				"Total" 		=> $formato->format($v1['beneficios'] + $v1['pesos'], array("before"=>"$ ", "places"=>2)),
				"Banco" 		=> $v1['banco'],
				"Suc." 			=> $v1['sucursal'],
				"Cuenta" 		=> $v1['cuenta']);
		}
	}
	$pdf->ezText("Periodo Liquidado: <b>" . $condiciones['Liquidacion-ano'] . " " . $condiciones['Liquidacion-mes'] . " " . $condiciones['Liquidacion-periodo'] . "</b>", 6);
	$pdf->ezText("Empleador Liquidado: <b>" . $empleadorNombre . "</b>", 6);
	$pdf->ezText("");
	$pdf->ezTable($tabla, null, null, $opciones);
	$pdf->ezText("Total en Pesos: <b>" . $formato->format($totalPesos, array("before"=>"$ ", "places"=>2)) . "</b>", 6, array("justification"=>"right"));
	$pdf->ezText("Total en Beneficios: <b>" . $formato->format($totalBeneficios, array("before"=>"$ ", "places"=>2)) . "</b>", 6, array("justification"=>"right"));
}
else {
	$pdf->ezText("No se han encontrado datos con los criterios especificados. Verifique.");
}
$pdf->ezStream();
?>