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
 * @version			$Revision: 268 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-16 16:50:32 -0200 (lun 16 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['DescuentosDetalle'] as $k=>$v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['liquidacion_id'], 'update'=>'desglose_1', 'imagen' => array('nombre' => 'liquidaciones.gif', 'alt' => 'Liquidacion'), 'url' => '../liquidaciones/recibo_html');
	$fila[] = array('model' => 'DescuentosDetalle', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'DescuentosDetalle', 'field' => 'fecha', 'valor' => $v['fecha']);
	$fila[] = array('model' => 'DescuentosDetalle', 'field' => 'monto', 'valor'=>'$ ' . $v['monto']);
 	$fila[] = array('model' => 'DescuentosDetalle', 'field' => 'observacion', 'valor' => $v['observacion']);
	$cuerpo[] = $fila;
}


/**
* Creo las opciones de la tabla.
*/
$opcionesTabla =  array('tabla'=>
							array(	'eliminar'			=> false,
									'ordenEnEncabezados'=> false,
									'modificar'			=> false,
									'seleccionMultiple'	=> false));

echo $this->element('desgloses/agregar', array('opcionesTabla'=>$opcionesTabla, 'titulo' => 'Detalles', 'cuerpo' => $cuerpo));

?>