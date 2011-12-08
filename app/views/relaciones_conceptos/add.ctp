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
 * @version			$Revision: 405 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-16 14:05:37 -0300 (lun 16 de mar de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['RelacionesConcepto.id'] = array();
$campos['RelacionesConcepto.relacion_id'] = array(	"lov"=>array("controller"	=>	"relaciones",
															"seleccionMultiple"	=> 	0,
															"camposRetorno"		=> 	array(	"Empleador.nombre",
																							"Trabajador.apellido")));
																				
$campos['RelacionesConcepto.concepto_id'] = array(	"lov"=>array("controller"	=>	"conceptos",
															"seleccionMultiple"	=> 	0,
															"camposRetorno"		=> 	array(	"Concepto.codigo",
																							"Concepto.nombre")));
$campos['RelacionesConcepto.desde'] = array();
$campos['RelacionesConcepto.hasta'] = array();
$campos['RelacionesConcepto.formula'] = array();
$campos['RelacionesConcepto.jerarquia'] = array('type' => 'soloLectura');
$campos['RelacionesConcepto.formula_aplicara'] = array('type' => 'soloLectura', 'label' => 'Formula que se Aplicara');

$campos['RelacionesConcepto.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "concepto de la relacion laboral", 'imagen' => 'conceptos.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = array('format' 	=> '%s %s (%s)', 
			  'content' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => $miga));
?>