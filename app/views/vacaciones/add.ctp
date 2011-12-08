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
 * @version			$Revision: 1043 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-10-01 15:51:10 -0300 (jue 01 de oct de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Vacacion.id'] = array();
$campos['Vacacion.relacion_id'] = array(
    'label' => 'Relacion',
    'lov'   => array(
        'controller'            => 'relaciones',
        'seleccionMultiple'	    => 0,
        'camposRetorno'	        => array(
            'Trabajador.nombre',
            'Trabajador.apellido',
            'Empleador.nombre')));

$campos['Vacacion.periodo'] = array('type' => 'periodo', 'periodo' => array('A'));
$campos['Vacacion.corresponde'] = array();
$campos['Vacacion.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'Vacaciones', 'imagen' => 'vacaciones.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));

?>