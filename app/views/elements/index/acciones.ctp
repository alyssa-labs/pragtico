<?php

/**
* Agrego los botones de las acciones.
* Nuevo, eliminar y modificar desde la seleccion multiple.
*
* Ejemplos:
* Solo el boton de accion nuevo y uno propio del formulario.
* $accionesExtra['opciones'] = array("acciones"=>array("nuevo", $appForm->bloque($appForm->link("Importar Planilla", "importarPlanillas", array("class"=>"link_boton", "title"=>"Importa las planillas de ingreso masivo de horas")))));
*
* Ninguna accion.
* $accionesExtra['opciones'] = array("acciones"=>array());
*/
$modificar = $appForm->link(__('Edit', true), null, 
		array("id"=>"modificar", "class"=>"link_boton", "title"=>__('Edit selected records', true)));
$nuevo = $appForm->link(__('New', true), 'add', 
		array("class"=>"link_boton", "title"=>__('Insert a new record', true)));
$eliminar = $appForm->link(__('Delete', true), null, 
		array("id"=>"eliminar", "class"=>"link_boton_rojo", "title"=>__('Delete selected records', true)));

if ((!empty($this->params['named']['layout']) && $this->params['named']['layout'] == "lov")) {
	echo $appForm->tag('div', '&nbsp;', array("class"=>"botones_acciones"));
} else {
	if (isset($accionesExtra['opciones']['acciones'])) {
		foreach ($accionesExtra['opciones']['acciones'] as $v) {
			switch ($v) {
				case "nuevo":
					$acciones[] = $nuevo;
					break;
				case "modificar":
					$acciones[] = $modificar;
					break;
				case "eliminar":
					$acciones[] = $eliminar;
					break;
				default:
					$acciones[] = $v;
					break;
			}
		}
	} else {
		$acciones[] = $nuevo;
		$acciones[] = $modificar;
		$acciones[] = $eliminar;
		$acciones = array_merge($acciones, $accionesExtra);
	}
	if (!empty($acciones)) {
		echo $appForm->tag('div', $acciones, array("class"=>"botones_acciones"));
	}
}

?>