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
 * @version			$Revision: 1445 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2011-05-26 12:17:44 -0300 (jue 26 de may de 2011) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Relacion-empleador_id'] = array(	'lov'=>array('controller'	=> 'empleadores',
																		'camposRetorno'	=> array(	'Empleador.cuit',
																									'Empleador.nombre')));

$condiciones['Condicion.Relacion-trabajador_id'] = array(	'lov'=>array('controller'	=> 'trabajadores',
																		'camposRetorno'	=> array(	'Trabajador.cuil',
																									'Trabajador.nombre',
																									'Trabajador.apellido')));

$condiciones['Condicion.Relacion-area_id'] = array(
        'label' => 'Area',
        'mask'  =>  '%s, %s',
        'lov'   => array('controller'   => 'areas',
                        'camposRetorno' => array(   'Empleador.nombre',
                                                    'Area.nombre')));

$condiciones['Condicion.Liquidacion-tipo'] = array('label' => 'Tipo', 'type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Bar-facturado'] = array('label' => 'Facturado', 'type' => 'select', 'multiple' => 'checkbox', 'options' => array('Si' => 'Si', 'No' => 'No'));

$condiciones['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo Liquidacion', 'type' => 'periodo', 'periodo' => array('1Q', '2Q', 'M', '1S', '2S', 'A'));

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Liquidaciones','imagen' => 'liquidaciones.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Liquidacion']['id'], 'imagen' => array('nombre' => 'liquidaciones.gif', 'alt' => 'liquidaciones'), 'url' => 'recibo_html');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Liquidacion']['id'], 'imagen' => array('nombre' => 'liquidaciones.gif', 'alt' => 'liquidaciones (debug)'), 'url' => 'recibo_html_debug');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Liquidacion']['id'], 'imagen' => array('nombre' => 'pagos.gif', 'alt' => 'Pagos'), 'url' => 'pagos');
	$fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('excel.gif', array('alt' => 'Generar Recibo Completo', 'title' => 'Generar Recibo Completo')), array('action' => 'imprimir', 'tipo' => 'preimpreso', 'id' => $v['Liquidacion']['id'])));
    $fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Recibo para Impresion', 'title' => 'Generar Recibo para Impresion')), array('action' => 'imprimir', 'id' => $v['Liquidacion']['id'])));
	$fila[] = array('model' => 'Liquidacion', 'field' => 'id', 'valor' => $v['Liquidacion']['id'], 'write' => $v['Liquidacion']['write'], 'delete' => $v['Liquidacion']['delete']);
	$fila[] = array('model' => 'Liquidacion', 'field' => 'tipo', 'valor' => $v['Liquidacion']['tipo']);
	$fila[] = array('model' => 'Liquidacion', 'field' => 'fecha', 'valor'=>$v['Liquidacion']['fecha']);
	$fila[] = array('model' => 'Liquidacion', 'field' => 'ano', 'valor' => $v['Liquidacion']['ano'] . str_pad($v['Liquidacion']['mes'], 2, '0' ,STR_PAD_LEFT) . $v['Liquidacion']['periodo'], 'nombreEncabezado'=>'Periodo');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado'=>'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Trabajador']['nombre'] . ' ' . $v['Trabajador']['apellido'], 'nombreEncabezado'=>'Trabajador');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'remunerativo', 'valor'=>$v['Liquidacion']['remunerativo'], 'tipoDato' => 'moneda', 'nombreEncabezado' => 'Rem.');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'deduccion', 'valor'=>$v['Liquidacion']['deduccion'], 'tipoDato' => 'moneda', 'nombreEncabezado' => 'Ded.');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'no_remunerativo', 'valor'=>$v['Liquidacion']['no_remunerativo'], 'tipoDato' => 'moneda', 'nombreEncabezado' => 'No Rem.');
	$fila[] = array('model' => 'Liquidacion', 'field' => 'total', 'valor' => $v['Liquidacion']['total'], 'tipoDato' => 'moneda');
	$cuerpo[] = $fila;
}
$accionesExtra['opciones'] = array('acciones' => array(
    $appForm->link('Imprimir', null, array('class' => 'link_boton', 'id' => 'imprimir_preimpreso', 'title' => 'Imprime las preliquidaciones seleccionadas')),
    $appForm->link('Impr. Simple', null, array('class' => 'link_boton', 'id' => 'imprimir', 'title' => 'Imprime las preliquidaciones seleccionadas'))));


echo $this->element('index/index', array(
        'accionesExtra' => $accionesExtra,
        'condiciones'   => $fieldset,
        'opcionesTabla' => array('tabla' => array('eliminar' => false, 'modificar' => false)),
        'cuerpo'        => $cuerpo));


/**
* Agrego el evento click asociado al boton confirmar.
*/
if (!empty($zipFileName)) {
    $appForm->addScript('
        window.location = "' . Router::url('/', true) . 'files/tmp/' . $zipFileName . '";
    ');
}

$appForm->addScript('

        
        
	jQuery("#imprimir_preimpreso, #imprimir").click(
		function() {
			var c = jQuery(".tabla :checkbox").checkbox("contar");
			if (c > 0) {
                if (jQuery(this).attr("id") == "imprimir_preimpreso") {
                    jQuery("#accion").attr("value", "preimpreso")
                } else {
                    jQuery("#accion").attr("value", "buscar")
                }
                jQuery("#form")[0].action = "' . Router::url(array('controller' => $this->params['controller'], 'action' => 'imprimir')) . '";
				jQuery("#form")[0].submit();
			} else {
				alert("Debe seleccionar al menos una pre-liquidacion para imprimir.");
			}
		}
	);', 'ready');


?>