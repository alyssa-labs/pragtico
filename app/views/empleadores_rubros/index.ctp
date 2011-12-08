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
$condiciones['Condicion.EmpleadoresRubro-empleador_id'] = array(	"lov"=>array("controller"	=>	"empleadores",
																		"camposRetorno"	=>array("Empleador.cuit",
																								"Empleador.nombre")));
$condiciones['Condicion.EmpleadoresRubro-rubro_id'] = array('options' => 'listable', "model"=>"Rubro", "displayField"=>array("Rubro.nombre"), "empty"=>true, "label"=>"Rubro");
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Rubros de los Empleadores", 'imagen' => 'rubros.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'EmpleadoresRubro', 'field' => 'id', 'valor' => $v['EmpleadoresRubro']['id'], 'write' => $v['EmpleadoresRubro']['write'], 'delete' => $v['EmpleadoresRubro']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre", "nombreEncabezado"=>"Empleador', 'valor' => $v['Empleador']['cuit'] . " - " . $v['Empleador']['nombre']);
	$fila[] = array('model' => 'Rubro', 'field' => 'nombre", "nombreEncabezado"=>"Rubro', 'valor' => $v['Rubro']['nombre']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>