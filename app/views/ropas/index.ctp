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
$condiciones['Condicion.Relacion-trabajador_id'] = array(	'lov'=>array('controller'		=>	'trabajadores',
																		'separadorRetorno'	=>	' ',
																		'camposRetorno'		=>array('Trabajador.apellido',
																									'Trabajador.nombre')));

$condiciones['Condicion.Relacion-empleador_id'] = array(	'lov'=>array('controller'	=> 'empleadores',
																		'camposRetorno'	=> array('Empleador.nombre')));

$condiciones['Condicion.Ropa-relacion_id'] = array(	'lov'=>array('controller'	=>	'relaciones',
																		'camposRetorno'	=>array('Empleador.nombre',
																								'Trabajador.apellido')));


$condiciones['Condicion.Ropa-fecha'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'ropa entregada a la relacion laboral', 'imagen' => 'ropas.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	//$accionImprimir = $appForm->link($appForm->image('print.gif', array('class' => 'accion', 'alt' => 'Imprimir Orden de Ropa', 'title' => 'Imprimir Orden de Ropa')), 'imprimirOrden/' . $id, array('target' => '_blank'));
	//$fila[] = array('tipo' => 'accion', 'valor'=>$accionImprimir);
	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Documento')), '../documentos/generar/model:Ropa/contain:' . str_replace('\'', '**', serialize(array('Relacion.Trabajador', 'Relacion.Empleador', 'RopasDetalle'))) . '/id:' . $v['Ropa']['id']));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Ropa']['id'], 'imagen' => array('nombre' => 'prendas.gif', 'alt' => 'Prendas Entregadas'), 'url' => 'prendas');
	$fila[] = array('model' => 'Ropa', 'field' => 'id', 'valor' => $v['Ropa']['id'], 'write' => $v['Ropa']['write'], 'delete' => $v['Ropa']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'class' => 'derecha', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');
	$fila[] = array('model' => 'Ropa', 'field' => 'fecha', 'valor' => $v['Ropa']['fecha']);
	$fila[] = array('model' => 'Ropa', 'field' => 'observacion', 'valor' => $v['Ropa']['observacion']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));


?>