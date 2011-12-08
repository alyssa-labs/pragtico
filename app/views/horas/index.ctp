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
 * @version			$Revision: 895 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-08-30 22:38:14 -0300 (dom 30 de ago de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Hora-tipo'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Relacion-trabajador_id'] = array(	
		'lov' => array('controller'	=>	'trabajadores',
				'separadorRetorno'	=>	' ',
				'camposRetorno'		=> array('Trabajador.apellido', 'Trabajador.nombre')));

$condiciones['Condicion.Relacion-empleador_id'] = array(	
		'lov' => array('controller'		=> 'empleadores',	
					   'camposRetorno'	=> array('Empleador.nombre')));

$condiciones['Condicion.Relacion-id'] = array(	
		'label'	=> 'Relacion',
		'lov'	=> array(	'controller'	=> 'relaciones',
							'camposRetorno'	=> array('Empleador.nombre', 'Trabajador.apellido')));

$condiciones['Condicion.Hora-estado'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Hora-periodo'] = array('type' => 'periodo');


$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'horas de la relacion laboral', 'imagen' => 'horas.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Hora', 'field' => 'id', 'valor' => $v['Hora']['id'], 'write'=>$v['Hora']['write'], 'delete'=>$v['Hora']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'class' => 'derecha', 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');
	$fila[] = array('model' => 'Hora', 'field' => 'periodo', 'valor' => $v['Hora']['periodo']);
	$fila[] = array('model' => 'Hora', 'field' => 'cantidad', 'valor' => $v['Hora']['cantidad']);
	$fila[] = array('model' => 'Hora', 'field' => 'tipo', 'valor' => $v['Hora']['tipo']);
	$fila[] = array('model' => 'Hora', 'field' => 'estado', 'valor' => $v['Hora']['estado']);
	if($v['Hora']['estado'] === 'Liquidada') {
		$cuerpo[] = array('contenido'=>$fila, 'opciones'=>array('seleccionMultiple'=>false, 'eliminar'=>false, 'modificar'=>false));
	}
	else {
		$cuerpo[] = $fila;
	}
}
$fila = null;
$fila[] = array('model' => 'Hora', 'field' => 'id', 'valor' => '');
$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => '');
$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => '');
$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => '');
$fila[] = array('model' => 'Hora', 'field' => 'periodo', 'valor' => '');
$fila[] = array('model' => 'Hora', 'field' => 'cantidad', 'valor'=>$cantidad);
$fila[] = array('model' => 'Hora', 'field' => 'tipo', 'valor' => '');
$fila[] = array('model' => 'Hora', 'field' => 'estado', 'valor' => '');

$pie[] = $fila;
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'pie'=>$pie));
?>