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
 * @version			$Revision: 572 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-06-02 16:34:55 -0300 (mar 02 de jun de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Descuento.id'] = array();
$campos['Descuento.relacion_id'] = array(	'label'=>'Relacion',
											'lov'=>array('controller'	=>	'relaciones',
													'seleccionMultiple'	=> 	0,
														'camposRetorno'	=>	array(	'Trabajador.nombre',
																					'Trabajador.apellido',
																					'Empleador.nombre')));
$campos['Descuento.alta'] = array();
$campos['Descuento.desde'] = array();
$campos['Descuento.hasta'] = array();
$campos['Descuento.descripcion'] = array('aclaracion' => 'Esta descripcion saldra impresa en el recibo.');
$campos['Descuento.monto'] = array('label' => 'Monto $', 'aclaracion' => 'Se refiere al monto total a descontar.');
$campos['Descuento.cuotas'] = array();
//$campos['Descuento.sobre_smvm'] = array('aclaracion' => 'Indica si el porcentaje a descontarse debe ser por sobre el SMVM. En caso de seleccionar la opcion "No", se tomara sobre el total del sueldo.');
$campos['Descuento.descontar'] = array('multiple' => 'checkbox');
$campos['Descuento.concurrencia'] = array();
$campos['Descuento.tipo'] = array();
$campos['Descuento.porcentaje'] = array('after' => '%', 'aclaracion' => 'Se refiere al porcentaje a descontar de la Cuota Alimentaria.');
$campos['Descuento.estado'] = array();
$campos['Descuento.observacion'] = array();
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'descuentos.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
$appForm->addScript('
    if (jQuery("#DescuentoTipoCuotaAlimentaria:checked").val() == undefined) {
        jQuery("#DescuentoPorcentaje").parent().hide();
    }
    
    jQuery(":radio", jQuery("#DescuentoTipoCuotaAlimentaria").parent().parent()).click(
        function() {
            if (jQuery(this).val() == "Cuota Alimentaria") {
                jQuery("#DescuentoPorcentaje").parent().show();
            } else {
                jQuery("#DescuentoPorcentaje").parent().hide();
            }
        }
    );
');
?>