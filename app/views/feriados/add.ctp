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
 * @version			$Revision: 293 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-19 12:21:14 -0200 (Thu, 19 Feb 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Feriado.id'] = array();
$campos['Feriado.fecha_origen'] = array();
$campos['Feriado.fecha_efectiva'] = array();
$campos['Feriado.nombre'] = array();
$campos['Feriado.descripcion'] = array();
$campos['Feriado.trasladable'] = array();
$campos['Feriado.tipo'] = array();
$campos['Feriado.descripcion_tipo'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'feriados.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = array('format' 	=> '(%s) %s', 
			  'content' => array('Feriado.fecha_origen', 'Feriado.nombre'));

echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => $miga));
?>