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
 * @version			$Revision: 386 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-03-09 15:59:15 -0200 (lun 09 de mar de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['GruposParametro'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'GruposParametro', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
	$fila[] = array('model' => 'Parametro', 'field' => 'nombre', 'valor' => $v['Parametro']['nombre']);
	$fila[] = array('model' => 'GruposParametro', 'field' => 'valor', 'valor' => $v['valor']);
	$cuerpo[] = $fila;
}

$url = array('controller' => "grupos_parametros", 'action' => 'add', "GruposParametro.grupo_id"=>$this->data['Grupo']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => "Parametros", 'cuerpo' => $cuerpo));

?>