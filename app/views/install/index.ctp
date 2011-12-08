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
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (Tue, 27 Jan 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$show = true;
foreach ($writables as $k => $v) {
	if ($v) {
		$image = 'ok.gif';
	} else {
		$show = false;
		$image = 'error.gif';
	}
	$campos['Install.' . $k] = array('label' => false, 'type' => 'soloLectura', 'value' => $appForm->image($image) . ' ' . $k);
}
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Permisos de escritura', 'imagen' => 'permisos.gif')));

if ($show) {
	$campos = null;
	$campos['Install.host'] = array('label' => 'Servidor', 'value' => $this->data['Install']['host']);
	$campos['Install.name'] = array('label' => 'Base de Datos', 'value' => $this->data['Install']['name']);
	$campos['Install.username'] = array('label' => 'Usuario', 'value' => $this->data['Install']['username']);
	$campos['Install.password'] = array('label' => 'Clave', 'type' => 'password', 'value' => $this->data['Install']['password']);
	$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Conexion a la Base de Datos', 'imagen' => 'identificacion.gif')));
}

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => '!Instalacion de Pragtico', 'imagen' => 'localidades.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$accionesExtra['opciones']['acciones'] = array($appForm->submit(__('Instalar', true)));
echo $this->element('add/add', array('opcionesForm' => array('action' => 'index'), 'fieldset' => $fieldset, 'accionesExtra' => $accionesExtra));
?>