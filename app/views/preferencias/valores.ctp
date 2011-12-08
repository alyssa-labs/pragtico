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
foreach ($this->data['PreferenciasValor'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'PreferenciasValor', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'PreferenciasValor', 'field' => 'valor', 'valor' => $v['valor']);
	$fila[] = array('model' => 'PreferenciasValor', 'field' => 'predeterminado', 'valor' => $v['predeterminado']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "preferencias_valores", 'action' => 'add', "PreferenciasValor.preferencia_id"=>$this->data['Preferencia']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Valores", 'cuerpo' => $cuerpo));

?>