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
 * @version			$Revision: 1143 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-11-19 09:43:31 -0300 (Thu, 19 Nov 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 


$campos = null;
$campos['Relacion.id'] = array('label' => 'Relacion', 'lov' => array(
	'controller'		=> 'relaciones',
	'seleccionMultiple'	=> 	0,
	'camposRetorno'		=> 	array('Empleador.nombre', 'Trabajador.apellido')));

$campos['Relacion.ingreso'] = array('type' => 'date');

$botonesExtra[] = $appForm->button('Cancelar', array('title' => 'Cancelar', 'class' => 'limpiar', 'onclick' => "document.getElementById('accion').value='cancelar';form.submit();"));

$botonesExtra[] = $appForm->submit('Confirmar', array('title' => 'Confirma el reingreso', 'onclick' => "document.getElementById('accion').value='confirmar'"));

$fieldsets[] = array('campos' => $campos);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Reingreso', 'imagen' => 'relaciones.gif')));

echo $this->element('index/index', array('opcionesTabla' => array('tabla' => array('omitirMensajeVacio' => true)), 'botonesExtra'=>array('opciones' => array('botones' => $botonesExtra)), 'accionesExtra' => array('opciones' => array('acciones'=>array())), 'opcionesForm'=>array('action' => 'reingreso'), 'condiciones' => $fieldset, 'cuerpo' => null));

?>