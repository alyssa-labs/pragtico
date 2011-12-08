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
 * @version			$Revision: 925 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-09 23:19:50 -0300 (mié 09 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['SiapsDetalle.id'] = array();
$campos['SiapsDetalle.siap_id'] = array('options' => 'listable', 'model' => 'Siap', 'displayField' => array('Siap.version'));
$campos['SiapsDetalle.elemento'] = array();
$campos['SiapsDetalle.valor'] = array();
$campos['SiapsDetalle.valor_maximo'] = array();
$campos['SiapsDetalle.tipo'] = array('empty' => true);
$campos['SiapsDetalle.descripcion'] = array();
$campos['SiapsDetalle.caracter_relleno'] = array();
$campos['SiapsDetalle.direccion_relleno'] = array();
$campos['SiapsDetalle.desde'] = array();
$campos['SiapsDetalle.longitud'] = array();
$campos['SiapsDetalle.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'Detalles de la Version de SIAP', 'imagen' => 'detalles.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>