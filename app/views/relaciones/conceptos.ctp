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
 * @version			$Revision: 771 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-07-28 11:15:14 -0300 (mar 28 de jul de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['RelacionesConcepto'] as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'RelacionesConcepto', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Concepto', 'field' => 'codigo', 'valor' => $v['Concepto']['codigo']);
	$fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'valor' => $v['Concepto']['nombre']);
    $fila[] = array('model' => 'Bar', 'field' => 'foo', 'valor' => $v['Concepto']['jerarquia'], 'nombreEncabezado' => 'Jerarquia');
    $fila[] = array('model' => 'Concepto', 'field' => 'formula', 'valor' => $v['Concepto']['formula']);
	$cuerpo[] = $fila;
}

$url[] = array('controller' => "relaciones_conceptos", 'action' => 'add', "RelacionesConcepto.relacion_id"=>$this->data['Relacion']['id']);
$url[] = array('controller' => "relaciones_conceptos", 'action' => "add_rapido", "RelacionesConcepto.relacion_id"=>$this->data['Relacion']['id'], "texto"=>"Carga Rapida");
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Conceptos", 'cuerpo' => $cuerpo));

?>