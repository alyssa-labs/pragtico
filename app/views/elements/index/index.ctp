<?php

/**
 * Creo la miga de pan.
 */
$appForm->addCrumb($this->name,
		array('controller' 	=> $this->params['controller'],
			'action' 		=> 'index'));
$appForm->addCrumb(__('Grid', true));


/**
 * Me aseguro que todas las variables queden inicializadas.
 */
if(!isset($botonesExtra)) {
	$botonesExtra = array();
}
if(!isset($separadorRetorno)) {
	$separadorRetorno = '';
}
if(empty($opcionesForm)) {
	$opcionesForm['action'] = 'index';
}
if(!isset($pie)) {
	$pie = array();
}


/**
 * Pongo el nombre del controller como un parametro, aunque no lo use, de modo de que el cache cree un archivo 
 * por cada controlador.
 */
//$botones = $this->element('index/buscadores', array('cache'=>'+30 day', $this->name=>'name', 'botonesExtra'=>$botonesExtra, 'opcionesForm'=>$opcionesForm));
$botones = $this->element('index/buscadores', array('botonesExtra' => $botonesExtra, 'opcionesForm' => $opcionesForm));

$bloques[] = $appForm->tag('div', $condiciones . $botones, array('class' => 'unica conditions_frame'));


/**
 * Agrego los botones de las acciones.
 * Nuevo y eliminar desde la seleccion multiple.
 *
 * Pongo el nombre del controller como un parametro, aunque no lo use, de modo de que el cache cree un archivo 
 * por cada controlador.
 */
//$acciones = $this->element('index/acciones', array('cache'=>'+30 day', $this->name=>'name', 'accionesExtra'=>$accionesExtra));
if (isset($accionesExtra) && $accionesExtra === false) {
	$acciones = '';
} else {
	if (!isset($accionesExtra)) {
		$accionesExtra = array();
	}
	$acciones = $this->element('index/acciones', array('accionesExtra' => $accionesExtra));
}


if (!isset($opcionesTabla)) {
	//$opcionesTabla = array('tabla'=>array('permisos'=>false));
	$opcionesTabla = array();
}

/**
 * Creo un bloque con el paginador superior e inferior.
 */
//$paginador = $this->element('index/paginador', array('cache'=>'+30 day', $this->name=>'name'));
$paginador = $this->element('index/paginador');
$bloque_paginador_superior = $appForm->tag('div', $paginador, array('class' => 'paginador_superior'));
$bloque_paginador_inferior = $appForm->tag('div', $paginador, array('class' => 'paginador_inferior'));

/**
 * Creo la tabla.
 */
if(!isset($cuerpo)) {
	$cuerpo = null;
}


/**
 * Agrego el cuerpo de la tabla, siempre y cuendo este no este vacio y no tenga seteado el valor de omitir mensaje vacio.
 */
if(!empty($opcionesTabla['tabla']['contenido'])) {
	$bloques[] = $opcionesTabla['tabla']['contenido'];
} else if(!((isset($opcionesTabla['tabla']['omitirMensajeVacio']) && $opcionesTabla['tabla']['omitirMensajeVacio'] === true) && empty($cuerpo))) {
	$tabla = $appForm->tag('div', $appForm->tabla(array_merge(array('cuerpo' => $cuerpo, 'pie' => $pie), $opcionesTabla)), array('class' => 'tabla'));
	$bloque_superior = $appForm->tag('div', $acciones . $bloque_paginador_superior, array('class' => 'bloque_superior_index'));
	$bloque_inferior = $appForm->tag('div', $bloque_paginador_inferior, array('class' => 'bloque_inferior_index'));
	$bloques[] = $appForm->tag('div', $bloque_superior . $tabla . $bloque_inferior, array('class' => 'unica'));
}


/**
 * Creo el formulario y pongo todo dentro.
 */
$form = $appForm->form($bloques, $opcionesForm);


/**
* Pongo todo dentro de un div (index) y muestro el resultado, siempre y cuando no sea un request ajax.
* Si es ajax y creo el div, voy a meter un div, dentro de otro, dentro de otro, dentro de otro....
*/
echo $appForm->tag('div', $form, array('class' => 'index', 'id' => 'index'));

/** Add breakdown js code */
$appForm->addScript('
	jQuery("img.breakdown_icon").bind("click", breakdown);
');
$javascript->link('breakdowns.min', false);

?>