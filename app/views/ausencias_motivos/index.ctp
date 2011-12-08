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
 * @version			$Revision: 644 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-06-26 16:25:46 -0300 (vie 26 de jun de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.AusenciasMotivo-motivo'] = array();
$condiciones['Condicion.AusenciasMotivo-tipo'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Motivos de las Ausencias", 'imagen' => 'ausencias_motivos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'AusenciasMotivo', 'field' => 'id', 'valor' => $v['AusenciasMotivo']['id'], 'write' => $v['AusenciasMotivo']['write'], 'delete' => $v['AusenciasMotivo']['delete']);
	$fila[] = array('model' => 'AusenciasMotivo', 'field' => 'motivo', 'valor' => $v['AusenciasMotivo']['motivo']);
	$fila[] = array('model' => 'Situacion', 'field' => 'nombre', 'valor' => $v['Situacion']['nombre'], "nombreEncabezado" => "Situacion");
	$fila[] = array('model' => 'AusenciasMotivo', 'field' => 'tipo', 'valor' => $v['AusenciasMotivo']['tipo']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>