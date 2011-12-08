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
 * @version			$Revision: 978 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-17 09:42:08 -0300 (jue 17 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($this->data['Coeficiente'] as $k=>$v) {
	$fila = null;
	$fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'id', 'valor' => $v['EmpleadoresCoeficiente']['id'], 'write' => $v['EmpleadoresCoeficiente']['write'], 'delete' => $v['EmpleadoresCoeficiente']['delete']);
	$fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'nombre', 'valor' => $v['nombre']);
	$fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'tipo', 'valor' => $v['tipo']);
    $fila[] = array('model' => 'Coeficiente', 'field' => 'valor', 'valor' => $v['valor']);
	$fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'porcentaje', 'valor' => $v['EmpleadoresCoeficiente']['porcentaje'], 'tipoDato' => 'percentage');
    $fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'porcentaje', 'valor' =>
    $formato->format($v['valor'] + ($v['valor'] * $v['EmpleadoresCoeficiente']['porcentaje'] / 100), array('type' => 'number', 'places' => 4)), 'nombreEncabezado' => 'Total');
	$cuerpo[] = $fila;
}

$url[] = array('controller' => 'empleadores_coeficientes', 'action' => 'add', 'EmpleadoresCoeficiente.empleador_id' => $this->data['Empleador']['id']);
$url[] = array('controller' => 'empleadores_coeficientes', 'action' => 'add_rapido', 'EmpleadoresCoeficiente.empleador_id' => $this->data['Empleador']['id'], 'texto'=>'Carga Rapida');

echo $this->element('desgloses/agregar', array('url' => $url, 'titulo' => 'Coeficiente', 'cuerpo' => $cuerpo));

?>