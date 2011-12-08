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
 * @version         $Revision: 332 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-25 16:33:58 -0200 (Wed, 25 Feb 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['RelacionesHistorial'] as $v) {
    $fila = null;
    if ($v['estado'] == 'Pendiente') {
        $fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('ok.gif', array('alt' => 'Confirmar')), array('controller' => 'relaciones_historiales', 'action' => 'confirmar', $v['id'])));
    }
    $fila[] = array('model' => 'RelacionesHistorial', 'field' => 'id', 'valor' => $v['id'], 'write' => $v['write'], 'delete' => $v['delete']);
    $fila[] = array('model' => 'RelacionesHistorial', 'field' => 'inicio', 'valor' => $v['inicio']);
    $fila[] = array('model' => 'RelacionesHistorial', 'field' => 'fin', 'valor' => $v['fin']);
    $fila[] = array('model' => 'EgresosMotivo', 'field' => 'motivo', 'valor' => $v['EgresosMotivo']['motivo']);
    $fila[] = array('model' => 'RelacionesHistorial', 'field' => 'estado', 'valor' => $v['estado']);
    $fila[] = array('model' => 'RelacionesHistorial', 'field' => 'liquidacion_final', 'valor' => $v['liquidacion_final']);
    $fila[] = array('model' => 'RelacionesHistorial', 'field' => 'observacion', 'valor' => $v['observacion']);
    $cuerpo[] = $fila;
}

$url[] = array('controller' => 'relaciones_historiales', 'action' => 'add', 'RelacionesHistorial.relacion_id' => $this->data['Relacion']['id']);
$url[] = array('controller' => 'relaciones', 'action' => 'reingreso', 'Relacion.id' => $this->data['Relacion']['id'], 'texto' => 'Reingreso');
echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Historial', 'cuerpo' => $cuerpo));

?>