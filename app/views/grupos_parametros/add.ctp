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
 * @version			$Revision: 384 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-09 15:34:18 -0200 (lun 09 de mar de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['GruposParametro.id'] = array();
$campos['GruposParametro.grupo_id'] = array('options' => 'listable', 'model'=>'Grupo', 'displayField' => 'Grupo.nombre', 'empty'=>true);
$campos['GruposParametro.parametro_id'] = array('options' => 'listable', 'model'=>'Parametro', 'displayField' => 'Parametro.nombre', 'empty'=>true);
$campos['GruposParametro.valor'] = array();
$campos['GruposParametro.descripcion'] = array();

$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'Parametro del Grupo', 'imagen' => 'parametros.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>