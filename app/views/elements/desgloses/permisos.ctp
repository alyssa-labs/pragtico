<?php

$atributosCelda = $atributosFila = null;
$id = $registro[Inflector::classify($this->name)]['id'];
$options = 	array(	'tipo'	=>'ajax',
					'update'=>'div_permisos_' . $id);

$cellsOut = array();
$operaciones = __getImagen(&$appForm, $registro['puedeCambiarPermisos'], true, 't', $id, 'Todos');
$operaciones .= __getImagen(&$appForm, $registro['puedeCambiarPermisos'], false, 't', $id, 'Todos');
$cellsOut[] = $appForm->tag('th', $operaciones);

$operaciones = __getImagen(&$appForm, $registro['puedeCambiarPermisos'], true, 'lt', $id, 'Lectura a Todos');
$operaciones .= __getImagen(&$appForm, $registro['puedeCambiarPermisos'], false, 'lt', $id, 'Lectura a Todos');
$cellsOut[] = $appForm->tag('th', $operaciones . 'Leer', array('class'=>'centro'));

$operaciones = __getImagen(&$appForm, $registro['puedeCambiarPermisos'], true, 'et', $id, 'Escritura a Todos');
$operaciones .= __getImagen(&$appForm, $registro['puedeCambiarPermisos'], false, 'et', $id, 'Escritura a Todos');
$cellsOut[] = $appForm->tag('th', $operaciones . 'Escribir', array('class'=>'centro'));

$operaciones = __getImagen($appForm, $registro['puedeCambiarPermisos'], true, 'dt', $id, 'Eliminar a Todos');
$operaciones .= __getImagen($appForm, $registro['puedeCambiarPermisos'], false, 'dt', $id, 'Eliminar a Todos');
$cellsOut[] = $appForm->tag('th', $operaciones . 'Eliminar', array('class'=>'centro'));
$out[] = $appForm->tag('tr', $cellsOut, $atributosFila);


