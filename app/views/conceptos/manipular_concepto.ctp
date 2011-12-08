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
 * @lastmodified	$Date: 2009-01-27 11:26:49 -0200 (mar 27 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
$campos['Asignar.accion'] = array("value"=>$accion, "type"=>"hidden");
$campos['Asignar.empleador_id'] = array(	
		"lov"=>array(	"controller"	=> "empleadores",
						"camposRetorno"	=> array("Empleador.cuit", "Empleador.nombre")));

$campos['Asignar.concepto_id'] = array("value"=>$concepto['Concepto']['id'], "type"=>"hidden");
$campos['Asignar.convenio_id'] = array("options"=>$convenios, 'type' => 'select', 'multiple' => 'checkbox');
$campos['Asignar.empleador_comportamiento'] = array("value"=>"incluir", "after"=>"<br />Indica la accion que se tomara con los empleadores seleccionados. Si no seleeciona ningun Empleador, se aplicara a todos.", "label"=>"Comportamiento", "options"=>$comportamientos, "type"=>"radio");

$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => ucfirst($accion) . " el Concepto " . $concepto['Concepto']['nombre'] . " a todos los Trabajadores de:", "imagen"=>"conceptos.gif")));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array("opcionesForm"=>array("action"=>"manipular_concepto"), "fieldset"=>$fieldset));

if($accion == "agregar") {
	$msg = "Esta seguro que desea asigar el concepto " . $concepto['Concepto']['nombre'] . " a todos los Trabajadores que pertenezcan a los Convenios Colectivos seleccionados?	";
}
else {
	$msg = "Esta seguro que desea quitar el concepto " . $concepto['Concepto']['nombre'] . " de todos los Trabajadores que pertenezcan a los Convenios Colectivos seleccionados?	";
}

echo $appForm->codeBlock("
	/**
	* Quito el evento por defecto del onclick del submit grabar y lo manejo en esta funcion.
	*/
	jQuery('#boton_grabar').attr('onclick', 'false');
	
	jQuery('#form').submit(
		function () {
			var seleccionado = false;
			jQuery('.checkbox input').each(
				function() {
					if(jQuery(this).attr('checked') == true) {
						seleccionado = true;
					}
				}
			);
			if(!seleccionado) {
				alert('Debe seleccionar al menos un Convenio Colectivo');
				return false;
			}
			if(confirm('" . $msg . "')) {
				jQuery('#accion').attr('value', 'grabar');
				return true;
			}
			else {
				return false;
			}
		}
	);
");
?>