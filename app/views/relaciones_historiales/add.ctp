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
 * @version         $Revision: 405 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-03-16 14:05:37 -0300 (Mon, 16 Mar 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['RelacionesHistorial.id'] = array();
$campos['RelacionesHistorial.relacion_id'] = array(
    'lov' => array(
        'controller'          => 'relaciones',
        'seleccionMultiple'   => 0,
        'camposRetorno'       => array('Empleador.nombre', 'Trabajador.apellido')));

$campos['RelacionesHistorial.egresos_motivo_id'] = array(
    'empty'         => true,
    'options'       => 'listable',
    'order'         => 'EgresosMotivo.motivo',
    'displayField'  => 'EgresosMotivo.motivo',
    'model'         => 'EgresosMotivo',
    'label'         => 'Motivo');

$campos['RelacionesHistorial.estado'] = array();
$campos['RelacionesHistorial.liquidacion_final'] = array('aclaracion' => 'Indica si se habilita la realizacion de Liquidacion Final.');
$campos['RelacionesHistorial.fin'] = array();

$campos['RelacionesHistorial.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets(
    $fieldsets,
    array('div'     => array('class' => 'unica'),
    'fieldset'      => array('legend' => 'historial de la relacion laboral', 'imagen' => 'historicos.gif')));


/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>