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
 * @version			$Revision: 346 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-27 18:47:43 -0200 (vie 27 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['AusenciasSeguimiento'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'AusenciasSeguimiento', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'AusenciasSeguimiento', 'field' => 'dias', 'valor' => $v['dias']);
	$fila[] = array('model' => 'AusenciasSeguimiento', 'field' => 'comprobante', 'valor' => $v['comprobante']);
	$fila[] = array('model' => 'AusenciasSeguimiento', 'field' => 'estado', 'valor' => $v['estado']);
	if ($v['estado'] === 'Liquidado') {
		$fila[] = array('tipo' => 'desglose', 'id' => $v['liquidacion_id'], 'imagen' => array('nombre' => 'liquidaciones.gif', 'alt' => 'liquidaciones'), 'url' => array('controller' => 'liquidaciones', 'action' => 'recibo_html'));
		$cuerpo[] = array('contenido' => $fila, 'opciones' => array('seleccionMultiple' => false, 'eliminar' => false, 'modificar' => false));
	} else {
		$cuerpo[] = $fila;
	}
}

$url = array('controller' => "ausencias_seguimientos", 'action' => 'add', "AusenciasSeguimiento.ausencia_id"=>$this->data['Ausencia']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Seguimientos", 'cuerpo' => $cuerpo));

?>