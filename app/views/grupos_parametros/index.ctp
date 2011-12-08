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
$condiciones['Condicion.GruposParametro-nombre'] = array();
$condiciones['Condicion.GruposParametro-valor'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Parametros del Grupo", 'imagen' => 'parametros.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'GruposParametro', 'field' => 'id', 'valor' => $v['GruposParametro']['id'], 'write' => $v['GruposParametro']['write'], 'delete' => $v['GruposParametro']['delete']);
	$fila[] = array('model' => 'Grupo', 'field' => 'nombre', 'valor' => $v['Grupo']['nombre'], "nombreEncabezado"=>"Grupo");
	$fila[] = array('model' => 'GruposParametro', 'field' => 'nombre', 'valor' => $v['GruposParametro']['nombre']);
	$fila[] = array('model' => 'GruposParametro', 'field' => 'valor', 'valor' => $v['GruposParametro']['valor']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>