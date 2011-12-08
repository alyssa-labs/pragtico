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
 * @version			$Revision: 421 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-19 17:49:44 -0300 (jue 19 de mar de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Usuario-nombre'] = array();
$condiciones['Condicion.Usuario-estado'] = array();
$condiciones['Condicion.Usuario-nombre_completo'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'usuarios.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {

	$fila = null;
	$id = $v['Usuario']['id'];
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'grupos.gif', 'alt' => 'Grupos'), 'url' => 'grupos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'roles.gif', 'alt' => 'Roles'), 'url' => 'roles');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'cambiar_clave.gif', 'alt' => 'Cambiar Clave'), 'url'=>'cambiar_clave');
	$fila[] = array('model' => 'Usuario', 'field' => 'id', 'valor'=>$id, 'write' => $v['Usuario']['write'], 'delete' => $v['Usuario']['delete']);
	$fila[] = array('model' => 'Usuario', 'field' => 'nombre', 'valor' => $v['Usuario']['nombre']);
	$fila[] = array('model' => 'Usuario', 'field' => 'nombre_completo', 'valor' => $v['Usuario']['nombre_completo']);
	$fila[] = array('model' => 'Usuario', 'field' => 'estado', 'valor' => $v['Usuario']['estado']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>