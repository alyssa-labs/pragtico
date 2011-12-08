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
 * @version			$Revision: 803 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-07-30 21:33:24 -0300 (jue 30 de jul de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.ConveniosConcepto-convenio_id'] = array("lov"=>array("controller"	=>	"convenios",
															"camposRetorno"	=>array("Convenio.numero",
																					"Convenio.nombre")));
$condiciones['Condicion.Concepto-codigo'] = array();
$condiciones['Condicion.Concepto-nombre'] = array();
$condiciones['Condicion.Concepto-tipo'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Conceptos de los Convenios Colectivos", 'imagen' => 'conceptos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'ConveniosConcepto', 'field' => 'id', 'valor' => $v['ConveniosConcepto']['id'], 'write' => $v['ConveniosConcepto']['write'], 'delete' => $v['ConveniosConcepto']['delete']);
	$fila[] = array('model' => 'Convenio', 'field' => 'nombre', 'nombreEncabezado' => 'Convenio', 'valor' => $v['Convenio']['nombre']);
	$fila[] = array('model' => 'Concepto', 'field' => 'codigo', 'valor' => $v['Concepto']['codigo']);
	$fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'nombreEncabezado' => 'Concepto', 'valor' => $v['Concepto']['nombre']);
	$fila[] = array('model' => 'ConveniosConcepto', 'field' => 'formula', 'valor' => $v['ConveniosConcepto']['formula']);
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>