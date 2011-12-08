<?php
/**
 * Pressentation layer.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.views
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 285 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-18 16:46:33 -0200 (mié 18 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
 * Search Conditions.
 */
$condiciones['Condicion.Zone-code'] = array();
$condiciones['Condicion.Zone-name'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => __('Zone', true), 'imagen' => 'zones.gif')));


/**
 * Table body.
 */
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Zone', 'field' => 'id', 'valor' => $v['Zone']['id'], 'write' => $v['Zone']['write'], 'delete' => $v['Zone']['delete']);
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Zone']['id'], 'imagen' => array('nombre' => 'Zone_detalle.gif', 'alt' => 'Detalles'), 'url' => 'detalles');
	$fila[] = array('model' => 'Zone', 'field' => 'code', 'valor' => $v['Zone']['code']);
	$fila[] = array('model' => 'Zone', 'field' => 'name', 'valor' => $v['Zone']['name']);
    $fila[] = array('model' => 'Zone', 'field' => 'percentage', 'valor' => $v['Zone']['percentage']);
	$cuerpo[] = $fila;
}

/**
 * Output view.
 */
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>