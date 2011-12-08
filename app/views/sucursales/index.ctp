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
 * @lastmodified	$Date: 2009-02-19 12:21:14 -0200 (jue 19 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Sucursal-banco_id'] = array('options' => 'listable', "empty"=>true, "displayField"=>array("Banco.nombre"), "model"=>"Banco");
$condiciones['Condicion.Sucursal-codigo'] = array();
$condiciones['Condicion.Sucursal-provincia_id'] = array('options' => 'listable', 'model' => 'Provincia', 'displayField' => array('Provincia.nombre'), 'empty' => true);

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Sucursal", 'imagen' => 'sucursales.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Sucursal', 'field' => 'id', 'valor' => $v['Sucursal']['id'], 'write' => $v['Sucursal']['write'], 'delete' => $v['Sucursal']['delete']);
	$fila[] = array('model' => 'Banco', 'field' => 'nombre', 'valor' => $v['Banco']['nombre'], "nombreEncabezado"=>"Banco");
	$fila[] = array('model' => 'Sucursal', 'field' => 'codigo', 'valor' => $v['Sucursal']['codigo']);
	$fila[] = array('model' => 'Sucursal', 'field' => 'direccion', 'valor' => $v['Sucursal']['direccion']);
	$fila[] = array('model' => 'Provincia', 'field' => 'nombre', 'valor' => $v['Provincia']['nombre'], 'nombreEncabezado' => 'Provincia');
	$fila[] = array('model' => 'Sucursal', 'field' => 'telefono', 'valor' => $v['Sucursal']['telefono']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>