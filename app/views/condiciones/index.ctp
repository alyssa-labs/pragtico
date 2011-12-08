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
$condiciones['Condicion.Condicion-codigo'] = array();
$condiciones['Condicion.Condicion-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'condiciones.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Condicion', 'field' => 'id', 'valor' => $v['Condicion']['id'], 'write' => $v['Condicion']['write'], 'delete' => $v['Condicion']['delete']);
	$fila[] = array('model' => 'Condicion', 'field' => 'codigo', 'valor' => $v['Condicion']['codigo']);
	$fila[] = array('model' => 'Condicion', 'field' => 'nombre', 'valor' => $v['Condicion']['nombre']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));
?>