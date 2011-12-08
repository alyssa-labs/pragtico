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
 * @version			$Revision: 914 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-07 16:49:11 -0300 (lun 07 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
if (!empty($grupos)) {
	$condiciones['Condicion.Liquidacion-grupo_id'] = array('options' => $grupos, 'empty' => false);
}
$condiciones['Condicion.Liquidacion-empleador_id'] = array(
		'lov'	=> array('controller'	=> 'empleadores',
						'camposRetorno'	=> array('Empleador.cuit', 'Empleador.nombre')));
$condiciones['Condicion.Bar-periodo_completo'] = array('label' => 'Periodo Liquidado', 'type' => 'periodo', 'periodo' => array('1Q', '2Q', 'M', '1S', '2S', 'A', 'F'));
$condiciones['Condicion.Liquidacion-tipo'] = array('label' => 'Tipo', 'type' => 'select');
$condiciones['Condicion.Liquidacion-estado'] = array('aclaracion' => 'Se refiere a que liquidaciones tomar como base para la prefacturacion. Solo se podran confirmar prefacturaciones realizadas en base a liquidaciones Confirmadas');

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Prefacturar', 'imagen' => 'prefacturar.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'accion', 'id' => $v['Factura']['id'],
					'valor' => $appForm->link(
						$appForm->image('resumen.gif', array('alt' => 'Reporte')),
						array('action' => 'reporte', 'id' => $v['Factura']['id'], $v['Factura']['estado'])));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Factura']['id'], 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Detalles'), 'url' => 'detalles');
	$fila[] = array('model' => 'Factura', 'field' => 'id', 'valor' => $v['Factura']['id'], 'write' => $v['Factura']['write'], 'delete' => $v['Factura']['delete']);
	$fila[] = array('model' => 'Factura', 'field' => 'fecha', 'valor' => $v['Factura']['fecha']);
	$fila[] = array('model' => 'Factura', 'field' => 'tipo', 'valor' => $v['Factura']['tipo']);
	$fila[] = array('model' => 'Factura', 'field' => 'ano', 'valor' => $v['Factura']['ano'] . str_pad($v['Factura']['mes'], 2, '0' ,STR_PAD_LEFT) . $v['Factura']['periodo'], 'class' => 'center', 'nombreEncabezado' => 'Periodo');
	$fila[] = array('model' => 'Factura', 'field' => 'estado', 'valor' => $v['Factura']['estado']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['Empleador']['cuit'], 'class' => 'centro');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Area', 'field' => 'nombre', 'valor' => $v['Area']['nombre'], 'nombreEncabezado' => 'Area');
	$fila[] = array('model' => 'Factura', 'field' => 'total', 'valor' => $v['Factura']['total'], 'tipoDato' => 'moneda');
	
	if ($v['Factura']['confirmable'] === 'No') {
		$cuerpo[] = array('contenido' 	=> $fila,
						  'opciones' 	=> array(
			'title' 			=> 'No podra confirmar esta Factura por haber sido realizada desde Liquidaciones "Sin Confirmar"',
			'class' 			=> 'fila_resaltada'));
	} else {
		$cuerpo[] = $fila;
	}
}

$opcionesTabla =  array('tabla' => array(	'ordenEnEncabezados'=> false,
											'modificar'			=> false,
											'seleccionMultiple'	=> true,
											'eliminar'			=> true,
											'permisos'			=> false));
$accionesExtra['opciones'] = array('acciones' => array($appForm->link('Confirmar', null, array('class' => 'link_boton', 'id' => 'confirmar', 'title' => 'Confirma las preliquidaciones seleccionadas')), $appForm->link('Guardar', null, array('class' => 'link_boton', 'id' => 'guardar', 'title' => 'Guarda las preliquidaciones seleccionadas')), $appForm->link('Imprimir', null, array('class' => 'link_boton', 'id' => 'imprimir', 'title' => 'Imprime las preliquidaciones seleccionadas')), 'eliminar'));
$botonesExtra[] = 'limpiar';
$botonesExtra[] = 'buscar';
$botonesExtra[] = $appForm->submit('Generar', array('title'=>'Genera una Pre-liquidacion', 'onclick'=>'document.getElementById("accion").value="generar"'));
echo $this->element('index/index', array(
		'botonesExtra' 	=> array('opciones' => array('botones'=>$botonesExtra)),
  		'accionesExtra' => $accionesExtra,
		'condiciones' 	=> $fieldset,
  		'cuerpo' 		=> $cuerpo,
		'opcionesTabla'	=> $opcionesTabla,
		'opcionesForm'	=> array('action' => 'prefacturar')));


/**
* Agrego el evento click asociado al boton confirmar.
*/
$appForm->addScript('

	jQuery("#confirmar, #guardar, #imprimir").click(
		function() {
			var c = jQuery(".tabla :checkbox").checkbox("contar");
			if (c > 0) {
				if (jQuery(this).attr("id") == "confirmar") {
					jQuery("#form")[0].action = "' . Router::url(array('controller' => $this->params['controller'], 'action' => 'confirmar')) . '";
				} else if (jQuery(this).attr("id") == "guardar") {
					jQuery("#form")[0].action = "' . Router::url(array('controller' => $this->params['controller'], 'action' => 'guardar')) . '";
				} else {
					jQuery("#form")[0].action = "' . Router::url(array('controller' => $this->params['controller'], 'action' => 'imprimir')) . '";
				}
				jQuery("#form")[0].submit();
			} else {
				alert("Debe seleccionar al menos una pre-facturacion para confirmar.");
			}
		}
	);


    /** Shows / Hides period options, depending receipt type */
    function period(type) {

        jQuery(".1q").hide();
        jQuery(".2q").hide();
        jQuery(".m").hide();
        jQuery(".f").hide();
        jQuery(".1s").hide();
        jQuery(".2s").hide();
        jQuery(".a").hide();
        //jQuery("input.periodo").parent().show();
        jQuery("input.periodo_vacacional").parent().hide();
        
        if (type === "normal") {
            jQuery(".1q").show();
            jQuery(".2q").show();
            jQuery(".m").show();
        } else if (type === "sac") {
            jQuery(".1s").show();
            jQuery(".2s").show();
        } else if (type === "vacaciones") {
            jQuery(".m").show();
            jQuery(".a", jQuery("input.periodo_vacacional").parent()).show();
            jQuery("input.periodo_vacacional").parent().show();
        } else if (type === "especial") {
            jQuery(".1q").show();
            jQuery(".2q").show();
            jQuery(".m").show();
            jQuery(".1s").show();
            jQuery(".2s").show();
            jQuery(".a").show();
        } else if (type === "final") {
            //jQuery("input.periodo").parent().hide();
            jQuery(".f").show();
        }
    }
    period(jQuery("#CondicionLiquidacion-tipo").find(":selected").val());

    jQuery("#CondicionLiquidacion-tipo").change(
        function() {
            jQuery("input.periodo").val("");
            period(jQuery(this).find(":selected").val());
        }
    );' , 'ready');

?>