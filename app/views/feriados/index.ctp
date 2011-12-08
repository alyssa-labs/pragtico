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
 * @version			$Revision: 332 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-25 16:33:58 -0200 (Wed, 25 Feb 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Feriado-fecha_origen__desde'] = array('label' => 'Origen Desde');
$condiciones['Condicion.Feriado-fecha_origen__hasta'] = array('label' => 'Origen Hasta');
$condiciones['Condicion.Feriado-fecha_efectiva__desde'] = array('label' => 'Efectiva Desde');
$condiciones['Condicion.Feriado-fecha_efectiva__hasta'] = array('label' => 'Efectiva Hasta');
$condiciones['Condicion.Feriado-nombre'] = array();
$condiciones['Condicion.Feriado-trasladable'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'feriados.gif')));

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Feriado', 'field' => 'id', 'valor' => $v['Feriado']['id'], 'write' => $v['Feriado']['write'], 'delete' => $v['Feriado']['delete']);
	$fila[] = array('model' => 'Feriado', 'field' => 'fecha_origen', 'valor' => $v['Feriado']['fecha_origen']);
	$fila[] = array('model' => 'Feriado', 'field' => 'fecha_efectiva', 'valor' => $v['Feriado']['fecha_efectiva']);
    $fila[] = array('model' => 'Feriado', 'field' => 'nombre', 'valor' => $v['Feriado']['nombre']);
    $fila[] = array('model' => 'Feriado', 'field' => 'trasladable', 'valor' => $v['Feriado']['trasladable']);
    $fila[] = array('model' => 'Feriado', 'field' => 'tipo', 'valor' => $v['Feriado']['tipo']);
    
	$cuerpo[] = $fila;
}


$actualizar = $appForm->link('Actualizar', 'update_fron_ws', array('title' => 'Actualizar desde el Ministerio del Interior', 'class' => 'link_boton'));
$accionesExtra['opciones'] = array('acciones' => array('nuevo', 'modificar', 'eliminar', $actualizar));
echo $this->element('index/index', array('condiciones' => $fieldset, 'accionesExtra' => $accionesExtra, 'cuerpo' => $cuerpo));
?>