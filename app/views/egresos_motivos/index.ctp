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
 * @lastmodified	$Date: 2009-02-03 13:42:43 -0200 (Tue, 03 Feb 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.EgresosMotivo-recomendable'] = array();
$condiciones['Condicion.EgresosMotivo-motivo'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Motivos de Egreso", 'imagen' => 'egresos_motivos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'EgresosMotivo', 'field' => 'id', 'valor' => $v['EgresosMotivo']['id'], 'write' => $v['EgresosMotivo']['write'], 'delete' => $v['EgresosMotivo']['delete']);
	$fila[] = array('model' => 'EgresosMotivo', 'field' => 'recomendable', 'valor' => $v['EgresosMotivo']['recomendable']);
    $fila[] = array('model' => 'EgresosMotivo', 'field' => 'motivo', 'valor' => $v['EgresosMotivo']['motivo']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>