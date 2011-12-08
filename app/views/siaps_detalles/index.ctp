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
 * @version			$Revision: 921 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-08 16:55:28 -0300 (mar 08 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.SiapsDetalle-siap_id'] = array('options' => 'listable', 'model' => 'Siap', 'displayField' => array('Siap.version'), 'empty' => true);
$condiciones['Condicion.SiapsDetalle-elemento'] = array();
$condiciones['Condicion.SiapsDetalle-descripcion'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Detalle de Siap', 'imagen' => 'siap_detalle.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'id', 'valor' => $v['SiapsDetalle']['id'], 'write' => $v['SiapsDetalle']['write'], 'delete' => $v['SiapsDetalle']['delete']);
	$fila[] = array('model' => 'Siap', 'field' => 'version', 'valor' => $v['Siap']['version']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'elemento', 'valor' => $v['SiapsDetalle']['elemento']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'descripcion', 'valor' => $v['SiapsDetalle']['descripcion']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'desde', 'valor' => $v['SiapsDetalle']['desde']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'longitud', 'valor' => $v['SiapsDetalle']['longitud']);
    $fila[] = array('model' => 'SiapsDetalle', 'field' => 'tipo', 'valor' => $v['SiapsDetalle']['tipo']);
	$fila[] = array('model' => 'SiapsDetalle', 'field' => 'valor', 'valor' => $v['SiapsDetalle']['valor']);
    $fila[] = array('model' => 'SiapsDetalle', 'field' => 'valor', 'valor' => $v['SiapsDetalle']['valor_maximo'], 'nombreEncabezado' => 'Maximo');
	$cuerpo[] = $fila;
}
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>