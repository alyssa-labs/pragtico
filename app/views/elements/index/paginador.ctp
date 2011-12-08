<?php

/**
* Creo un bloque con el paginador.
* Lo divido al paginador en navegacion (las flechitas) y la posicion, registro (4 de 10).
*/
$url = array();
foreach (array('named', 'pass') as $nombre) {
	if (!empty($this->params[$nombre])) {
		unset($this->params[$nombre]['direction']);
		unset($this->params[$nombre]['sort']);
		unset($this->params[$nombre]['page']);
		$url = array_merge($url, $this->params[$nombre]);
	}
}

$options = array();
if (!empty($this->params['isAjax'])) {
    $options['update'] = 'lov';
    $options['tipo'] = 'ajax';
}

$bloque_paginador[] = $appForm->tag('div', $paginador->paginador('navegacion', array('url' => $url)), array('class' => 'navegacion'));
$bloque_paginador[] = $appForm->tag('div', $paginador->paginador('posicion'), array('class' => 'posicion'));


/**
* Si hay algun registro, muestro el 'mostrar'.
*/
if (isset($paginador->params['paging'][Inflector::classify($paginador->params['controller'])]['count']) 
   	&& $paginador->params['paging'][Inflector::classify($paginador->params['controller'])]['count'] > 0) {
	
	foreach (array(15, 25, 50, 1000) as $value) {
        if ($value < 1000) {
            $show[$value] = $appForm->link($value, array_merge($url, array('limit' => $value)), array_merge($options, array('title' => sprintf(__('Show %s records', true), $value))));
        } else {
            $show[$value] = $appForm->link(__('A', true), array_merge($url, array('limit' => $value)), array_merge($options, array('title' => sprintf(__('Show all records', true), $value))));
        }
	}
	
	$cantidadActual = $this->params['paging'][Inflector::classify($this->name)]['options']['limit'];
	if ($cantidadActual < 1000) {
		$show[$cantidadActual] = $appForm->tag('span', $cantidadActual);
	} else {
		$show[$cantidadActual] = $appForm->tag('span', __('A', true));
	}
	$bloque_paginador[] = $appForm->tag('div', __('Show', true) . ': ' . implode('/', $show), array('class' => 'cantidad_a_mostrar'));
}

echo implode('', $bloque_paginador) . $this->Js->writeBuffer();
;
?>