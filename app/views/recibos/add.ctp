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
 * @version			$Revision: 1360 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-11 13:42:05 -0300 (vie 11 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Recibo.id'] = array();
if (!empty($this->params['named']['Recibo.empleador_id'])
	|| (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'empleador')) {
	$campos['Recibo.empleador_id'] = array(
		'lov'				=> array(
			'controller'		=> 'empleadores',
			'seleccionMultiple'	=> 0,
			'camposRetorno'		=> array('Empleador.cuit', 'Empleador.nombre')
		)
	);
}

$defaultName = '';
if (!empty($this->params['named']['Recibo.convenio_id'])
	|| (!empty($this->params['pass'][0]) && $this->params['pass'][0] == 'convenio')) {

	if (!empty($this->data['Convenio']['Convenio']['nombre'])) {
		$defaultName = $this->data['Convenio']['Convenio']['nombre'];
	}

	$campos['Recibo.convenio_id'] = array(
		'lov'				=> array(
			'controller'		=> 'convenios',
			'seleccionMultiple'	=> 0,
			'camposRetorno'		=> array('Convenio.numero', 'Convenio.nombre')
		)
	);
}
$campos['Recibo.nombre'] = array('default' => $defaultName);
$campos['Recibo.descripcion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'recibos.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/

$accionesExtra['opciones']['acciones'][] = 'cancelar';
$accionesExtra['opciones']['acciones'][] = $appForm->button('Guardar y Cont.', array(
	'title'		=> 'Guardar y continuar agregando los conceptos del recibo',
	'onclick' 	=> "jQuery('#accion').val('grabar_continuar|controller:recibos_conceptos|action:add_rapido|RecibosConcepto.recibo_id:##ID##');form.submit();"
	)
);
$accionesExtra['opciones']['acciones'][] = 'grabar';

echo $this->element('add/add', array('fieldset' => $fieldset, 'accionesExtra' => $accionesExtra));
?>