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
 * @version			$Revision: 538 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-05-26 15:15:54 -0300 (mar 26 de may de 2009) $
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

$condiciones['Condicion.Descuento-relacion_id'] = array(	'lov'=>array('controller'	=>	'relaciones',
																		'camposRetorno'	=>array('Empleador.nombre',
																								'Trabajador.apellido')));

$condiciones['Condicion.Descuento-desde'] = array();
$condiciones['Condicion.Descuento-descontar'] = array('type'=>'select', 'empty'=>true);
$condiciones['Condicion.Descuento-estado'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Descuento-tipo'] = array('type' => 'select', 'multiple' => 'checkbox');

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'descuentos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Descuento']['id'], 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Detalles'), 'url' => 'detalles');
	$fila[] = array('model' => 'Descuento', 'field' => 'id', 'valor' => $v['Descuento']['id'], 'write' => $v['Descuento']['write'], 'delete' => $v['Descuento']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado'=>'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'tipoDato'=>'integer', 'nombreEncabezado'=>'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado'=>'Trabajador');
	$fila[] = array('model' => 'Descuento', 'field' => 'desde', 'valor' => $v['Descuento']['desde']);
	$fila[] = array('model' => 'Descuento', 'field' => 'monto', 'valor' => $v['Descuento']['monto'], 'tipoDato'=>'moneda');
	$fila[] = array('model' => 'Descuento', 'field' => 'tipo', 'valor' => $v['Descuento']['tipo']);
	$fila[] = array('model' => 'Descuento', 'field' => 'estado', 'valor' => $v['Descuento']['estado']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>