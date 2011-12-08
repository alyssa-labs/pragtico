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
foreach ($this->data['Usuario'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'GruposUsuario', 'field' => 'id', 'valor' => $v['GruposUsuario']['id'], 'write' => $v['GruposUsuario']['write'], 'delete' => $v['GruposUsuario']['delete']);
	$fila[] = array('model' => 'Usuario', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'Usuario', 'field' => 'nombre_completo', 'valor' => $v['nombre_completo']);
 	$fila[] = array('model' => 'Usuario', 'field' => 'ultimo_ingreso', 'valor' => $v['ultimo_ingreso']);
 	$fila[] = array('model' => 'Usuario', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "grupos_usuarios", 'action' => 'add', "GruposUsuario.grupo_id"=>$this->data['Grupo']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Usuarios", 'cuerpo' => $cuerpo));

?>