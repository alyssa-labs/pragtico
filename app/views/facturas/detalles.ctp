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
 * @version			$Revision: 519 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-05-14 23:34:38 -0300 (jue 14 de may de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['FacturasDetalle'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'FacturasDetalle', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'FacturasDetalle', 'field' => 'nombre', 'valor' => $v['coeficiente_nombre'], 'nombreEncabezado' => 'Coeficiente');
	$fila[] = array('model' => 'FacturasDetalle', 'field' => 'subtotal', 'valor' => $v['subtotal'], 'tipoDato' => 'moneda');
	$fila[] = array('model' => 'FacturasDetalle', 'field' => 'coeficiente_valor', 'valor' => $formato->format($v['coeficiente_valor'], array('tipo' => 'number')), 'nombreEncabezado' => 'Valor');
	$fila[] = array('model' => 'FacturasDetalle', 'field' => 'total', 'valor' => $v['total'], 'tipoDato' => 'moneda');
	$cuerpo[] = $fila;
}

echo $this->element('desgloses/agregar', array(
		'opcionesTabla' => array('tabla' => array('eliminar' => false, 'modificar' => false)),
		'titulo' 		=> 'Detalle',
  		'cuerpo' 		=> $cuerpo));

?>