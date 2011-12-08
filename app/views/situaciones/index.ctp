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
$condiciones['Condicion.Situacion-codigo'] = array();
$condiciones['Condicion.Situacion-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'situaciones.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Situacion']['id'], 'imagen' => array('nombre' => 'ausencias_motivos.gif', 'alt' => "Motivos de Ausencia"), 'url' => 'ausencias_motivos');
	$fila[] = array('model' => 'Situacion', 'field' => 'id', 'valor' => $v['Situacion']['id'], 'write' => $v['Situacion']['write'], 'delete' => $v['Situacion']['delete']);
	$fila[] = array('model' => 'Situacion', 'field' => 'codigo', 'valor' => $v['Situacion']['codigo']);
	$fila[] = array('model' => 'Situacion', 'field' => 'nombre', 'valor' => $v['Situacion']['nombre']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>