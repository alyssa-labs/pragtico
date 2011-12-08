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
 * @version			$Revision: 182 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-12-23 09:34:19 -0200 (mar 23 de dic de 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Grupo'] as $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'update' => 'desglose_1', 'imagen' =>array('nombre' => 'usuarios.gif', 'alt' => 'Usuarios'), 'url' => array('controller' => 'grupos', 'action' => 'usuarios'));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['id'], 'update' => 'desglose_2', 'imagen' =>array('nombre' => 'parametros.gif', 'alt' => 'Parametros'), 'url' => array('controller' => 'grupos', 'action' => 'parametros'));
	$fila[] = array('model' => 'GruposUsuario', 'field' => 'id', 'valor' => $v['GruposUsuario']['id'], 'write' => $v['GruposUsuario']['write'], 'delete' => $v['GruposUsuario']['delete']);
	$fila[] = array('model' => 'Grupo', 'field' => 'nombre', 'valor' => $v['nombre']);
 	$fila[] = array('model' => 'GruposUsuario', 'field' => 'estado', 'valor' => $v['estado']);
	$cuerpo[] = $fila;
}

$url = array(	'controller' 				=> 'grupos_usuarios', 
			 	'action' 					=> 'add', 
	 			'GruposUsuario.usuario_id' 	=> $this->data['Usuario']['id']);
	 			
echo $this->element('desgloses/agregar', 
	array(	'url' 		=> $url, 
		  	'titulo' 	=> 'Grupos', 
	 		'cuerpo' 	=> $cuerpo));

?>