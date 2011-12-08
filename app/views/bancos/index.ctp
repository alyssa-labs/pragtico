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
 * @version			$Revision: 332 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-25 16:33:58 -0200 (mié 25 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Banco-codigo'] = array();
$condiciones['Condicion.Banco-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'bancos.gif')));

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Banco']['id'], 'imagen' => array('nombre' => 'sucursales.gif', 'alt' => 'Sucursales'), 'url' => 'sucursales');
	$fila[] = array('model' => 'Banco', 'field' => 'id', 'valor' => $v['Banco']['id'], 'write' => $v['Banco']['write'], 'delete' => $v['Banco']['delete']);
	$fila[] = array('model' => 'Banco', 'field' => 'codigo', 'valor' => $v['Banco']['codigo']);
	$fila[] = array('model' => 'Banco', 'field' => 'nombre', 'valor' => $v['Banco']['nombre']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));
?>