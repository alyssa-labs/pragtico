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
 * @version			$Revision: 367 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-05 13:49:57 -0200 (jue 05 de mar de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['EmpleadoresCoeficiente.id'] = array();
$campos['EmpleadoresCoeficiente.empleador_id'] = array(	"lov"=>array("controller"	=>	"empleadores",
																"seleccionMultiple"	=> 	0,
																	"camposRetorno"	=>	array(	"Empleador.cuit",
																								"Empleador.nombre")));
$campos['EmpleadoresCoeficiente.coeficiente_id'] = array(	"lov"=>array("controller"	=>	"coeficientes",
																	"seleccionMultiple"	=> 	0,
																		"camposRetorno"	=>	array(	"Coeficiente.nombre",
																									"Coeficiente.valor")));
$campos['EmpleadoresCoeficiente.porcentaje'] = array('aclaracion' => "Indica el porcentaje a sumar o restar al coeficiente valor del coeficiente.");
$campos['EmpleadoresCoeficiente.observacion'] = array();
$fieldsets[] = array('campos' => $campos);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "coeficiente del empleador", 'imagen' => 'coeficientes.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>