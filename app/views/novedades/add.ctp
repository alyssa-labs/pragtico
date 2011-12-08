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
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (Tue, 27 Jan 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Novedad.id'] = array();
$campos['Novedad.alta'] = array('type' => 'hidden', 'value' => date('d/m/Y'));
if (empty($this->data)) {
    $campos['Novedad.tipo'] = array('readonly' => true, 'type' => 'text', 'value' => 'Concepto');
} else {
    $campos['Novedad.tipo'] = array('readonly' => true, 'type' => 'text');
}
$campos['Novedad.relacion_id'] = array(
        'lov'   => array('controller'   => 'relaciones',
                        'seleccionMultiple'    =>  0,
                        'camposRetorno' => array(   'Empleador.cuit',
                                                    'Empleador.nombre',
                                                    'Trabajador.cuil',
                                                    'Trabajador.nombre',
                                                    'Trabajador.apellido')));
$campos['Novedad.periodo'] = array('type' => 'periodo');
$campos['Novedad.liquidacion_tipo'] = array('label' => 'Tipo Liquidacion', 'aclaracion' => 'Indica para que tipo de liquidacion sera tenida en cuenta al momento de preliquidar');
if (!empty($this->data[0]['Novedad']['concepto_id']) || empty($this->data)) {
    $campos['Novedad.concepto_id'] = array( "lov"=>array("controller"   =>  "conceptos",
                                                            "seleccionMultiple" =>  0,
                                                                "camposRetorno" =>  array(  "Concepto.codigo",
                                                                                            "Concepto.nombre")));
}
$campos['Novedad.data'] = array('label' => 'Valor', 'type' => 'text');
$campos['Novedad.estado'] = array('options' => array('Pendiente' => 'Pendiente', 'Confirmada' => 'Confirmada'));
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'novedades.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => 'Novedad.nombre'));
?>