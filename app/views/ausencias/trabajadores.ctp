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
 * @version         $Revision: 346 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-27 18:47:43 -0200 (Fri, 27 Feb 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
$fila = null;
$fila[] = array('model' => 'Trabajador', 'field' => 'id', 'valor' => $this->data['Relacion']['Trabajador']['id'], 'write' => $this->data['Relacion']['Trabajador']['write'], 'delete' => $this->data['Relacion']['Trabajador']['delete']);
$fila[] = array('model' => 'Trabajador', 'field' => 'telefono', 'valor' => $this->data['Relacion']['Trabajador']['telefono']);
$fila[] = array('model' => 'Trabajador', 'field' => 'direccion', 'valor' => $this->data['Relacion']['Trabajador']['direccion']);
$fila[] = array('model' => 'Trabajador', 'field' => 'codigo_postal', 'valor' => $this->data['Relacion']['Trabajador']['codigo_postal']);
$fila[] = array('model' => 'Trabajador', 'field' => 'barrio', 'valor' => $this->data['Relacion']['Trabajador']['barrio']);
$fila[] = array('model' => 'Trabajador', 'field' => 'ciudad', 'valor' => $this->data['Relacion']['Trabajador']['ciudad']);
$fila[] = array('model' => 'Localidad', 'field' => 'nombre', 'valor' => $this->data['Relacion']['Trabajador']['Localidad']['nombre'], 'nombreEncabezado' => 'Localidad');
$cuerpo[] = $fila;

echo $this->element('desgloses/agregar', array('titulo' => 'Trabajador', 'cuerpo' => $cuerpo));

?>