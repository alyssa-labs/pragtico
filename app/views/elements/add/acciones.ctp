<?php

/**
* Agrego descripcion adicional al form y los botones.
* El boton de Grabar y cancelar.
*/
$acciones[] = $appForm->input("Form.accion", array("type" => "hidden", "id" => "accion", "value" => "grabar"));
$cancelar = $appForm->button(__('Cancel', true), array("class" => "cancelar", "onclick" => "jQuery('#accion').val('cancelar');form.submit();"));
$duplicar = $appForm->button(__('Duplicate', true), array("onclick" => "jQuery('#accion').val('duplicar');document.getElementById('form').action='../save';form.submit();"));
$eliminar = $appForm->button(__('Delete', true), array("class" => "boton_rojo", "onclick" => "jQuery('#accion').val('delete');document.getElementById('form').action='../delete/#*ID*#/1';form.submit();"));
$grabar = $appForm->submit(__('Save', true), array('id' => 'boton_grabar', 'onclick' => 'jQuery("#accion").val("grabar");form.submit();'));

if(isset($accionesExtra['opciones']['acciones'])) {
	foreach($accionesExtra['opciones']['acciones'] as $v) {
		switch ($v) {
			case 'cancelar':
				$acciones[] = $cancelar;
				break;
			case 'duplicar':
				$acciones[] = $duplicar;
				break;
			case 'eliminar':
				$acciones[] = $eliminar;
				break;
			case 'grabar':
				$acciones[] = $grabar;
				break;
			default:
				$acciones[] = $v;
				break;
		}
	}
} else {
	if($this->params['action'] === 'add') {
		$acciones[] = $appForm->tag('div', $appForm->input('Form.volverAInsertar', array('div' => false, 'label' => 'Insertar un nuevo registro despues de grabar', 'type' => 'checkbox', 'checked' => 'false')), array('class' => 'volver_a_insertar'));
	}
	$acciones[] = $cancelar;
	if($this->params['action'] === 'edit' && count($this->data) === 1) {
		$acciones[] = str_replace('#*ID*#', $this->data[0][Inflector::classify($this->params['controller'])]['id'], $eliminar);
		$acciones[] = $duplicar;
	}
	$acciones[] = $grabar;
	if (!empty($accionesExtra)) {
		$acciones = array_merge($acciones, $accionesExtra);
	}
}
if(!empty($acciones)) {
	echo $appForm->tag('div', $acciones, array('class' => 'botones'));
}

?>