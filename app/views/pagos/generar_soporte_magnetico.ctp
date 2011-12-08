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
 * @version			$Revision: 1143 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-11-19 09:43:31 -0300 (jue 19 de nov de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 


/**
* Especifico los campos para ingresar las condiciones.
*/
if (empty($confirmar)) {
    $condiciones['Soporte.empleador_id'] =  array(	'lov'	=>array('controller'		=> 'empleadores',
                                                                    'seleccionMultiple'	=> 0,
                                                                    'camposRetorno'		=> array(	'Empleador.cuit',
                                                                                                    'Empleador.nombre')));
    $condiciones['Soporte.cuenta_id'] = array('label' => 'Cuenta', 'type' => 'relacionado', 'relacion' => 'Soporte.empleador_id', 'url' => 'pagos/cuentas_relacionado');
    $condiciones['Soporte.fecha_acreditacion'] = array('value'=>date('Y-m-d'), 'type' => 'date', 'label' => 'Acreditacion', 'aclaracion' => 'Fecha opcional de acreditacion.');

    if (!empty($ids)) {
        $condiciones['Soporte.pago_id'] = array('type' => 'hidden', 'value' => $ids);
    }


    $fieldsets[] = array('campos' => $condiciones);
    $fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Seleccione la cuenta','imagen' => 'bancos.gif')));

    $botonesExtra[] = $appForm->button('Cancelar', array('title' => 'Cancelar', 'class' => 'limpiar', 'onclick' => "document.getElementById('accion').value='cancelar';form.submit();"));

    $botonesExtra[] = $appForm->submit('Generar', array('title' => 'Generar archivo de Soporte', 'onclick' => "document.getElementById('accion').value='generar'"));
} else {
    $campos = null;
    $campos['Soporte.cuenta_id'] = array('type' => 'hidden', 'value' => $confirmar['cuenta']['Cuenta']['id']);
    $campos['Soporte.pago_id'] = array('type' => 'hidden', 'value' => serialize($confirmar['pagos']));
    $campos['Soporte.fecha_acreditacion'] = array('type' => 'hidden', 'value' => $fecha_acreditacion);
    $campos['Bar.cantidad'] = array('type' => 'soloLectura', 'value' => count($confirmar['pagos']));
    $campos['Bar.Total'] = array('type' => 'soloLectura', 'value' => '$ ' . $confirmar['total']);

    $fieldsets[] = array('campos' => $campos);
    $fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Confirmar soporte desde ' . $confirmar['cuenta']['Cuenta']['tipo'] . $confirmar['cuenta']['Cuenta']['cbu'] . ' de ' . $confirmar['cuenta']['Sucursal']['Banco']['nombre'],'imagen' => 'bancos.gif')));
    
    $botonesExtra[] = $appForm->button('Cancelar', array('title' => 'Cancelar', 'class' => 'limpiar', 'onclick' => "document.getElementById('accion').value='cancelar';form.submit();"));
    
    $botonesExtra[] = $appForm->submit('Confirmar', array('title' => 'Confirma la Generaracion del archivo de Soporte', 'onclick' => "document.getElementById('accion').value='confirmar'"));
}


echo $this->element('index/index', array('opcionesTabla' => array('tabla' => array('omitirMensajeVacio' => true)), 'botonesExtra'=>array('opciones' => array('botones' => $botonesExtra)), 'accionesExtra' => array('opciones' => array('acciones'=>array())), 'opcionesForm'=>array('action' => 'generar_soporte_magnetico'), 'condiciones' => $fieldset, 'cuerpo' => null));

?>