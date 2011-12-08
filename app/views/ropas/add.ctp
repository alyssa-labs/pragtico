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
 * @version			$Revision: 1008 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-22 13:39:12 -0300 (mar 22 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Ropa.id'] = array();
$campos['Ropa.fecha'] = array();
$campos['Ropa.relacion_id'] = array(	"lov"=>array(	"controller"		=>	"relaciones",
														"seleccionMultiple"	=> 	0,
															"camposRetorno"	=> 	array(	"Empleador.nombre",
																						"Trabajador.apellido")));

$campos['Ropa.observacion'] = array();
$fieldsets[] = 	array('campos' => $campos, 'opciones' => array('fieldset' => array('legend' => "Datos de la Orden", 'imagen' => 'ropas.gif')));

$campos = null;
$campos['RopasDetalle.id'] = array();
$campos['RopasDetalle.prenda'] = array("type"=>"select");
$campos['RopasDetalle.tipo'] = array();
$campos['RopasDetalle.color'] = array();
$campos['RopasDetalle.modelo'] = array();
$campos['RopasDetalle.tamano'] = array("label"=>"Tamaño / Numero");
$fieldsets[] = array('campos' => $campos, 'opciones' => array('fieldset' => array("class"=>"detail", 'legend' => "prenda", 'imagen' => 'prendas.gif')));

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "Orden para la entrega de ropa", 'imagen' => 'ropas.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = array('format' 	=> '%s %s (%s)', 
			  'content' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => $miga));

$appForm->addScript('
    detalle();
    jQuery("a.link_boton").bind("click", agregar);
');

?>