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
 * @version			$Revision: 236 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar, 27 ene 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
if (!empty($data)) {

    if (empty($groupParams)) {
        $left = sprintf("&L%s\n%s - %s\nCP: %s - %s - %s\nCUIT: %s\n%s",
            $employer['Empleador']['nombre'],
            $employer['Empleador']['direccion'],
            $employer['Empleador']['barrio'],
            $employer['Empleador']['codigo_postal'],
            $employer['Empleador']['ciudad'],
            $employer['Empleador']['pais'],
            $employer['Empleador']['cuit'],
			$employer['Actividad']['nombre']);
        $center = "&CLibro Especial de Sueldos - Art. 52 Ley 20744";
    } else {
        $left = sprintf("&L%s\n%s - %s\nCP: %s - %s - %s\nCUIT: %s",
            $groupParams['nombre_fantasia'],
            $groupParams['direccion'],
            $groupParams['barrio'],
            $groupParams['codigo_postal'],
            $groupParams['ciudad'],
            $groupParams['pais'],
            $groupParams['cuit']);
        $center = "&CLibro Especial de Sueldos" . $groupParams['libro_sueldos_encabezado'];
    }
	$documento->create(array('header' => $left . $center, 'password' => true));

	$pageMargins = $documento->doc->getActiveSheet()->getPageMargins();
	$pageMargins->setBottom(0.2);
	$pageMargins->setLeft(0.2);
	$pageMargins->setRight(0.2);

	$styleBorderBottom = array('style' => array(
		'borders' => array( 'bottom'     => array('style' => PHPExcel_Style_Border::BORDER_DASHDOT))));

	
	$documento->setWidth('A', 30);
	$documento->setWidth('B', 7);
	$documento->setWidth('C', 13);
	$documento->setWidth('D', 3);
	$documento->setWidth('E', 30);
	$documento->setWidth('F', 7);
	$documento->setWidth('G', 13);
	$documento->setWidth('H', 3);
	$documento->setWidth('I', 30);
	$documento->setWidth('J', 7);
	$documento->setWidth('K', 13);

        
    $fila = 0;
	$employerFlag = null;
	$pageCount = $startPage - 1;
    $recordCount = 0;
    $k = 0;
	foreach ($data as $record) {
        $k++;
        
        /** Must print emplyer only when group is selected */
		if ($employerFlag !== $record['Liquidacion']['empleador_cuit']) {
			$employerFlag = $record['Liquidacion']['empleador_cuit'];

			$recordCount = 0;
			$documento->doc->getActiveSheet()->setBreak('A' . $fila, PHPExcel_Worksheet::BREAK_ROW);
			$fila++;
			$pageCount++;
			$documento->setCellValue('K' . $fila, 'Hoja ' . $pageCount);

            if (empty($employer)) {
                $fila+=2;
                $documento->setCellValue('A' . $fila, 'Empresa Usuario:');
                $documento->setCellValue('B' . $fila, $record['Liquidacion']['empleador_nombre'], 'bold');
                $documento->setCellValue('I' . $fila, 'Periodo: ' . $formato->format($periodo, array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'ucfirst')), 'bold');
                
                $fila++;
                $documento->setCellValue('A' . $fila, 'CUIT:');
                $documento->setCellValue('B' . $fila, $record['Liquidacion']['empleador_cuit'], 'bold');
                
                $fila++;
                $documento->setCellValue('A' . $fila, 'Direccion:');
                $documento->setCellValue('B' . $fila, $record['Liquidacion']['empleador_direccion']);
                
                $fila+=2;
            } else {
                $documento->setCellValue('I' . $fila, 'Periodo: ' . $formato->format($periodo, array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'ucfirst')), 'bold');
                $fila++;
            }
		}
		$recordCount++;
		
        $fila++;
		$documento->setCellValue('A' . $fila, 'CUIL: ' . $record['Liquidacion']['trabajador_cuil'] . ' / Legajo: ' . $record['Liquidacion']['relacion_legajo']);
		$documento->setCellValue('E' . $fila, 'Apellido y Nombre: ' . $record['Liquidacion']['trabajador_apellido'] . ' ' . $record['Liquidacion']['trabajador_nombre']);
        $documento->setCellValue('I' . $fila, 'Ingreso: ' . $formato->format($record['Liquidacion']['relacion_ingreso'], 'date'));

        $fila++;
		$documento->setCellValue('A' . $fila, 'Periodo: ' . $record['Liquidacion']['periodo']);
		$documento->setCellValue('E' . $fila, 'Contrato: ' . $record['Liquidacion']['relacion_modalidad_nombre']);
        if ($record['Liquidacion']['relacion_basico'] > 0) {
            $salary = $record['Liquidacion']['relacion_basico'];
        } else {
            $salary = $record['Liquidacion']['convenio_categoria_costo'];
        }
        $documento->setCellValue('I' . $fila, 'Suel/Jorn.: $ ' . $formato->format($salary));

        $fila++;
        $documento->setCellValue('A' . $fila, 'Categoria: ' . $record['Liquidacion']['convenio_categoria_nombre']);

        $egreso = '';
        if (!empty($record['Liquidacion']['relacion_egreso']) && $record['Liquidacion']['relacion_egreso'] !== '0000-00-00') {
            $egreso = $formato->format($record['Liquidacion']['relacion_egreso'], 'date');
        }
		$documento->setCellValue('E' . $fila, 'Baja: ' . $egreso);
		if (empty($egreso)) {
			$documento->setCellValue('I' . $fila, 'Estado: Activo');
		} else {
			$documento->setCellValue('I' . $fila, 'Estado: Inactivo');
		}

		
        $fila++;
		$documento->setCellValue('A' . $fila . ':C' . $fila, 'Remunerativo', array('title' => 30));
		$documento->setCellValue('B' . $fila, '');
		$documento->setCellValue('C' . $fila, '');
		$documento->setCellValue('E' . $fila . ':G' . $fila, 'Deduccion', array('title' => 30));
		$documento->setCellValue('F' . $fila, '');
		$documento->setCellValue('G' . $fila, '');
		$documento->setCellValue('I' . $fila . ':K' . $fila, 'No Remunerativo', array('title' => 30));
		$documento->setCellValue('J' . $fila, '');
		$documento->setCellValue('K' . $fila, '');
		
		$fila++;
		$documento->setCellValue('A' . $fila, 'Descripcion');
		$documento->setCellValue('B' . $fila, 'Cant.', 'right');
		$documento->setCellValue('C' . $fila, 'Importe', 'right');
		
		$documento->setCellValue('E' . $fila, 'Descripcion');
		$documento->setCellValue('F' . $fila, 'Cant.', 'right');
		$documento->setCellValue('G' . $fila, 'Importe', 'right');
		
		$documento->setCellValue('I' . $fila, 'Descripcion');
		$documento->setCellValue('J' . $fila, 'Cant.', 'right');
		$documento->setCellValue('K' . $fila, 'Importe', 'right');

		$detailFlag = null;
		$initialRow = $fila;
		$maxCount['Remunerativo'] = 0;
        $maxCount['Deduccion'] = 0;
        $maxCount['No Remunerativo'] = 0;
		foreach ($record['LiquidacionesDetalle'] as $detail) {

			if($detail['concepto_imprimir'] === 'Si' || ($detail['concepto_imprimir'] === 'Solo con valor' && abs($detail['valor']) > 0)) {
				if ($detailFlag !== $detail['concepto_tipo']) {
					$detailFlag = $detail['concepto_tipo'];
					$fila = $initialRow;
				}
                $fila++;

				if ($detail['concepto_tipo'] === 'Remunerativo') {
					$documento->setCellValue('A' . $fila, $detail['concepto_nombre']);
					$documento->setCellValue('B' . $fila, $detail['valor_cantidad']);
					$documento->setCellValue('C' . $fila, $detail['valor'], 'currency');
				} elseif ($detail['concepto_tipo'] === 'Deduccion') {
					$documento->setCellValue('E' . $fila, $detail['concepto_nombre']);
					$documento->setCellValue('F' . $fila, $detail['valor_cantidad']);
					$documento->setCellValue('G' . $fila, $detail['valor'], 'currency');
				} elseif ($detail['concepto_tipo'] === 'No Remunerativo') {
					$documento->setCellValue('I' . $fila, $detail['concepto_nombre']);
					$documento->setCellValue('J' . $fila, $detail['valor_cantidad']);
					$documento->setCellValue('K' . $fila, $detail['valor'], 'currency');
				}
                $maxCount[$detail['concepto_tipo']]++;
			}
		}

        $count = 0;
        foreach ($maxCount as $c) {
            if ($c > $count) {
                $count = $c;
            }
        }
		$fila = $initialRow + $count + 1;
		$documento->setCellValue('A' . $fila, 'Totales', 'bold');
		$documento->setCellValue('C' . $fila, $record['Liquidacion']['remunerativo'], 'total');
		$documento->setCellValue('G' . $fila, $record['Liquidacion']['deduccion'], 'total');
		$documento->setCellValue('K' . $fila, $record['Liquidacion']['no_remunerativo'], 'total');

        $fila++;
		$documento->setCellValue('J' . $fila, 'Total Neto:', array('bold', 'right'));
        $documento->setCellValue('K' . $fila, $record['Liquidacion']['total_pesos'], 'total');

        $fila++;
		$documento->setCellValue('A' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('B' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('C' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('D' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('E' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('F' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('G' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('H' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('I' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('J' . $fila, '', $styleBorderBottom);
		$documento->setCellValue('K' . $fila, '', $styleBorderBottom);
        $fila++;

		if ($recordCount === 3 && $k < count($data) - 1) {
			$recordCount = 0;
			$documento->doc->getActiveSheet()->setBreak('A' . $fila, PHPExcel_Worksheet::BREAK_ROW);
            $fila++;
			$pageCount++;
            $documento->setCellValue('I' . $fila, 'Periodo: ' . $formato->format($periodo, array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'ucfirst')), 'bold');
			$documento->setCellValue('K' . $fila, 'Hoja ' . $pageCount);
            $fila++;
		}
	}

	if (!empty($fileName)) {
		$documento->save($fileName);
	} else {
		$documento->save($fileFormat);
	}

} else {

    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => 0,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

    $conditions['Condicion.Bar-area_id'] = array(
            'label' => 'Area',
            'mask'          =>  '%s, %s',
            'lov'   => array('controller'   => 'areas',
                            'camposRetorno' => array(   'Empleador.nombre',
                                                        'Area.nombre')));
    
    $conditions['Condicion.Bar-tipo'] = array('label' => 'Tipo', 'multiple' => 'checkbox', 'type' => 'select', 'options' => $types);
    $conditions['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo', 'type' => 'periodo', 'periodo' => array('1Q', '2Q', 'M', '1S', '2S', 'F'));

    $conditions['Condicion.Bar-start_page'] = array('label' => 'Hoja Inicial', 'type' => 'text', 'value' => '1');
    $options = array('title' => 'Libro de Sueldos');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));


}
 
?>