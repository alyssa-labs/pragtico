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
 * @version			$Revision: 1455 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2011-07-02 22:07:06 -0300 (sáb 02 de jul de 2011) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Convenio-numero'] = array();
$condiciones['Condicion.Convenio-nombre'] = array();
$fieldset = $appForm->pintarFieldsets(array(array('campos' => $condiciones)), array(
	'fieldset' => array(
		'legend' => 'Convenio Colectivo',
		'imagen' => 'convenios.gif')
	)
);


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$id = $v['Convenio']['id'];
/*
	$fila[] = array(
		'tipo' 		=> 'accion',
		'valor' 	=> $appForm->link($appForm->image('sync.gif', array(
				'alt' 	=> 'Asignar este concepto a todos los Trabajadores',
				'title' => 'Asignar este concepto a todos los Trabajadores')),
				array('action' 		=> 'manipular_concepto/agregar',
					'convenio_id' 	=> $this->data['Convenio']['id'], 'concepto_id'=>$v['id']), array(),
				'Asignara este concepto a todos los trabajadores de todos los empleadores que tengan el convenio colectivo ' . $this->data['Convenio']['nombre'] . '. Desea continuar?'));
*/
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'recibos.gif', 'alt' => "Recibos"), 'url' => 'recibos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'categorias.gif', 'alt' => 'Categorias'), 'url' => 'categorias');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'conceptos.gif', 'alt' => 'Conceptos'), 'url' => 'conceptos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'informaciones.gif', 'alt' => 'Informaciones'), 'url' => 'informaciones');
	$fila[] = array('tipo'=>'accion', 'valor'=>$appForm->link($appForm->image('archivo.gif', array('alt' => 'Descargar')), 'descargar/' . $id));
	$fila[] = array('model' => 'Convenio', 'field' => 'id', 'valor' => $v['Convenio']['id'], 'write' => $v['Convenio']['write'], 'delete' => $v['Convenio']['delete']);
	$fila[] = array('model' => 'Convenio', 'field' => 'numero', 'valor' => $v['Convenio']['numero']);
	$fila[] = array('model' => 'Convenio', 'field' => 'nombre', 'valor' => $v['Convenio']['nombre']);
	$fila[] = array('model' => 'Convenio', 'field' => 'actualizacion', 'valor' => $v['Convenio']['actualizacion'], 'nombreEncabezado'=>'Ult. Actualizacion');
	$cuerpo[] = $fila;
}


$generar = $appForm->link('Generar Planilla', 'generar_planilla', array('title' => 'Genera las planillas para de actualizacion de Categorias', 'class' => 'link_boton'));
$importar = $appForm->link('Importar Planilla', 'importar_planilla', array('class' => 'link_boton', 'title' => 'Importa las planillas de actualizacion de Categorias'));
$accionesExtra['opciones'] = array('acciones' => array('nuevo', 'modificar', 'eliminar', $generar, $importar));
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'accionesExtra' => $accionesExtra));

?>