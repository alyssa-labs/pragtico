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
 * @version			$Revision: 236 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar, 27 ene 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['AreasCoeficiente.id'] = array();
$campos['AreasCoeficiente.area_id'] = array('lov' => array(
	'controller'		=>	'areas',
	'seleccionMultiple'	=> 	0,
	'camposRetorno'		=>	array(	'Area.nombre', 'Empleador.cuit', 'Empleador.nombre')));
	
$campos['AreasCoeficiente.coeficiente_id'] = array('lov' => array(
	'controller'		=>	'coeficientes',
 	'seleccionMultiple'	=> 	0,
	'camposRetorno'		=>	array(	'Coeficiente.nombre', 'Coeficiente.valor')));
	
$campos['AreasCoeficiente.porcentaje'] = array('aclaracion' => 'Indica el porcentaje a sumar o restar al valor del coeficiente.');
$campos['AreasCoeficiente.observacion'] = array();
$fieldsets[] = array('campos' => $campos);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'coeficiente del area', 'imagen' => 'coeficientes.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>