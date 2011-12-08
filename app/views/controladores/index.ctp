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
 * @version			$Revision: 651 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-07-03 10:31:23 -0300 (vie 03 de jul de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Controlador-nombre'] = array();
$condiciones['Condicion.Controlador-etiqueta'] = array();
$condiciones['Condicion.Controlador-estado'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'controladores.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Controlador']['id'], 'imagen' => array('nombre' => 'acciones.gif', 'alt' => 'Acciones'), 'url' => 'acciones');
	$fila[] = array('model' => 'Controlador', 'field' => 'id', 'valor' => $v['Controlador']['id'], 'write' => $v['Controlador']['write'], 'delete' => $v['Controlador']['delete']);
	$fila[] = array('model' => 'Controlador', 'field' => 'nombre', 'valor' => $v['Controlador']['nombre']);
	$fila[] = array('model' => 'Controlador', 'field' => 'etiqueta', 'valor' => $v['Controlador']['etiqueta']);
	$fila[] = array('model' => 'Controlador', 'field' => 'ayuda', 'valor' => $v['Controlador']['ayuda']);
	$fila[] = array('model' => 'Controlador', 'field' => 'estado', 'valor' => $v['Controlador']['estado']);
	$cuerpo[] = $fila;
}

$accionesExtra[] = $appForm->link('Act. Masiva', array('controller' => 'controladores', 'action' => 'actualizar_controladores'), array('class' => 'link_boton', 'title' => 'Actualiza automaticamente todos controladores y sus acciones'));

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'accionesExtra' => $accionesExtra));

?>