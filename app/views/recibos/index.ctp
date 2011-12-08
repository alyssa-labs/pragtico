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
 * @version			$Revision: 1423 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Recibo-empleador_id'] = array(
	'lov'		=> array(
		'controller'	=> 'empleadores',
		'camposRetorno'	=> array('Empleador.cuit', 'Empleador.nombre')
	)
);
$condiciones['Condicion.Recibo-convenio_id'] = array(
	'lov'		=> array(
		'controller'	=> 'convenios',
		'camposRetorno'	=> array('Convenio.nombre')
	)
);

$condiciones['Condicion.Recibo-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'recibos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Recibo']['id'], 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Conceptos (Detalle del Recibo)'), 'url' => 'conceptos');

	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('asignar.gif', array('alt' => 'Sincronizar este recibo en todas las relaciones que lo tengan asigando', 'title' => 'Sincronizar este recibo en todas las relaciones que lo tengan asigando')), array('action' => 'sync', $v['Recibo']['id']), array(), 'Sincronizara los conceptos del recibo ' . $v['Recibo']['nombre'] . ' en todas las relaciones que lo tengan asignado. Desea continuar?'));

	$fila[] = array('model' => 'Recibo', 'field' => 'id', 'valor' => $v['Recibo']['id'], 'write' => $v['Recibo']['write'], 'delete' => $v['Recibo']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Convenio', 'field' => 'nombre', 'valor' => $v['Convenio']['nombre'], 'nombreEncabezado' => 'Convenio');
	$fila[] = array('model' => 'Recibo', 'field' => 'nombre', 'valor' => $v['Recibo']['nombre']);
	$fila[] = array('model' => 'Recibo', 'field' => 'descripcion', 'valor' => $v['Recibo']['descripcion']);
	$cuerpo[] = $fila;
}

$acciones[] = $appForm->link('Nuevo (Emp)', 'add/empleador',
			array(	'class' 	=> 'link_boton',
	 				'title' 	=> 'Nuevo Recibo para el Empleador'));
$acciones[] = $appForm->link('Nuevo (Conv)', 'add/convenio',
			array(	'class' 	=> 'link_boton',
	 				'title' 	=> 'Nuevo Recibo para el Convenio'));
$acciones[] = 'modificar';
$acciones[] = 'eliminar';
$accionesExtra['opciones'] = array('acciones' => $acciones);

echo $this->element('index/index', array(
	'condiciones' 	=> $fieldset,
	'cuerpo' 		=> $cuerpo,
	'accionesExtra' => $accionesExtra)
);

?>