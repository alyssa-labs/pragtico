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
foreach ($this->data['RopasDetalle'] as $k=>$v) {
	$fila = null;
	//$urls = array("delete"=>"../ropas_detalles/delete/" . $v['id'], "edit"=>"../ropas/edit/" . $this->data['Ropa']['id']);
	//$fila[] = array("tipo"=>"idDetail", "urls"=>$urls);
	$fila[] = array('model' => 'RopasDetalle', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'RopasDetalle', 'field' => 'prenda', 'valor' => $v['prenda']);
	$fila[] = array('model' => 'RopasDetalle', 'field' => 'tipo', 'valor' => $v['tipo']);
	$fila[] = array('model' => 'RopasDetalle', 'field' => 'color', 'valor' => $v['color']);
	$fila[] = array('model' => 'RopasDetalle', 'field' => 'modelo', 'valor' => $v['modelo']);
	$fila[] = array('model' => 'RopasDetalle', 'field' => 'tamano', 'valor' => $v['tamano']);
	$cuerpo[] = $fila;
}


/**
* Creo la tabla.
*/
/*
$opcionesTabla =  array("tabla"=>
							array(	"eliminar"			=>true,
									"ordenEnEncabezados"=>false,
									"modificar"			=>true,
									"seleccionMultiple"	=>false,
									"mostrarEncabezados"=>true,
									"zebra"				=>false,
									"mostrarIds"		=>false));


echo $appForm->bloque($appForm->tabla(am(array('cuerpo' => $cuerpo), $opcionesTabla)), array('div' => array('class' => 'unica')));
*/
$url = array('controller' => "ropas_detalles", 'action' => 'add', "RopasDetalle.ropa_id"=>$this->data['Ropa']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Prendas", 'cuerpo' => $cuerpo));

?>