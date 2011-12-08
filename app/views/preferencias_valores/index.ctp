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
$condiciones['Condicion.Preferencia-nombre'] = array("label"=>"Preferencia");
$condiciones['Condicion.PreferenciasValor-valor'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "valores de la preferencia", 'imagen' => 'preferencias.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'PreferenciasValor', 'field' => 'id', 'valor' => $v['PreferenciasValor']['id'], 'write' => $v['PreferenciasValor']['write'], 'delete' => $v['PreferenciasValor']['delete']);
	$fila[] = array('model' => 'Preferencia', 'field' => 'nombre', 'valor' => $v['Preferencia']['nombre']);
	$fila[] = array('model' => 'PreferenciasValor', 'field' => 'valor', 'valor' => $v['PreferenciasValor']['valor']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>