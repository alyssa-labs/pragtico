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
$condiciones['Condicion.Menu-nombre'] = array();
$condiciones['Condicion.Menu-etiqueta'] = array();
$condiciones['Condicion.Menu-parent_id'] = array('options' => 'listable', 'displayField'=>array('Menu.etiqueta'), 'conditions'=>array('Menu.parent_id is null'), 'order'=>array('Menu.nombre'), 'empty'=>true, 'label'=>'Padre');
$condiciones['Condicion.Menu-orden__desde'] = array('label'=>'Orden Desde');
$condiciones['Condicion.Menu-orden__hasta'] = array('label'=>'Orden Hasta');
$condiciones['Condicion.Menu-estado'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'menus.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$id = $v['Menu']['id'];
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'roles.gif', 'alt' => 'Roles'), 'url' => 'roles');
	$fila[] = array('model' => 'Menu', 'field' => 'id', 'valor'=>$id, 'write' => $v['Menu']['write'], 'delete' => $v['Menu']['delete']);
	$fila[] = array('model' => 'Menu', 'field' => 'etiqueta', 'valor' => $v['Menu']['etiqueta']);
	$fila[] = array('model' => 'Menu', 'field' => 'imagen', 'valor' => $v['Menu']['imagen']);
	$fila[] = array('model' => 'Menu', 'field' => 'orden', 'valor' => $v['Menu']['orden']);
	$fila[] = array('model' => 'Menu', 'field' => 'controller', 'valor' => $v['Menu']['controller']);
	$fila[] = array('model' => 'Menu', 'field' => 'action', 'valor' => $v['Menu']['action']);
	$fila[] = array('model' => 'Menu', 'field' => 'estado', 'valor' => $v['Menu']['estado']);
	$fila[] = array('model' => 'Parentmenu', 'field' => 'etiqueta', 'valor' => $v['Parentmenu']['etiqueta'], 'nombreEncabezado'=>'Padre', 'class'=>'izquierda');
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>