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
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Concepto'] as $v) {
	$fila = null;
	$fila[] = array('model' => 'Concepto', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Concepto', 'field' => 'codigo', 'valor' => $v['codigo']);
	$fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'valor' => $v['nombre']);
	$cuerpo[] = $fila;
}

$url = array('controller' => 'conceptos', 'action' => 'add', 'Concepto.conceptos_familia_id' => $this->data['ConceptosFamilia']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Conceptos', 'cuerpo' => $cuerpo));

?>