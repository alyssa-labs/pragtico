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
 * @version			$Revision: 1346 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-04 17:28:00 -0300 (vie 04 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['RecibosConcepto'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'RecibosConcepto', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Concepto', 'field' => 'codigo', 'valor' => $v['Concepto']['codigo'], "nombreEncabezado"=>"Codigo");
	$fila[] = array('model' => 'Concepto', 'field' => 'nombre', 'valor' => $v['Concepto']['nombre'], "nombreEncabezado"=>"Concepto");
	$fila[] = array('model' => 'RecibosConcepto', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}


/**
* Creo la tabla.
*/
$opcionesTabla =  array("tabla"=>
							array(	"eliminar"			=>true,
									"ordenEnEncabezados"=>false,
									"modificar"			=>true,
									"seleccionMultiple"	=>false,
									"mostrarEncabezados"=>true,
									"zebra"				=>false,
									"mostrarIds"		=>false));

$url[] = array('controller' => "recibos_conceptos", 'action' => 'add', "RecibosConcepto.recibo_id"=>$this->data['Recibo']['id']);
$texto[] = "Conceptos";
$url[] = array('controller' => "recibos_conceptos", "texto"=>"Carga Rapida", 'action' => "add_rapido", "RecibosConcepto.recibo_id"=>$this->data['Recibo']['id']);
$texto[] = "Carga Rapida";
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Conceptos (Detalle del Recibo)", 'cuerpo' => $cuerpo));


?>