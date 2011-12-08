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
 * @version			$Revision: 449 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-04-01 21:11:44 -0300 (mié 01 de abr de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
//if (!empty($grupos)) {
//	$condiciones['Condicion.Liquidacion-grupo_id'] = array('options' => $grupos, 'empty' => true);
//}
$condiciones['Condicion.Suss-empleador_id'] = array('lov' => array(
		'controller'		=> 	'empleadores',
		'seleccionMultiple'	=> 	1,
		'camposRetorno'		=> 	array('Empleador.cuit', 'Empleador.nombre')));
$condiciones['Condicion.Suss-periodo'] = array('type'=>'periodo', 'periodo'=>array('soloAAAAMM'), 'aclaracion' => 'De la forma AAAAMM');
$condiciones['Condicion.Suss-banco_id'] = array('options' => 'listable', 'model'=>'Banco', 'empty'=>true, 'displayField'=>array('Banco.nombre'));
$condiciones['Condicion.Suss-fecha'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Suss', 'imagen' => 'suss.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Suss', 'field' => 'id', 'valor' => $v['Suss']['id'], 'write' => $v['Suss']['write'], 'delete' => $v['Suss']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado'=>'Empleador');
	$fila[] = array('model' => 'Suss', 'field' => 'fecha', 'valor' => $v['Suss']['fecha']);
	$fila[] = array('model' => 'Suss', 'field' => 'periodo', 'valor' => $v['Suss']['periodo']);
	$fila[] = array('model' => 'Banco', 'field' => 'nombre', 'valor' => $v['Banco']['nombre'], 'nombreEncabezado' => 'Banco');
	$cuerpo[] = $fila;
}

echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo));

?>