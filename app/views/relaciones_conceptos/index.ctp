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
 * @version			$Revision: 405 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-16 14:05:37 -0300 (lun 16 de mar de 2009) $
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

$condiciones['Condicion.RelacionesConcepto-relacion_id'] = array(	'lov'=>array('controller'	=>	'relaciones',
																		'camposRetorno'	=>array('Empleador.nombre',
																								'Trabajador.apellido')));

$condiciones['Condicion.RelacionesConcepto-concepto_id'] = array(	'lov'=>array('controller'	=>	'conceptos',
																		'camposRetorno'	=>array('Concepto.codigo',
																								'Concepto.nombre')));
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'concepto de la relacion laboral', 'imagen' => 'conceptos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'id', 'valor' => $v['RelacionesConcepto']['id'], 'write' => $v['RelacionesConcepto']['write'], 'delete' => $v['RelacionesConcepto']['delete']);
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'jerarquia', 'valor' => $v['RelacionesConcepto']['jerarquia']);
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'formula_aplicara', 'valor' => $v['RelacionesConcepto']['formula_aplicara'], 'nombreEncabezado' => 'Formula que se Aplicara');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'class' => 'derecha', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');
	$fila[] = array('model' => 'Concepto', 'field' => 'codigo', 'valor' => $v['Concepto']['codigo'], 'nombreEncabezado' => 'Concepto');
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));


?>