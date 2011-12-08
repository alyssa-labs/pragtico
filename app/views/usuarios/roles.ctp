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
 * @version			$Revision: 1342 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-03 22:14:29 -0300 (jue 03 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Rol'] as $v) {
	$fila = null;
	$fila[] = array('model' => 'RolesUsuario', 'field' => 'id', 'valor' => $v['RolesUsuario']['id'], 'write'=>$v['RolesUsuario']['write'], 'delete' => $v['RolesUsuario']['delete']);
	$fila[] = array('model' => 'Rol', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'RolesUsuario', 'field' => 'estado', 'valor' => $v['RolesUsuario']['estado']);
	$cuerpo[] = $fila;
}

$url = array(
	'controller' 				=> 'roles_usuarios',
	'action' 					=> 'add',
	'RolesUsuario.usuario_id'	=> $this->data['Usuario']['id']
);
echo $this->element('desgloses/agregar', array(
	'url' 		=> $url,
	'titulo' 	=> 'Roles',
	'cuerpo' 	=> $cuerpo)
);

?>