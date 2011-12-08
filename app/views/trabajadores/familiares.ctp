<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.views
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 236 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-01-27 11:26:49 -0200 (Tue, 27 Jan 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Familiar'] as $k => $v) {
    $fila = null;
    $fila[] = array('model' => 'Familiar', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
    $fila[] = array('model' => 'Familiar', 'field' => 'parentezco', 'valor' => $v['parentezco']);
    $fila[] = array('model' => 'Familiar', 'field' => 'apellido', 'valor' => $v['apellido']);
    $fila[] = array('model' => 'Familiar', 'field' => 'nombre', 'valor' => $v['nombre']);
    $fila[] = array('model' => 'Familiar', 'field' => 'celular', 'valor' => $v['celular']);
    $cuerpo[] = $fila;
}

$url = array('controller' => 'familiares', 'action' => 'add', 'Familiar.trabajador_id' => $this->data['Trabajador']['id']);
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Familiares', 'cuerpo' => $cuerpo));

?>