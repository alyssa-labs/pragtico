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
 * @version			$Revision: 1008 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-22 13:39:12 -0300 (mar 22 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Banco.id'] = array();
$campos['Banco.codigo'] = array();
$campos['Banco.nombre'] = array();
$campos['Banco.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$campos = null;
$campos['Sucursal.id'] = array();
$campos['Sucursal.codigo'] = array();
$campos['Sucursal.direccion'] = array();
$campos['Sucursal.barrio'] = array();
$campos['Sucursal.localidad'] = array();
$campos['Sucursal.provincia_id'] = array('options' => 'listable', 'model' => 'Provincia', 'displayField' => array('Provincia.nombre'));
$campos['Sucursal.telefono'] = array();
$campos['Sucursal.observacion'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('fieldset' => array('class'=>'detail', 'legend' => 'Sucursal', 'imagen' => 'sucursales.gif')));

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'bancos.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = array('format' 	=> '(%s) %s', 
			  'content' => array('Banco.codigo', 'Banco.nombre'));

echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => $miga));
$appForm->addScript('
    detalle();
    jQuery("a.link_boton").bind("click", agregar);
');
?>