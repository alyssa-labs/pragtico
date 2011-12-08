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

    if (count(User::getUserGroups('all')) > 1) {
        $groupParams = User::getGroupParams();
        $header = '';
        if (!empty($groupParams)) {
            $header = sprintf("&L%s\n%s - %s\nCP: %s - %s - %s\nCUIT: %s",
                $groupParams['nombre_fantasia'],
                $groupParams['direccion'],
                $groupParams['barrio'],
                $groupParams['codigo_postal'],
                $groupParams['ciudad'],
                $groupParams['pais'],
                $groupParams['cuit']);
        }
        $labelEmployer = 'Usuario';
    } else {
        $labelEmployer = 'Empleador';
    }

    $documento->create(array('password' => false, 'header' => $header));
    $documento->activeSheet->getDefaultStyle()->getFont()->setSize(8);

    /** Body */
    App::import('Vendor', 'dates', 'pragmatia');
    foreach ($data as $record) {

        for ($i = 0; $i < 2; $i++) {
            foreach ($record['VacacionesDetalle'] as $detail) {

                $documento->moveCurrentRow(2);
                $documento->setCellValue('K', 'Córdoba, ' . $formato->format(Dates::dateAdd($detail['desde'], -45), array('type' => 'date', 'format' => 'd/m/Y')), 'right');
                $documento->moveCurrentRow(5);

                $documento->setCellValue('F:I', 'NOTIFICACION DE VACACIONES', 'title');

                $documento->moveCurrentRow(3);
                $documento->setCellValue('A', 'Sr./a.:');
                $documento->moveCurrentRow(1);
                $documento->setCellValue('A', 'Apellido y Nombre:');
                $documento->setCellValue('D', $record['Relacion']['Trabajador']['apellido'] . ', ' . $record['Relacion']['Trabajador']['nombre'], 'bold');
                $documento->moveCurrentRow(1);
                $documento->setCellValue('A', 'Documento:');
                $documento->setCellValue('D:E', $record['Relacion']['Trabajador']['numero_documento'] . ' ', 'bold');
                $documento->moveCurrentRow(1);
                $documento->setCellValue('A', $labelEmployer);
                $documento->setCellValue('D', $record['Relacion']['Empleador']['nombre'], 'bold');

                $documento->moveCurrentRow(3);
                $documento->setCellValue('A', 'De nuestra mayor consideracion:');
                $documento->moveCurrentRow(2);
                $documento->setCellValue('B', 'Nos dirigimos a Ud. para poner en vuestro conocimiento la');
                $documento->moveCurrentRow(1);
                $documento->setCellValue('A', 'fecha correspondiente al periodo vacacional:');
                $documento->setCellValue('H', $record['Vacacion']['periodo'], 'bold');

                $documento->moveCurrentRow(2);
                $documento->setCellValue('B', 'Las mismas tendran el siguiente cronograma:');

                $documento->moveCurrentRow(2);
                $documento->setCellValue('C', 'Cantidad (dias corridos):');
                $documento->setCellValue('H', $detail['dias'], array('right', 'bold'));

                $documento->moveCurrentRow(2);
                $documento->setCellValue('C', 'Fecha de Inicio:');
                $documento->setCellValue('H', $formato->format($detail['desde'], array('type' => 'date', 'format' => 'd/m/Y')), array('right', 'bold'));

                $documento->moveCurrentRow(2);
                $documento->setCellValue('C', 'Fecha de Fin (inclusive):');
                $end = Dates::dateAdd($detail['desde'], $detail['dias']);
                $documento->setCellValue('H', $formato->format($end, array('type' => 'date', 'format' => 'd/m/Y')), array('right', 'bold'));

                $documento->moveCurrentRow(2);
                $documento->setCellValue('C', 'Fecha Reintegro:');
                $return = Dates::dateAdd($end, 1, 'd', array('fromInclusive' => false));
                $netxDay = date('D', strtotime($return));
                if ($netxDay == 'Sat') {
                    $return = Dates::dateAdd($end, 3, 'd', array('fromInclusive' => false));
                } elseif ($netxDay == 'Sun') {
                    $return = Dates::dateAdd($end, 2, 'd', array('fromInclusive' => false));
                }
                $documento->setCellValue('H', $formato->format($return, array('type' => 'date', 'format' => 'd/m/Y')), array('right', 'bold'));

                $documento->moveCurrentRow(4);
                $documento->setCellValue('B', 'Sin otro particular, sirva la presente nota como formal notificacion.');


                $documento->moveCurrentRow(14);
                $documento->setCellValue('C', 'Firma');
                $documento->setCellValue('F', 'Apellido y Nombre');
                $documento->setCellValue('J', 'Documento');

                $documento->moveCurrentRow();
                $documento->doc->getActiveSheet()->setBreak('A' . $documento->getCurrentRow(), PHPExcel_Worksheet::BREAK_ROW);
            }
        }
    }

    $documento->save('Excel5');
    //$documento->save('Excel2007');
}

?>