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
 * @version			$Revision: 248 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-03 13:42:43 -0200 (mar 03 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */

/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Auditoria-created__desde'] = array('label' => 'Desde');
$condiciones['Condicion.Auditoria-created__hasta'] = array('label' => 'Hasta');
$condiciones['Condicion.Auditoria-tipo'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Auditoria-usuario'] = array();
$condiciones['Condicion.Auditoria-ip'] = array('label' => 'Direccion IP');

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'auditorias.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Auditoria', 'field' => 'id', 'valor' => $v['Auditoria']['id'], 'write'=>$v['Auditoria']['write'], 'delete'=>$v['Auditoria']['delete']);
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Auditoria']['id'], 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Detalles'), 'url' => 'detalles');
	$fila[] = array('model' => 'Auditoria', 'field' => 'tipo', 'valor' => $v['Auditoria']['tipo']);
	$fila[] = array('model' => 'Auditoria', 'field' => 'usuario', 'valor' => $v['Auditoria']['usuario']);
	$fila[] = array('model' => 'Auditoria', 'field' => 'created', 'valor' => $v['Auditoria']['created'], 'nombreEncabezado' => 'Fecha');
	$fila[] = array('model' => 'Auditoria', 'field' => 'ip', 'valor' => $v['Auditoria']['ip'], 'nombreEncabezado' => 'Direccion IP');
	$cuerpo[] = $fila;
}

$opcionesTabla =  array('tabla'=> array(
									'eliminar'			=> false,
									'modificar'			=> false,
									'seleccionMultiple'	=> false,
									'permisos'			=> false));
$accionesExtra['opciones'] = array('acciones'=>array());									
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'opcionesTabla'=>$opcionesTabla, 'accionesExtra'=>$accionesExtra));


?>