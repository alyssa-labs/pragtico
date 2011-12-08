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
$condiciones['Condicion.Accion-controlador_id'] = array('options' => 'listable', 'model'=>'Controlador', 'displayField'=>array('Controlador.nombre'), 'empty'=>true);
$condiciones['Condicion.Accion-nombre'] = array();
$condiciones['Condicion.Accion-etiqueta'] = array();
$condiciones['Condicion.Accion-estado'] = array();
$condiciones['Condicion.Accion-seguridad'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'acciones.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Accion', 'field' => 'id', 'valor' => $v['Accion']['id'], 'write' => $v['Accion']['write'], 'delete' => $v['Accion']['delete']);
	$fila[] = array('model' => 'Controlador', 'field' => 'nombre', 'valor' => $v['Controlador']['nombre'], 'nombreEncabezado'=>'Controlador');
	$fila[] = array('model' => 'Accion', 'field' => 'nombre', 'valor' => $v['Accion']['nombre']);
	$fila[] = array('model' => 'Accion', 'field' => 'etiqueta', 'valor' => $v['Accion']['etiqueta']);
	$fila[] = array('model' => 'Accion', 'field' => 'estado', 'valor' => $v['Accion']['estado']);
	$fila[] = array('model' => 'Accion', 'field' => 'seguridad', 'valor' => $v['Accion']['seguridad']);

	if($v['Accion']['seguridad'] == 'No') {
		$cuerpo[] = array('contenido'=>$fila, 'opciones' => array('title'=>'No se esta controlando la seguridad sobre esta accion.', 'class'=>'fila_resaltada'));
	}
	else {
		$cuerpo[] = $fila;
	}
}

$accionesExtra[] = $appForm->link('Act. Masiva', array('controller' => 'controladores', 'action' => 'actualizar_controladores'), array('class' => 'link_boton', 'title' => 'Actualiza automaticamente todos controladores y sus acciones'));

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'accionesExtra' => $accionesExtra));

?>