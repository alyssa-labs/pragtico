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
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar 27 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Convenio'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'ConveniosConcepto', 'field' => 'id', 'valor' => $v['ConveniosConcepto']['id'], 'write' => $v['ConveniosConcepto']['write'], 'delete' => $v['ConveniosConcepto']['delete']);
	$fila[] = array('model' => 'Convenio', 'field' => 'numero', 'valor' => $v['numero']);
	$fila[] = array('model' => 'Convenio', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'ConveniosConcepto', 'field' => 'formula', 'valor' => $v['ConveniosConcepto']['formula']);
	$fila[] = array('model' => 'ConveniosConcepto', 'field' => 'desde', 'valor' => $v['ConveniosConcepto']['desde']);
	$fila[] = array('model' => 'ConveniosConcepto', 'field' => 'hasta', 'valor' => $v['ConveniosConcepto']['hasta']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "convenios_conceptos", 'action' => 'add', "ConveniosConcepto.concepto_id"=>$this->data['Concepto']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Convenios", 'cuerpo' => $cuerpo));

?>