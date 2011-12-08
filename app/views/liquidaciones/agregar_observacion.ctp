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
 * @version			$Revision: 344 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-26 15:58:10 -0200 (jue 26 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Liquidacion.id'] = array();
$campos['Liquidacion.observacion'] = array();
$fieldset = $appForm->pintarFieldsets(array(array('campos' => $campos)), array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'Obervaciones', 'imagen' => 'observaciones.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, 'opcionesForm' => array('action' => 'save')));

?>