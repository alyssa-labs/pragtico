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
 * @version			$Revision: 1345 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-04 16:17:50 -0300 (Fri, 04 Jun 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.ConceptosFamilia-nombre'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Familia de Conceptos', 'imagen' => 'conceptos_familias.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['ConceptosFamilia']['id'], 'imagen' => array('nombre' => 'conceptos.gif', 'alt' => 'Conceptos'), 'url' => 'conceptos');
	$fila[] = array('model' => 'ConceptosFamilia', 'field' => 'id', 'valor' => $v['ConceptosFamilia']['id'], 'write' => $v['ConceptosFamilia']['write'], 'delete' => $v['ConceptosFamilia']['delete']);
	$fila[] = array('model' => 'ConceptosFamilia', 'field' => 'nombre', 'valor' => $v['ConceptosFamilia']['nombre']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>