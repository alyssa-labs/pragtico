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
 * @version			$Revision: 248 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-03 13:42:43 -0200 (mar 03 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Cuenta-empleador_id'] 	= array(	'lov'=>array('controller'	=>	'empleadores',
																		'camposRetorno'	=>array('Empleador.cuit',
																								'Empleador.nombre')));
$condiciones['Condicion.Cuenta-sucursal_id'] 	= array('label' => 'Banco',
															'lov'=>array('controller'		=>	'sucursales',
																		'camposRetorno'		=>	array(	'Banco.nombre',
																										'Sucursal.direccion')));
$condiciones['Condicion.Cuenta-tipo'] = array();
$condiciones['Condicion.Cuenta-cbu'] = array();

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Cuentas de los Empleadores', 'imagen' => 'cuentas.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Cuenta', 'field' => 'id', 'valor' => $v['Cuenta']['id'], 'write' => $v['Cuenta']['write'], 'delete' => $v['Cuenta']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'nombreEncabezado' => 'Empleador', 'valor' => $v['Empleador']['nombre']);
	$fila[] = array('model' => 'Banco', 'field' => 'nombre', 'nombreEncabezado' => 'Banco', 'valor' => $v['Sucursal']['Banco']['nombre']);
	$fila[] = array('model' => 'Sucursal', 'field' => 'direccion', 'nombreEncabezado' => 'Sucursal', 'valor' => $v['Sucursal']['direccion']);
	$fila[] = array('model' => 'Cuenta', 'field' => 'tipo', 'valor' => $v['Cuenta']['tipo']);
	$fila[] = array('model' => 'Cuenta', 'field' => 'cbu', 'valor' => $v['Cuenta']['cbu']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>