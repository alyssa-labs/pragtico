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
 * @version			$Revision: 1445 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2011-05-26 12:17:44 -0300 (jue 26 de may de 2011) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Empleador-nombre'] = array();
$condiciones['Condicion.Empleador-cuit'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'empleadores.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$id = $v['Empleador']['id'];
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'trabajadores.gif', 'alt' => 'Trabajadores Activos'), 'url' => 'relaciones');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'conceptos.gif', 'alt' => 'Conceptos'), 'url' => 'conceptos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'coeficientes.gif', 'alt' => 'Coeficientes'), 'url' => 'coeficientes');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'areas.gif', 'alt' => 'Areas'), 'url' => 'areas');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'recibos.gif', 'alt' => 'Recibos'), 'url' => 'recibos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'rubros.gif', 'alt' => 'Rubros'), 'url' => 'rubros');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'cuentas.gif', 'alt' => 'Cuentas'), 'url' => 'cuentas');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'suss.gif', 'alt' => 'Suss'), 'url' => 'suss');
	$fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Documento')), array('controller' => 'documentos', 'action' => 'generar', 'model' => 'Empleador', 'id' => $id)));
	$fila[] = array('model' => 'Empleador', 'field' => 'id', 'valor' => $v['Empleador']['id'], 'write' => $v['Empleador']['write'], 'delete' => $v['Empleador']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['Empleador']['cuit'], 'class'=>'centro');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre']);
	$fila[] = array('model' => 'Empleador', 'field' => 'telefono', 'valor' => $v['Empleador']['telefono']);
	$fila[] = array('model' => 'Empleador', 'field' => 'email', 'valor' => $v['Empleador']['email']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));
?>