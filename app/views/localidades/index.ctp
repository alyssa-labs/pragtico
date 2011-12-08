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
$condiciones['Condicion.Localidad-provincia_id'] = array('options' => 'listable', "model"=>"Provincia", "displayField"=>array("Provincia.nombre"), "empty"=>true);
$condiciones['Condicion.Localidad-codigo'] = array();
$condiciones['Condicion.Localidad-nombre'] = array("label"=>"Localidad");
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'localidades.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Localidad', 'field' => 'id', 'valor' => $v['Localidad']['id'], 'write' => $v['Localidad']['write'], 'delete' => $v['Localidad']['delete']);
	$fila[] = array('model' => 'Provincia', 'field' => 'nombre', 'valor' => $v['Provincia']['nombre'], "nombreEncabezado"=>"Provincia");
	$fila[] = array('model' => 'Localidad', 'field' => 'codigo', 'valor' => $v['Localidad']['codigo']);
	$fila[] = array('model' => 'Localidad', 'field' => 'nombre', 'valor' => $v['Localidad']['nombre'], "nombreEncabezado"=>"Localidad");
	$fila[] = array('model' => 'Localidad', 'field' => 'codigo_zona', 'valor' => $v['Localidad']['codigo_zona']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>