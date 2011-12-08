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
$condiciones['Condicion.Grupo-nombre'] = array();
$condiciones['Condicion.Grupo-estado'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'buscar.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['GruposUsuario']['id'], 'imagen' => array('nombre' => 'usuarios.gif', 'alt' => 'Usuarios'), 'url' => 'usuarios');
	$fila[] = array('model' => 'GruposUsuario', 'field' => 'id', 'valor'=>$v['GruposUsuario']['id'], 'write' => $v['GruposUsuario']['write'], 'delete' => $v['GruposUsuario']['delete']);
	$fila[] = array('model' => 'Grupo', 'field' => 'nombre', 'valor' => $v['Grupo']['nombre']);
	$fila[] = array('model' => 'Grupo', 'field' => 'estado', 'valor' => $v['Grupo']['estado']);
	$fila[] = array('model' => 'Grupo', 'field' => 'observacion', 'valor' => $v['Grupo']['observacion']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>