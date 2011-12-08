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
 * @version			$Revision: 238 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-28 13:06:36 -0200 (mié 28 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Cuenta.id'] = array();
$campos['Cuenta.empleador_id'] = array(	"lov"=>array("controller"	=>	"empleadores",
																"seleccionMultiple"	=> 	0,
																	"camposRetorno"	=>	array(	"Empleador.cuit",
																								"Empleador.nombre")));
$campos['Cuenta.sucursal_id'] 	= array("label"=>"Banco",
											"lov"=>array("controller"		=>	"sucursales",
														"seleccionMultiple"	=> 	0,
														"camposRetorno"		=>	array(	"Banco.nombre",
																						"Sucursal.direccion")));


$campos['Cuenta.tipo'] = array();
$campos['Cuenta.cbu'] = array();
$campos['Cuenta.convenio'] = array();
$fieldsets[] = array('campos' => $campos);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "cuenta del empleador", 'imagen' => 'cuentas.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>