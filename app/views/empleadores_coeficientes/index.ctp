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
 * @version			$Revision: 1362 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-15 16:27:48 -0300 (mar 15 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.EmpleadoresCoeficiente-empleador_id'] = array(	'lov'=>array('controller'	=>	'empleadores',
																		'camposRetorno'	=>array('Empleador.cuit',
																								'Empleador.nombre')));
$condiciones['Condicion.Coeficiente-nombre'] = array();
$condiciones['Condicion.Coeficiente-tipo'] = array('type' => 'select', 'multiple' => 'checkbox');
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Coeficientes de los Empleadores', 'imagen' => 'coeficientes.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'id', 'valor' => $v['EmpleadoresCoeficiente']['id'], 'write' => $v['EmpleadoresCoeficiente']['write'], 'delete' => $v['EmpleadoresCoeficiente']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'nombreEncabezado' => 'Empleador', 'valor' => $v['Empleador']['cuit'] . ' - ' . $v['Empleador']['nombre']);
	$fila[] = array('model' => 'Coeficiente', 'field' => 'nombre', 'nombreEncabezado' => 'Coeficiente', 'valor' => $v['Coeficiente']['nombre']);
	$fila[] = array('model' => 'Coeficiente', 'field' => 'tipo', 'valor' => $v['Coeficiente']['tipo']);
    $fila[] = array('model' => 'Coeficiente', 'field' => 'valor', 'valor' => $v['Coeficiente']['valor']);
	$fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'porcentaje', 'valor' => $v['EmpleadoresCoeficiente']['porcentaje'], 'tipoDato' => 'percentage');
    $fila[] = array('model' => 'EmpleadoresCoeficiente', 'field' => 'porcentaje', 'valor' => $formato->format($v['Coeficiente']['valor'] + ($v['Coeficiente']['valor'] * $v['EmpleadoresCoeficiente']['porcentaje'] / 100), array('type' => 'number', 'places' => 4)), 'nombreEncabezado' => 'Total');
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>