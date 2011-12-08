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
 * @version         $Revision: 1445 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-05-26 12:17:44 -0300 (Thu, 26 May 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
if (!empty($registros)) {

    $documento->create(array(
		'password' 		=> true,
		'filters'		=> $documento->getReportFilters($this->data),
		'title' 		=> 'Ingreso de actualizaciones de escala del convenio'));

    $documento->setCellValue('A', 'Id', array('title' => '15'));
	$documento->setCellValue('B', 'Convenio', array('title' => '40'));
    $documento->setCellValue('C', 'Categoria', array('title' => '40'));
    $documento->setCellValue('D', 'Desde', array('title' => '15'));
    $documento->setCellValue('E', 'Hasta', array('title' => '15'));
	$documento->setCellValue('F', 'Costo', array('title' => '15'));

    /** Body */
    foreach ($registros as $registro) {

        $documento->setCellValueFromArray(
            array(
				$registro['ConveniosCategoria']['id'],
                $registro['Convenio']['nombre'],
				$registro['ConveniosCategoria']['nombre']
			)
		);


		$row = $documento->getCurrentRow();
		$documento->setDataValidation('D' . $row, 'date');
		$documento->setDataValidation('E' . $row, 'date');
		$documento->setDataValidation('F' . $row, 'decimal');
		
    }


	//$documento->activeSheet->getColumnDimension('A')->setVisible(false);



    $documento->save($fileFormat);



} else {

    /**
    * Especifico los campos para ingresar las condiciones.
    */

	$condiciones['Condicion.ConveniosCategoria-convenio_id'] = array(
		'lov' 			=> array(
			'controller'	=> 'convenios',
			'camposRetorno'	=> array('Convenio.numero', 'Convenio.nombre')
		)
	);

    $condiciones['Condicion.Novedad-formato'] = array('type' => 'radio');
    $fieldsets[] = array('campos' => $condiciones);
    
    $fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Categorias', 'imagen' => 'categorias.gif')));
    $opcionesTabla['tabla']['omitirMensajeVacio'] = true;
    $accionesExtra['opciones'] = array('acciones'=>array($appForm->link('Generar', null, array('class' => 'link_boton', 'id' => 'confirmar', 'title' => 'Confirma las liquidaciones seleccionadas'))));
    $botonesExtra['opciones'] = array('botones'=>array('limpiar', $appForm->submit('Generar', array('title' => 'Genera la planilla base para importar novedades'))));
    echo $this->element('index/index', array('botonesExtra' => $botonesExtra, 'condiciones' => $fieldset, 'opcionesForm' => array('action' => 'generar_planilla'), 'opcionesTabla' => $opcionesTabla));


}

?>