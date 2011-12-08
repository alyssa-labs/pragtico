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
$condiciones['Condicion.Preferencia-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'preferencias.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	foreach($v['PreferenciasValor'] as $v1) {
		if($v1['predeterminado'] == 'Si') {
			$valorPreedterminado = $v1['valor'];
		}
	}
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Preferencia']['id'], 'imagen' => array('nombre' => 'preferencias.gif', 'alt' => 'Valores'), 'url' => 'valores');
	$fila[] = array('model' => 'Preferencia', 'field' => 'id', 'valor'=>$v['Preferencia']['id'], 'write' => $v['Preferencia']['write'], 'delete' => $v['Preferencia']['delete']);
	$fila[] = array('model' => 'Preferencia', 'field' => 'nombre', 'valor' => $v['Preferencia']['nombre']);
	$fila[] = array('tipo'=>'celda', 'valor'=>$valorPreedterminado, 'nombreEncabezado'=>'Predeterminado', 'orden'=>false);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>