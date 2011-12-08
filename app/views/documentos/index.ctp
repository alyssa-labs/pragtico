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
 * @version			$Revision: 329 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-25 12:28:43 -0200 (mié 25 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Documento-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'documentos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
    $fila[] = array('tipo' => 'desglose', 'id' => $v['Documento']['id'], 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Patterns'), 'url' => 'patterns');
	$fila[] = array('model' => 'Documento', 'field' => 'id', 'valor' => $v['Documento']['id'], 'write' => $v['Documento']['write'], 'delete' => $v['Documento']['delete']);
	$fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('archivo.gif', array('alt' => 'Descargar')), array('action' => 'download', $v['Documento']['id'])));
	$fila[] = array('model' => 'Documento', 'field' => 'nombre', 'valor' => $v['Documento']['nombre']);
	$fila[] = array('model' => 'Documento', 'field' => 'model', 'valor' => $v['Documento']['model']);
	$fila[] = array('model' => 'Documento', 'field' => 'observacion', 'valor' => $v['Documento']['observacion']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index',
		array(	'accionesExtra'	=> array('opciones' => array('acciones' => array('nuevo', 'eliminar'))),
				'condiciones'	=> $fieldset,
				'opcionesTabla'	=> array('tabla' => array('modificar' => false)),
				'cuerpo' 		=> $cuerpo));

?>