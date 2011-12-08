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
 * @version         $Revision: 1282 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-04-26 09:46:05 -0300 (lun 26 de abr de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
if (!empty($data)) {

    $documento->create(array('password' => false, 'title' => 'Riesgo Indemnizatorio'));
    
    $documento->setWidth('A', '50');
    $documento->setWidth('B', '30');
    $documento->setWidth('C', '30');
            
    $documento->setCellValue('A', 'Empleador: ', 'right');
    $documento->setCellValue('B', $relacion['Empleador']['nombre'], 'bold');
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Cuil: ', 'right');
    $documento->setCellValue('B', $relacion['Trabajador']['cuil'], 'bold');
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Trabajador: ', 'right');
    $documento->setCellValue('B', $relacion['Trabajador']['nombre'] .', ' . $relacion['Trabajador']['apellido'], 'bold');
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Ingreso: ', 'right');
	$d = explode('-', $relacion['Relacion']['ingreso']);
    $documento->setCellValue('B', '=DATE(' . $d[0] . ', ' . $d[1] . ', ' . $d[2] . ')', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('ingreso', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Egreso: ', 'right');
 	if (!empty($relacion['RelacionesHistorial'][0]['fin'])) {
		$d = explode('-', $relacion['RelacionesHistorial'][0]['fin']);
		$egreso = '=DATE(' . $d[0] . ', ' . $d[1] . ', ' . $d[2] . ')';
	} else {
		$egreso = '';
	}
    $documento->setCellValue('B', $egreso, 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('egreso', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Suspension: ', 'right');
    $documento->setCellValue('B', '0', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_suspension', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Excedencia: ', 'right');
    $documento->setCellValue('B', '0', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_excedencia', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Antiguedad: ', 'right');
    $documento->setCellValue('B', '=((egreso - ingreso) + 1) - (dias_suspension + dias_excedencia)', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_antiguedad', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Años Servicio: ', 'right');
    $documento->setCellValue('B', '=INT(dias_antiguedad / 365.25)', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('anos_servicio', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Meses Servicio: ', 'right');
    $documento->setCellValue('B', '=INT((dias_antiguedad - (anos_servicio * 365.25)) / 30.44)', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('meses_servicio', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Servicio: ', 'right');
    $documento->setCellValue('B', '=INT(dias_antiguedad - (anos_servicio * 365.25) - (meses_servicio * 30.44))', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_servicio', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Años Antiguedad a Computar: ', 'right');
    $documento->setCellValue('B', '=IF(meses_servicio<3, anos_servicio, (anos_servicio + 1))', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('anos_antiguedad', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Semestre: ', 'right');
    $documento->setCellValue('B', '=IF(MONTH(egreso) >= 6, "Segundo", "Primero")', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('semestre', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Semestre: ', 'right');
    $documento->setCellValue('B', '=IF(MONTH(egreso) >= 6, 184, 181)', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_semestre', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Trabajados en el Semestre: ', 'right');
    $documento->setCellValue('B', '=IF(semestre = "Segundo", egreso - DATE(YEAR(egreso), 1, 1) - 181, egreso - DATE(YEAR(egreso), 1, 1)) + 1', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_trabajados_en_el_semestre', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Meses de Preaviso: ', 'right');
    $documento->setCellValue('B', '=IF(anos_antiguedad >= 5, 2, 1)', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('meses_de_preaviso', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Integrativos: ', 'right');
    $documento->setCellValue('B', '=30 - DAY(egreso)', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_integrativos', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Corridos Trabajados: ', 'right');
    $documento->setCellValue('B', '= (egreso - IF(ingreso > DATE(YEAR(egreso), 1, 1), ingreso, DATE(YEAR(egreso), 1, 1)))+1', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_corridos_trabajados', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Vacaciones: ', 'right');
    $documento->setCellValue('B', '=IF(AND(MONTH(ingreso)>6,YEAR(ingreso)=YEAR(egreso),DAY(ingreso)>=1),INT(IF(NETWORKDAYS(ingreso,egreso)=132,14,NETWORKDAYS(ingreso,egreso)/20)),IF(AND(MONTH(ingreso)<6,YEAR(ingreso)=YEAR(egreso)),14,IF((YEAR(egreso)-YEAR(ingreso))<=5,14,IF((YEAR(egreso)-YEAR(ingreso))<=10,21,IF((YEAR(egreso)-YEAR(ingreso))<=15,28,35)))))', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_vacaciones', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Dias Vacaciones No Goz.: ', 'right');
    $documento->setCellValue('B', '=(dias_vacaciones * dias_corridos_trabajados) / 365', 'bold');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('dias_vacaciones_no_gozadas', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow(4);
    $initialRow = $documento->getCurrentRow() + 1;
    foreach ($data as $v) {
        $documento->moveCurrentRow();
        $documento->setCellValue('A', $formato->format($v['Liquidacion']['ano'] . str_pad($v['Liquidacion']['mes'], 2, '0', STR_PAD_LEFT), array('type' => 'periodoEnLetras', 'short' => true)) . ' ', 'right');
        $documento->setCellValue('B', $v['Liquidacion']['total'], 'total');
    }
    $finalRow = $documento->getCurrentRow();
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sueldos', $documento->activeSheet, 'B' . $initialRow . ':B' . $finalRow));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Total: ', 'right');
    $documento->setCellValue('B', '=SUM(sueldos)', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('suma_sueldos', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Promedio: ', 'right');
    $documento->setCellValue('B', '=suma_sueldos / COUNT(sueldos)', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('promedio_sueldo', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Mayor: ', 'right');
    $documento->setCellValue('B', '=MAX(sueldos)', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('mayor_sueldo', $documento->activeSheet, 'B' . $documento->getCurrentRow()));

    $documento->moveCurrentRow(3);
    $documento->setCellValue('B', 'Sobre Sueldo Mayor', 'right');
    $documento->setCellValue('C', 'Sobre Sueldo Promedio', 'right');
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'SAC Proporcional: ', 'right');
    $documento->setCellValue('B', '=((mayor_sueldo / 2) / dias_semestre) * dias_trabajados_en_el_semestre', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sac_proporcional_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=((promedio_sueldo / 2) / dias_semestre) * dias_trabajados_en_el_semestre', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sac_proporcional_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Preaviso: ', 'right');
    $documento->setCellValue('B', '=mayor_sueldo * meses_de_preaviso', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('preaviso_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=promedio_sueldo * meses_de_preaviso', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('preaviso_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'SAC s/ Preaviso: ', 'right');
    $documento->setCellValue('B', '=preaviso_mayor / 12', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sac_sobre_preaviso_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=preaviso_promedio / 12', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sac_sobre_preaviso_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));

    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Indemnizacion: ', 'right');
    $documento->setCellValue('B', '=mayor_sueldo * anos_antiguedad', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('indemnizacion_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=promedio_sueldo * anos_antiguedad', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('indemnizacion_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Integrativo Mes Despido: ', 'right');
    $documento->setCellValue('B', '=(mayor_sueldo / 30) * dias_integrativos', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('integrativo_mes_despido_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=(promedio_sueldo / 30) * dias_integrativos', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('integrativo_mes_despido_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'SAC s/ Integrativo: ', 'right');
    $documento->setCellValue('B', '=integrativo_mes_despido_mayor / 12', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sac_sobre_integrativo_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=integrativo_mes_despido_promedio / 12', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('sac_sobre_integrativo_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Vacaciones No Gozadas: ', 'right');
    $documento->setCellValue('B', '=(mayor_sueldo / 25) * dias_vacaciones_no_gozadas', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('vacaciones_no_gozadas_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=(promedio_sueldo / 25) * dias_vacaciones_no_gozadas', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('vacaciones_no_gozadas_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow(2);
    $documento->setCellValue('A', 'Total Indemnizaciones: ', 'right');
    $documento->setCellValue('B', '=preaviso_mayor + sac_sobre_preaviso_mayor + indemnizacion_mayor + integrativo_mes_despido_mayor + sac_sobre_integrativo_mayor', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('total_indemnizaciones_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=preaviso_promedio + sac_sobre_preaviso_promedio + indemnizacion_promedio + integrativo_mes_despido_promedio + sac_sobre_integrativo_promedio', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('total_indemnizaciones_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow();
    $documento->setCellValue('A', 'Liquidacion Final: ', 'right');
    $documento->setCellValue('B', '=sac_proporcional_mayor + vacaciones_no_gozadas_mayor', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('liquidacion_final_mayor', $documento->activeSheet, 'B' . $documento->getCurrentRow()));
    $documento->setCellValue('C', '=sac_proporcional_promedio + vacaciones_no_gozadas_promedio', 'total');
    $documento->doc->addNamedRange(new PHPExcel_NamedRange('liquidacion_final_promedio', $documento->activeSheet, 'C' . $documento->getCurrentRow()));
    
    $documento->moveCurrentRow(2);
    $documento->setCellValue('A', 'TOTAL: ', 'right');
    $documento->setCellValue('B', '=total_indemnizaciones_mayor + liquidacion_final_mayor', 'total');
    $documento->setCellValue('C', '=total_indemnizaciones_promedio + liquidacion_final_promedio', 'total');

    $documento->moveCurrentRow(4);
    $documento->setCellValue('A', 'Observaciones:', 'bold');
    $styleArray = array(
        'font'      => array('bold' => true),
        'borders'   => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOTTED,
                'color' => array('argb' => '00000000'),
            ),
        ),
    );
    $documento->activeSheet->getStyle('A' . $documento->getCurrentRow() . ':F' . ($documento->getCurrentRow() + 6))->applyFromArray($styleArray);

    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-relacion_id'] = array( 'lov' => array(
            'controller'        => 'relaciones',
            'seleccionMultiple' => true,
                'camposRetorno' => array(   'Empleador.cuit',
                                            'Empleador.nombre',
                                            'Trabajador.cuil',
                                            'Trabajador.nombre',
                                            'Trabajador.apellido')));

    $options = array('title' => 'Riesgo Indemnizatorio');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>