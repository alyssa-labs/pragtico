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
 * @version			$Revision: 1282 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-04-26 09:46:05 -0300 (lun 26 de abr de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
if (!empty($data)) {
    
	$documento->create(array('password' => false, 'title' => 'Solicitud de Tarjetas de Debito'));

	$documento->setCellValue('A', 'Cuil', 'title');
	$documento->setCellValue('B', 'Tipo Documento', 'title');
    $documento->setCellValue('C', 'Numero Documento', 'title');
    $documento->setCellValue('D', 'Apellido', 'title');
    $documento->setCellValue('E', 'Nombre', 'title');
    $documento->setCellValue('F', 'Dicreccion', 'title');
    $documento->setCellValue('G', 'Numero', 'title');
    $documento->setCellValue('H', 'Ciudad', 'title');
    $documento->setCellValue('I', 'Localidad', 'title');
    $documento->setCellValue('J', 'Provincia', 'title');
    $documento->setCellValue('K', 'Telefono', 'title');
    $documento->setCellValue('L', 'Sexo', 'title');
    $documento->setCellValue('M', 'Estado Civil', 'title');
    $documento->setCellValue('N', 'Ingreso', 'title');
    $documento->setCellValue('O', 'Nacimiento', 'title');
    $documento->setCellValue('P', 'Codigo Postal', 'title');
    $documento->setCellValue('Q', 'Empleador', 'title');
	
    
	foreach($data as $record) {

        $documento->moveCurrentRow();
        $documento->setCellValue('A', $record['Trabajador']['cuil']);
        $documento->setCellValue('B', $record['Trabajador']['tipo_documento']);
        $documento->setCellValue('C', $record['Trabajador']['numero_documento']);
        $documento->setCellValue('D', $record['Trabajador']['apellido']);
        $documento->setCellValue('E', $record['Trabajador']['nombre']);
        $documento->setCellValue('F', $record['Trabajador']['direccion']);
        $documento->setCellValue('G', $record['Trabajador']['numero']);
        if (empty($record['Trabajador']['Localidad']['nombre'])) {
            $record['Trabajador']['Localidad']['nombre'] = '';
        }
        if (empty($record['Trabajador']['Localidad']['Provincia']['nombre'])) {
            $record['Trabajador']['Localidad']['Provincia']['nombre'] = '';
        }
        $documento->setCellValue('H', $record['Trabajador']['ciudad']);
        $documento->setCellValue('I', $record['Trabajador']['Localidad']['nombre']);
        $documento->setCellValue('J', $record['Trabajador']['Localidad']['Provincia']['nombre']);
        $documento->setCellValue('K', (!empty($record['Trabajador']['telefono']))?$record['Trabajador']['telefono']:'');
        $documento->setCellValue('L', $record['Trabajador']['sexo']);
        $documento->setCellValue('M', $record['Trabajador']['estado_civil']);
        $documento->setCellValue('N', (!empty($record['Relacion']['ingreso']))?$formato->format($record['Relacion']['ingreso'], 'date'):'');
        $documento->setCellValue('O', $formato->format($record['Trabajador']['nacimiento'], 'date'));
        $documento->setCellValue('P', $record['Trabajador']['codigo_postal']);
        $documento->setCellValue('Q', (!empty($record['Empleador']['nombre']))?$record['Empleador']['nombre']:'');
        
	}

    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-marcar'] = array('aclaracion' => 'Indica si deben marcarse como "Solicitud en Procesos" a aquellos trabajadores a los cuales se les solicite la Tarjeta de Debito.', 'type' => 'radio', 'options' => array('si' => 'Si', 'no' => 'No'), 'value' => 'si');
    $options = array('title' => 'Solicitud Tarjetas de Debito');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}

?>