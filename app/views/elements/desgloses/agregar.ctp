<?php
/**
* TODO
* Que no se habra dentro de una popup cuando esta en una lov.
*/
$acciones = array();
if (!empty($url) && !isset($url[0])) {
	$tmp = $url;
	$url = false;
	$url[] = $tmp;
}

if (!empty($url)) {
	foreach($url as $v) {
		if(!isset($v['texto']) && $v['action'] === 'add') {
			$texto = 'Nuevo';
		} else {
			$texto = $v['texto'];
			unset($v['texto']);
		}
		$acciones[] = $appForm->link($texto, $v, array('class' => 'link_boton'));
	}
}

/**
* Creo la tabla con las opciones por default para un desglose.
*/
$opcionesTablaDefault =	array(	'eliminar'				=> true,
								'ordenEnEncabezados'	=> false,
								'modificar'				=> true,
								'seleccionMultiple'		=> false,
								'mostrarEncabezados'	=> true,
								'permisos'				=> false,
								'zebra'					=> false,
								'mostrarIds'			=> false);
if(!empty($opcionesTabla)) {
	$opcionesTabla['tabla'] = array_merge($opcionesTablaDefault, $opcionesTabla['tabla']);
} else {
	$opcionesTabla['tabla'] = $opcionesTablaDefault;
}

if(empty($texto)) {
	$texto = '';
}

$codigoHtml = $appForm->tag('span', $titulo, array('class' => 'titulo'));
if (isset($acciones)) {
	$codigoHtml .= $appForm->tag('span', $acciones, array('class' => 'acciones'));
}
$codigoHtml = $appForm->tag('div', $codigoHtml, array('class' => 'cabecera'));
echo $appForm->tag('div', $codigoHtml . $appForm->tag('div', $appForm->tabla(array_merge(array('cuerpo' => $cuerpo), $opcionesTabla)), array('class' => 'tabla')), array('class' => 'unica'));
?>