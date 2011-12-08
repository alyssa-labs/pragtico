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
 * @version			$Revision: 373 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-06 10:35:32 -0200 (vie 06 de mar de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['ConveniosCategoriasHistorico'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'ConveniosCategoriasHistorico', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Convenio', 'field' => 'nombre', 'valor' => $this->data['Convenio']['nombre']);
	$fila[] = array('model' => 'ConveniosCategoria', 'field' => 'nombre', 'valor'=>$this->data['ConveniosCategoria']['nombre']);
	$fila[] = array('model' => 'ConveniosCategoriasHistorico', 'field' => 'desde', 'valor' => $v['desde']);
	$fila[] = array('model' => 'ConveniosCategoriasHistorico', 'field' => 'hasta', 'valor' => $v['hasta']);
	$fila[] = array('model' => 'ConveniosCategoriasHistorico', 'field' => 'costo', 'valor' => $v['costo'], 'tipoDato' => 'currency');
	$cuerpo[] = $fila;
}

$url = array('controller' => 'convenios_categorias_historicos', 'action' => 'add', 'ConveniosCategoriasHistorico.convenios_categoria_id' => $this->data['ConveniosCategoria']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Historico de Categorias', 'cuerpo' => $cuerpo));

?>