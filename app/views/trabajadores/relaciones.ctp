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
 * @version			$Revision: 1098 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-10-20 14:35:25 -0300 (mar 20 de oct de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Empleador'] as $k=>$v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], 'imagen' => array('nombre' => 'ausencias.gif', 'alt' => 'Ausencias'), 'url'=> array('controller' => 'relaciones', 'action' => 'ausencias'));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], 'imagen' => array('nombre' => 'conceptos.gif', 'alt' => 'Conceptos'), 'url'=> array('controller' => 'relaciones', 'action' => 'conceptos'));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], 'imagen' => array('nombre' => 'ropas.gif', 'alt' => 'Ropa Entregada'), 'url'=> array('controller' => 'relaciones', 'action' => 'ropas'));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], 'imagen' => array('nombre' => 'horas.gif', 'alt' => 'Horas'), 'url'=> array('controller' => 'relaciones', 'action' => 'horas'));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Relacion']['id'], 'imagen' => array('nombre' => 'descuentos.gif', 'alt' => 'Descuentos'), 'url'=> array('controller' => 'relaciones', 'action' => 'descuentos'));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'imagen' => array('nombre' => 'recibos.gif', 'alt' => 'Recibos'), 'url'=> array('controller' => 'empleadores', 'action' => 'recibos'));
	$fila[] = array('model' => 'Relacion', 'field' => 'id', 'valor' => $v['Relacion']['id'], 'write' => $v['Relacion']['write'], 'delete' => $v['Relacion']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['cuit'], 'class' => 'centro');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Relacion', 'field' => 'ingreso', 'valor' => $v['Relacion']['ingreso']);
	$fila[] = array('model' => 'Relacion', 'field' => 'horas', 'valor' => $v['Relacion']['horas']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'relaciones', 'action' => 'add', 'Relacion.trabajador_id'=>$this->data['Trabajador']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Empleadores', 'cuerpo' => $cuerpo));

?>