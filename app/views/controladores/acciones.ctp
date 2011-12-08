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
foreach ($this->data['Accion'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'Accion', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Accion', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Accion', 'field' => 'etiqueta', 'valor' => $v['etiqueta']);
	$fila[] = array('model' => 'Accion', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}


/**
* Creo la tabla.
*/
$opcionesTabla =  array("tabla"=>
							array(	"eliminar"			=>true,
									"ordenEnEncabezados"=>false,
									"modificar"			=>true,
									"seleccionMultiple"	=>false,
									"mostrarEncabezados"=>true,
									"zebra"				=>false,
									"mostrarIds"		=>false));
									
$url = array('controller' => "acciones", 'action' => 'add', "Accion.controlador_id"=>$this->data['Controlador']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Acciones", 'cuerpo' => $cuerpo));
//echo $appForm->bloque($appForm->tabla(am(array('cuerpo' => $cuerpo), $opcionesTabla)), array('div' => array('class' => 'unica')));


?>