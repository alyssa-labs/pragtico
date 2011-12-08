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
$condiciones['Condicion.Coeficiente-nombre'] = array();
$condiciones['Condicion.Coeficiente-tipo'] = array();
$condiciones['Condicion.Coeficiente-descripcion'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'coeficientes.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Coeficiente', 'field' => 'id', 'valor' => $v['Coeficiente']['id'], 'write' => $v['Coeficiente']['write'], 'delete' => $v['Coeficiente']['delete']);
	$fila[] = array('model' => 'Coeficiente', 'field' => 'nombre', 'valor' => $v['Coeficiente']['nombre']);
	$fila[] = array('model' => 'Coeficiente', 'field' => 'tipo', 'valor' => $v['Coeficiente']['tipo']);
	$fila[] = array('model' => 'Coeficiente', 'field' => 'valor', 'valor' => $v['Coeficiente']['valor']);
	$fila[] = array('model' => 'Coeficiente', 'field' => 'descripcion', 'valor' => $v['Coeficiente']['descripcion']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>