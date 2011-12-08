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

    $documento->create(array(
		'password' 		=> true,
		'filters'		=> $documento->getReportFilters($this->data),
		'title' 		=> 'Evolucion de la Facturacion'));
    $documento->setCellValue('A', 'Cuit', array('title' => '20'));
    $documento->setCellValue('B', 'Empleador', array('title' => '35'));
    $documento->setCellValue('C', 'Area', array('title' => '35'));
    $documento->setCellValue('D', 'Fact. ' . $periods[0], array('title' => '20'));
    $documento->setCellValue('E', 'Fact. ' . $periods[1], array('title' => '20'));

    $initialRow = $documento->getCurrentRow();

    /** Body */
    foreach ($data as $cuit => $record) {

        foreach ($record as $area => $values) {

            $r = null;
            foreach ($values as $period => $d) {
                if (empty($r)) {
                    $r = array(
                        $d['Empleador']['cuit'],
                        $d['Empleador']['nombre'],
                        $d['Area']['nombre']);
                }
                $r[] = array('value' => $d['Factura']['total'], 'options' => 'currency');
            }
            $documento->setCellValueFromArray($r);
        }
    }
    $documento->moveCurrentRow();
    $documento->setCellValue('D', sprintf('=SUM(D%s:D%s)', $initialRow + 1, $documento->getCurrentRow() - 1), 'total');
    $documento->setCellValue('E', sprintf('=SUM(E%s:E%s)', $initialRow + 1, $documento->getCurrentRow() - 1), 'total');

    $documento->save($fileFormat);
} else {

	$conditions = null;
    $conditions['Condicion.Bar-empleador_id'] = array( 'lov' => array(
            'controller'        => 'empleadores',
            'seleccionMultiple' => true,
            'camposRetorno'     => array('Empleador.cuit', 'Empleador.nombre')));

    $conditions['Condicion.Bar-periodo_largo_partida'] = array('label' => 'Periodo', 'type' => 'periodo', 'label' => 'Periodo Partida');
    $conditions['Condicion.Bar-periodo_largo_final'] = array('label' => 'Periodo', 'type' => 'periodo', 'label' => 'Periodo Final');

    $options = array('title' => 'Evolucion de la Facturacion');
    echo $this->element('reports/conditions', array('aditionalConditions' => $conditions, 'options' => $options));
}
 
?>