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
 * @version			$Revision: 1450 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2011-05-30 18:46:11 -0300 (lun 30 de may de 2011) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Ausencia.id'] = array();
$campos['Ausencia.relacion_id'] = array(	"lov"=>array("controller"	=>	"relaciones",
													"seleccionMultiple"	=> 	0,
													"camposRetorno"		=> 	array(	"Empleador.nombre",
																					"Trabajador.apellido")));
																					
$campos['Ausencia.ausencia_motivo_id'] = array(	"empty"			=> true,
												"options"		=> "listable",
												"order"			=> "AusenciasMotivo.motivo",
												"displayField"	=> "AusenciasMotivo.motivo",
												"groupField"	=> "AusenciasMotivo.tipo",
												"model"			=> "AusenciasMotivo",
												"label"			=> "Motivo");
$campos['Ausencia.desde'] = array();
$fieldsets[] = 	array('campos' => $campos);


$campos = null;
$campos['AusenciasSeguimiento.id'] = array();
$campos['AusenciasSeguimiento.dias'] = array();
$campos['AusenciasSeguimiento.comprobante'] = array("label"=>"Presento Comprobante");


$hours = $appForm->link($appForm->image('convert.gif'), null, array('class' => 'hours_to_days', 'title' => 'Convertir a dias', 'escape' => false));
$campos['AusenciasSeguimiento.foo'] = array('after' => $hours, 'label' => 'Horas', 'aclaracion' => 'Para la liquidacion solo se tendra el campo dias. Este valor solo simplifica la conversion de horas a dias para jornadas de 8 horas');

$minutes = $appForm->link($appForm->image('convert.gif'), null, array('class' => 'minutes_to_days', 'title' => 'Convertir a dias', 'escape' => false));
$campos['AusenciasSeguimiento.foo1'] = array('after' => $minutes, 'label' => 'Minutos', 'aclaracion' => 'Para la liquidacion solo se tendra el campo dias. Este valor solo simplifica la conversion de minutos a dias para jornadas de 8 horas');

$campos['AusenciasSeguimiento.archivo'] = array("label"=>"Comprobante", "type"=>"file", "descargar"=>true, "mostrar"=>true);
$campos['AusenciasSeguimiento.estado'] = array('type' => 'radio');
$campos['AusenciasSeguimiento.observacion'] = array();
$fieldsets[] = 	array('campos' => $campos, 'opciones' => array('fieldset' => array("class"=>"detail", 'legend' => "Seguimientos", 'imagen' => 'seguimientos.gif')));

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "Ausencias", 'imagen' => 'ausencias.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$miga = array('format' 	=> '%s %s (%s)', 
			  'content' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));
echo $this->element("add/add", array('fieldset' => $fieldset, "opcionesForm"=>array("enctype"=>"multipart/form-data"), 'miga' => $miga));

$extraJs = array();
if (!empty($this->data)) {
    foreach ($this->data as $ausencia) {
		if (!empty($ausencia['AusenciasSeguimiento'])) {
			foreach ($ausencia['AusenciasSeguimiento'] as $k => $seguimiento) {
				$extraJs[] = 'verifyStates(' . $k . ');';
			}
		}
    }
}
$appForm->addScript('


	jQuery(".hours_to_days").click(
		function() {
			var e = jQuery("input", jQuery(this).parent());
			if (isNaN(e.val())) {
				alert("Debe ingresar un numero valido para las horas");
			} else {
				var v = e.val()  / 8;
				v = Math.round(v * 100) / 100;
				jQuery("#AusenciasSeguimientoDia_" + e.attr("id").split("_").pop()).val(v);
			}
		}
	);


	jQuery(".minutes_to_days").click(
		function() {
			var e = jQuery("input", jQuery(this).parent());
			if (isNaN(e.val())) {
				alert("Debe ingresar un numero valido para los minutos");
			} else {
				var v = e.val() / 480;
				v = Math.round(v * 100) / 100;
				jQuery("#AusenciasSeguimientoDia_" + e.attr("id").split("_").pop()).val(v);
			}
		}
	);


    detalle();
    jQuery("a.link_boton").bind("click", agregar);

    jQuery.detailAfterAdd = function(frameSetId, elementId) {
        jQuery("#AusenciasSeguimientoEstado" + (frameSetId - 1) + "Pendiente_" + frameSetId).attr("checked", true);
        jQuery("#AusenciasSeguimientoEstado" + (frameSetId - 1) + "Pendiente_" + frameSetId).removeAttr("disabled");
        jQuery("#AusenciasSeguimientoEstado" + (frameSetId - 1) + "Confirmado_" + frameSetId).removeAttr("disabled");
        jQuery("#AusenciasSeguimientoEstado" + (frameSetId - 1) + "Liquidado_" + frameSetId).attr("disabled", true);
    }
        
    var verifyStates = function(id) {
        if (jQuery("#AusenciasSeguimientoEstado" + id + "Liquidado").attr("checked")) {
            jQuery("#AusenciasSeguimientoEstado" + id + "Pendiente").attr("disabled", true);
            jQuery("#AusenciasSeguimientoEstado" + id + "Confirmado").attr("disabled", true);
        } else {
            jQuery("#AusenciasSeguimientoEstado" + id + "Liquidado").attr("disabled", true);
        }
    }
    ' . implode('', $extraJs));
?>