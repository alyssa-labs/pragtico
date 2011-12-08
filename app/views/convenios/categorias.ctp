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
foreach ($this->data['ConveniosCategoria'] as $k=>$v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], "update"=>"desglose_1", 'imagen' => array('nombre' => 'historicos.gif', 'alt' => "Historicos"), "url"=>"../convenios_categorias/historicos/");
	$fila[] = array('model' => 'ConveniosCategoria', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'ConveniosCategoria', 'field' => 'nombre', 'valor' => $v['nombre'], "nombreEncabezado"=>"Categoria");
	$fila[] = array('model' => 'ConveniosCategoria', 'field' => 'costo', 'valor' => $v['costo'], "tipoDato"=>"moneda");
	$fila[] = array('model' => 'ConveniosCategoria', 'field' => 'jornada', 'valor' => $v['jornada']);
	$fila[] = array('model' => 'ConveniosCategoria', 'field' => 'observacion', 'valor' => $v['observacion']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "convenios_categorias", 'action' => 'add', "ConveniosCategoria.convenio_id"=>$this->data['Convenio']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Categorias", 'cuerpo' => $cuerpo));


?>