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
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar, 27 ene 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['DocumentosPatron'] as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'DocumentosPatron', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'DocumentosPatron', 'field' => 'identificador', 'valor' => $v['identificador']);
	$fila[] = array('model' => 'DocumentosPatron', 'field' => 'patron', 'valor' => $v['patron']);
	$cuerpo[] = $fila;
}


/**
* Creo las opciones de la tabla.
*/
$opcionesTabla =  array('tabla'=>
							array(	'eliminar'			=> false,
									'ordenEnEncabezados'=> false,
									'modificar'			=> true,
									'seleccionMultiple'	=> false));

echo $this->element('desgloses/agregar', array('opcionesTabla'=>$opcionesTabla, 'titulo' => 'Detalles', 'cuerpo' => $cuerpo));

?>