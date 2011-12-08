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
 * @version			$Revision: 238 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-28 13:06:36 -0200 (mié 28 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Cuenta'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'Cuenta', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Banco', 'field' => 'nombre', 'nombreEncabezado' => 'Banco', 'valor' => $v['Sucursal']['Banco']['nombre']);
	$fila[] = array('model' => 'Sucursal', 'field' => 'direccion', 'nombreEncabezado' => 'Sucursal', 'valor' => $v['Sucursal']['direccion']);
	$fila[] = array('model' => 'Cuenta', 'field' => 'tipo', 'valor' => $v['tipo']);
	$fila[] = array('model' => 'Cuenta', 'field' => 'cbu', 'valor' => $v['cbu']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'Cuentas', 'action' => 'add', 'Cuenta.empleador_id'=>$this->data['Empleador']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Cuentas', 'cuerpo' => $cuerpo));

?>