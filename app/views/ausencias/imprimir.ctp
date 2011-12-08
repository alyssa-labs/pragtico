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
 

foreach($registros as $registro) {
	
	$empleador = $registro['Relacion']['Empleador']['cuit'] . " - " . $registro['Relacion']['Empleador']['nombre'];
	$trabajador = $registro['Relacion']['Trabajador']['cuil'] . " - " . $registro['Relacion']['Trabajador']['apellido'] . ", " . $registro['Relacion']['Trabajador']['nombre'];
	$mesDesde = $formato->format($registro['Ausencia']['desde'], array("type"=>"mesEnLetras"));
	$anoDesde = $formato->format($registro['Ausencia']['desde'], array("type"=>"ano"));
	$mesHasta = $formato->format($registro['Ausencia']['hasta'], array("type"=>"mesEnLetras"));
	$anoHasta = $formato->format($registro['Ausencia']['hasta'], array("type"=>"ano"));

	if($anoDesde == $anoHasta && $mesDesde == $mesHasta) {
		$diferencia = $formato->diferenciaEntreFechas($registro['Ausencia']['hasta'], $registro['Ausencia']['desde']);
		$datos[$mesDesde . " / " . $anoDesde][$empleador][$trabajador][] =
				array(	"desde"	=>$registro['Ausencia']['desde'],
						"hasta"	=>$registro['Ausencia']['hasta'],
						"dias"	=>$diferencia['dias']+1);
	}
	elseif($anoDesde == $anoHasta) {
		$mesDesdeNumero = $formato->format($registro['Ausencia']['desde'], array("type"=>"mes"));
		$mesHastaNumero = $formato->format($registro['Ausencia']['hasta'], array("type"=>"mes"));
		
		for($i=$mesDesdeNumero; $i<=$mesHastaNumero; $i++) {
			$mes = str_pad($i, 2, "0", STR_PAD_LEFT);

			if($i==$mesDesdeNumero) {
				$fechaDesde = $anoDesde . "-" . $mes . "-" . $formato->format($registro['Ausencia']['desde'], array("type"=>"dia"));
			}
			else {
				$fechaDesde = $anoDesde . "-" . $mes . "-01";
			}

			if($i==$mesHastaNumero) {
				$fechaHasta = $anoDesde . "-" . $mes . "-" . $formato->format($registro['Ausencia']['hasta'], array("type"=>"dia"));
			}
			else {
				$ultimoDiaDelMes = $formato->format("", array("type"=>"ultimoDiaDelMes", "ano"=>$anoDesde, "mes"=>$mes));
				$fechaHasta = $anoDesde . "-" . $mes . "-" . $ultimoDiaDelMes;
			}
			
			$diferencia = $formato->diferenciaEntreFechas($fechaHasta, $fechaDesde);
			
			$fechaDummy = $anoDesde . "-" . $mes . "-01";
			$mesEnLetras = $formato->format($fechaDummy, array("type"=>"mesEnLetras"));
			$datos[$mesEnLetras . " / " . $anoDesde][$empleador][$trabajador][] =
				array(	"desde"	=>$fechaDesde,
						"hasta"	=>$fechaHasta,
						"dias"	=>$diferencia['dias']+1);
		}
	}
	else {
	
		$mesDesdeNumero = $formato->format($registro['Ausencia']['desde'], array("type"=>"mes"));
		$mesHastaNumero = $formato->format($registro['Ausencia']['hasta'], array("type"=>"mes"));
		$anoDesdeNumero = $formato->format($registro['Ausencia']['desde'], array("type"=>"ano"));
		$anoHastaNumero = $formato->format($registro['Ausencia']['hasta'], array("type"=>"ano"));
		
		for($i=$anoDesdeNumero; $i<=$anoHastaNumero; $i++) {
			if($i==$anoDesdeNumero) {
				$mesInicio = $mesDesdeNumero;
			}
			else {
				$mesInicio = 1;
			}
			
			if($i==$anoHastaNumero) {
				$mesFin = $mesHastaNumero;
			}
			else {
				$mesFin = 12;
			}
			
			for($j=$mesInicio; $j<=$mesFin; $j++) {
				$mes = str_pad($j, 2, "0", STR_PAD_LEFT);

				if($j==$mesDesdeNumero) {
					$fechaDesde = $i . "-" . $mes . "-" . $formato->format($registro['Ausencia']['desde'], array("type"=>"dia"));
				}
				else {
					$fechaDesde = $i . "-" . $mes . "-01";
				}

				if($j==$mesHastaNumero) {
					$fechaHasta = $i . "-" . $mes . "-" . $formato->format($registro['Ausencia']['hasta'], array("type"=>"dia"));
				}
				else {
					$ultimoDiaDelMes = $formato->format("", array("type"=>"ultimoDiaDelMes", "ano"=>$anoDesde, "mes"=>$mes));
					$fechaHasta = $i . "-" . $mes . "-" . $ultimoDiaDelMes;
				}

				$diferencia = $formato->diferenciaEntreFechas($fechaHasta, $fechaDesde);

				$fechaDummy = $anoDesde . "-" . $mes . "-01";
				$mesEnLetras = $formato->format($fechaDummy, array("type"=>"mesEnLetras"));
				$datos[$mesEnLetras . " / " . $i][$empleador][$trabajador][] =
					array(	"desde"	=>$fechaDesde,
							"hasta"	=>$fechaHasta,
							"dias"	=>$diferencia['dias']+1);
			}
		}
	}
}

/**
* Pinto la tabla.
*/
$table = "<table>";
$table .= "<tr><td colspan='10' class='derecha'><span class='color_rojo'>Total de faltas en el periodo: ##TotalGeneral##</span></td></tr>";
$total = 0;
foreach($datos as $k=>$v) {
	$table .= "<tr><td colspan='10'>&nbsp;</td></tr>";
	$table .= "<tr><th class='izquierda' colspan='4'>" . $k . "</th><th class='derecha'>Mes: ##TotalMes##</th></tr>";
	$totalMes = 0;
	
	foreach($v as $k1=>$v1) {
		$table .= "<tr><td colspan='4'>" . $k1 . "</td><td class='derecha'>Empleador: <span class='color_rojo'>##TotalEmpleador##</span></td></tr>";
		$totalEmpleador = 0;
		
		foreach($v1 as $k2=>$v2) {
			$totalTrabajador = 0;
			foreach($v2 as $k3=>$v3) {
				$table .= "<tr>";
				$table .= "<td>&nbsp;</td>";
				$table .= "<td>" . $k2 . "</td>";
				$table .= "<td>" . $formato->format($v3['desde'], "db2helper") . "</td>";
				$table .= "<td>" . $formato->format($v3['hasta'], "db2helper") . "</td>";
				$table .= "<td class='derecha'>" . $v3['dias'] . "</td>";
				$table .= "</tr>";
				$totalTrabajador += $v3['dias'];
				if(count($v2)-1 == $k3) {
					$table .= "<tr><td colspan='10' class='derecha'>Trabajador: <span class='color_rojo'>" . $totalTrabajador . "</span></td></tr>";
					$totalEmpleador += $totalTrabajador;
				}
			}
		}
		$table = str_replace("##TotalEmpleador##", $totalEmpleador, $table);
		$totalMes += $totalEmpleador;
	}
	$table = str_replace("##TotalMes##", $totalMes, $table);
	$total += $totalMes;
}
$table .= "</table>";
$table = str_replace("##TotalGeneral##", $total, $table);

echo $table;
?>