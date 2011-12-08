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
 * @version			$Revision: 236 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar 27 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Trabajador'] as $k=>$v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], "update"=>"desglose_1", 'imagen' => array('nombre' => 'ausencias.gif', 'alt' => "Ausencias"), 'url' => '../relaciones/ausencias');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], "update"=>"desglose_2", 'imagen' => array('nombre' => 'conceptos.gif', 'alt' => "Conceptos"), 'url' => '../relaciones/conceptos');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], "update"=>"desglose_3", 'imagen' => array('nombre' => 'ropas.gif', 'alt' => "Ropa Entregada"), 'url' => '../relaciones/ropaEntregada');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], "update"=>"desglose_4", 'imagen' => array('nombre' => 'horas.gif', 'alt' => "Horas"), 'url' => '../relaciones/horas');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], "update"=>"desglose_5", 'imagen' => array('nombre' => 'descuentos.gif', 'alt' => "Descuentos"), 'url' => '../relaciones/descuentos');
	$fila[] = array('model' => 'Relacion', 'field' => 'id', 'valor' => $v['Relacion']['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Trabajador', 'field' => 'cuil', 'valor' => $v['cuil'], "class"=>"centro");
	$fila[] = array('model' => 'Trabajador', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['apellido']);
	$fila[] = array('model' => 'Relacion', 'field' => 'ingreso', 'valor' => $v['Relacion']['ingreso']);
	$fila[] = array('model' => 'Relacion', 'field' => 'horas', 'valor' => $v['Relacion']['horas']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "relaciones", 'action' => 'add', "Relacion.empleador_id"=>$this->data['Empleador']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Trabajadores", 'cuerpo' => $cuerpo));

?>