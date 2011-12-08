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
 * @version			$Revision: 1423 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Area.id'] = array();
$campos['Area.empleador_id'] = array('lov' => array(
		'controller'		=>  'empleadores',
		'seleccionMultiple'	=> 	0,
		'camposRetorno'		=>	array(	'Empleador.cuit',
										'Empleador.nombre')));
$campos['Area.zone_id'] = array('label' => 'Zona', 
								'lov' 	=> array(
		'controller'		=>	'zones',
		'seleccionMultiple'	=> 	0,
		'separadorRetorno'	=> 	', ',
		'camposRetorno'		=>	array(	'Zone.code',
										'Zone.name')));
$campos['Area.nombre'] = array();
$campos['Area.identificador'] = array('aclaracion' => 'Se refiere a un identificador externo. No se utilizara dentro del Sistema.');
if (!empty($centrosDeCosto)) {
    $campos['Area.identificador_centro_costo'] = array('options' => $centrosDeCosto, 'aclaracion' => 'Se refiere a un identificador interno de centro de costo relacionado al grupo.');
}
$campos['Area.contacto'] = array();
$campos['Area.direccion'] = array();
$campos['Area.ciudad'] = array();
$campos['Area.provincia_id'] = array(
	'empty'			=> true,
	'options'		=> 'listable',
	'recursive'		=> -1,
	'order'			=> 'Provincia.nombre ASC',
	'displayField'	=> 'Provincia.nombre',
	'model'			=> 'Provincia');

$campos['Area.codigo_postal'] = array();
$campos['Area.telefono'] = array();
$campos['Area.fax'] = array();
$campos['Area.pago'] = array('empty' => true);
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'areas.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => 'Area.nombre'));
?>