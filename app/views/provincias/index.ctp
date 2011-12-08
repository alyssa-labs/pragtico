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
 * @version			$Revision: 952 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-13 19:49:18 -0300 (dom 13 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Provincia-codigo'] = array();
$condiciones['Condicion.Provincia-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'provincias.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Provincia']['id'], 'imagen' => array('nombre' => 'localidades.gif', 'alt' => "Localidades"), 'url' => 'localidades');
	$fila[] = array('model' => 'Provincia', 'field' => 'id', 'valor' => $v['Provincia']['id'], 'write' => $v['Provincia']['write'], 'delete' => $v['Provincia']['delete']);
    $fila[] = array('model' => 'Provincia', 'field' => 'codigo', 'valor' => $v['Provincia']['codigo']);
	$fila[] = array('model' => 'Provincia', 'field' => 'nombre', 'valor' => $v['Provincia']['nombre']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>