/**
* EL DUENO
*/
$cellsOut = array();
$operaciones = __getImagen($appForm, $registro['puedeCambiarPermisos'], true, 'td', $id, 'Todo al Dueño');
$operaciones .= __getImagen($appForm, $registro['puedeCambiarPermisos'], false, 'td', $id, 'Todo al Dueño');
$cellsOut[] = $appForm->tag('th', $operaciones . ' Dueño: ' . $registro['Usuarios']['nombre_completo'], array('class'=>'izquierda'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Usuarios']['permisos']['leer'], 'dl', $id), array('class'=>'centro'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Usuarios']['permisos']['escribir'], 'de', $id), array('class'=>'centro'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Usuarios']['permisos']['eliminar'], 'dd', $id), array('class'=>'centro'));
$out[] = $appForm->tag('tr', $cellsOut, $atributosFila);

/**
* EL GRUPO
*/
$cellsOut = array();
$operaciones = __getImagen($appForm, $registro['puedeCambiarPermisos'], true, 'tg', $id, 'Todo al Grupo');
$operaciones .= __getImagen($appForm, $registro['puedeCambiarPermisos'], false, 'tg', $id, 'Todo al Grupo');
$cellsOut[] = $appForm->tag('th', $operaciones . ' Grupo/s:', array('class'=>'izquierda'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Grupos']['permisos']['leer'], 'gl', $id), array('rowspan'=>count($registro['Grupo']) + count($registro['Rol']) + 1, 'class'=>'centro'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Grupos']['permisos']['escribir'], 'ge', $id), array('rowspan'=>count($registro['Grupo']) + count($registro['Rol']) + 1, 'class'=>'centro'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Grupos']['permisos']['eliminar'], 'gd', $id), array('rowspan'=>count($registro['Grupo']) + count($registro['Rol']) + 1, 'class'=>'centro'));
$out[] = $appForm->tag('tr', $cellsOut, $atributosFila);
unset($registro['Grupos']['permisos']);

foreach($registro['Grupo'] as $k=>$v){
	$cellsOut = array();
	if(isset($v['Grupo']['posible_accion']) && $v['Grupo']['posible_accion'] === 'agregar') {
		$cellsOut[] = $appForm->tag('th', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $appForm->link($appForm->image('add.gif', array('alt' => 'Agregar')), array('action'=>'permisos', $id, 'agregarGrupo'=>$v['Grupo']['id']), $options) . '&nbsp;' . $v['Grupo']['nombre'] . ' <i>(No Incluido)</i>', array('class'=>'izquierda'));
	}
	elseif(isset($v['Grupo']['posible_accion']) && $v['Grupo']['posible_accion'] === 'quitar') {
		$cellsOut[] = $appForm->tag('th', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $appForm->link($appForm->image('remove.gif', array('alt' => 'Quitar')), array('action'=>'permisos', $id, 'quitarGrupo'=>$v['Grupo']['id']), $options) . '&nbsp;' . $v['Grupo']['nombre'] . ' <i>(Incluido)</i>', array('class'=>'izquierda'));
	}
	else {
		$cellsOut[] = $appForm->tag('th', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $appForm->image('grupos.gif') . '&nbsp;' . $v['Grupo']['nombre'], array('class'=>'izquierda'));
	}
	$out[] = $appForm->tag('tr', $cellsOut, $atributosFila);
}

/**
* Los roles se muestran igual que los grupos.
*/
foreach($registro['Rol'] as $k=>$v){
	$cellsOut = array();
	if(isset($v['Rol']['posible_accion']) && $v['Rol']['posible_accion'] === 'agregar') {
		$cellsOut[] = $appForm->tag('th', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $appForm->link($appForm->image('add.gif', array('alt' => 'Agregar')), array('action'=>'permisos', $id, 'agregarRol'=>$v['Rol']['id']), $options) . '&nbsp;' . $v['Rol']['nombre'] . ' <i>(No Incluido)</i>', array('class'=>'izquierda'));
	}
	elseif(isset($v['Rol']['posible_accion']) && $v['Rol']['posible_accion'] === 'quitar') {
		$cellsOut[] = $appForm->tag('th', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $appForm->link($appForm->image('remove.gif', array('alt' => 'Quitar')), array('action'=>'permisos', $id, 'quitarRol'=>$v['Rol']['id']), $options) . '&nbsp;' . $v['Rol']['nombre'] . ' <i>(Incluido)</i>', array('class'=>'izquierda'));
	}
	else {
		$cellsOut[] = $appForm->tag('th', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $appForm->image('roles.gif') . '&nbsp;' . $v['Rol']['nombre'], array('class'=>'izquierda'));
	}
	$out[] = $appForm->tag('tr', $cellsOut, $atributosFila);
}


$cellsOut = array();
$operaciones = __getImagen($appForm, $registro['puedeCambiarPermisos'], true, 'to', $id, 'Todo a los Demas');
$operaciones .= __getImagen($appForm, $registro['puedeCambiarPermisos'], false, 'to', $id, 'Todo a los Demas');
$cellsOut[] = $appForm->tag('th', $operaciones . ' Otros', array('class'=>'izquierda'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Otros']['permisos']['leer'], 'ol', $id), array('class'=>'centro'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Otros']['permisos']['escribir'], 'oe', $id), array('class'=>'centro'));
$cellsOut[] = $appForm->tag('td', __getImagen($appForm, $registro['puedeCambiarPermisos'], $registro['Otros']['permisos']['eliminar'], 'od', $id), array('class'=>'centro'));
$out[] = $appForm->tag('tr', $cellsOut, $atributosFila);


$tabla = $appForm->tag('table', $appForm->tag('tbody', $out));

/**
* Pongo todo dentro de un div y muestro el resultado.
*/
echo $appForm->tag('div', $tabla, array('id'=>'div_permisos_' . $id));


function __getImagen(&$appForm, $puedeCambiarPermisos, $permiso, $accion, $id, $altAdicional='') {
	$parametros[] = $id;
	$options = 	array(	'tipo'		=>'ajax',
						'update'	=>'div_permisos_' . $id);
						
	if (!empty($altAdicional)) {
		if (!$puedeCambiarPermisos) {
			return '';
		}
		$altAdicional = ' ' . $altAdicional;
		if (!$permiso) {
			$parametros['accion'] = 'd' . $accion;
			$imagen = $appForm->image('error_icono_naranja.gif');
			$options['title'] = 'Denegar' . $altAdicional;
		} else {
			$parametros['accion'] = 'p' . $accion;
			$imagen = $appForm->image('ok_icono_verde.gif');
			$options['title'] = 'Permitir' . $altAdicional;
		}
	} else {
		if ($permiso) {
			$parametros['accion'] = 'd' . $accion;
			$imagen = $appForm->image('ok_icono_verde.gif');
			$options['title'] = 'Denegar' . $altAdicional;
		} else {
			$parametros['accion'] = 'p' . $accion;
			$imagen = $appForm->image('error_icono_naranja.gif');
			$options['title'] = 'Permitir' . $altAdicional;
		}
	}

	if ($puedeCambiarPermisos) {
		return $appForm->link($imagen, $parametros, $options);
	} else {
		return $imagen;
	}
}


?>