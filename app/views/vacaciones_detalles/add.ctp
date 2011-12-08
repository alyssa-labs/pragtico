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
 * @version			$Revision: 1029 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-29 09:58:08 -0300 (Tue, 29 Sep 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['VacacionesDetalle.id'] = array();
$campos['VacacionesDetalle.vacacion_id'] = array(
    'label'    => 'Vacacion',
	'lov'      => array(
        'controller'            => 'vacaciones',
        'seleccionMultiple'	    => 0,
        'camposRetorno'	        => array(
            'Trabajador.nombre',
            'Trabajador.apellido',
            'Vacacion.periodo')));
            
$campos['VacacionesDetalle.desde'] = array();
$campos['VacacionesDetalle.dias'] = array();
$campos['VacacionesDetalle.estado'] = array();
$campos['VacacionesDetalle.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'Detalles', 'imagen' => 'detalles.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));

$appForm->addScript('
    jQuery("#VacacionesDetalleEstadoLiquidada").attr("disabled", true);
');
?>