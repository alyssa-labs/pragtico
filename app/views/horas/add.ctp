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
 * @version			$Revision: 1423 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Hora.id'] = array();
$campos['Hora.periodo'] = array('type' => 'periodo');
$campos['Hora.relacion_id'] = array(
	'label' => 'Relacion',
	'lov'	=> array(	'controller'		=> 	'relaciones',
						'seleccionMultiple'	=> 	0,
						'camposRetorno'		=> 	array(
							'Trabajador.nombre',
							'Trabajador.apellido',
							'Empleador.nombre')));
$campos['Hora.cantidad'] = array();
$campos['Hora.tipo'] = array();
$campos['Hora.liquidacion_tipo'] = array('label' => 'Tipo Liquidacion', 'aclaracion' => 'Indica para que tipo de liquidacion sera tenida en cuenta al momento de preliquidar');
$campos['Hora.estado'] = array('type' => 'radio');
$campos['Hora.observacion'] = array();
$fieldset = $appForm->pintarFieldsets(
	array(array('campos' => $campos)),
	array('div' => array('class' => 'unica'),
	'fieldset' => array('legend' => 'horas manual', 'imagen' => 'horas.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));

?>