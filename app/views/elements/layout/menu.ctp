<?php
$MenuItems = $session->read('__itemsMenu');

$menu = '';
foreach ($MenuItems as $k => $padre) {
	
	$hijos = '';
	foreach ($padre['children'] as $k1 => $hijo) {
		if (empty($hijo['Menu']['ayuda'])) {
			$hijo['Menu']['ayuda'] = $hijo['Menu']['etiqueta'];
		}

		if (empty($hijo['Menu']['imagen'])) {
			$hijo['Menu']['imagen'] = $hijo['Menu']['nombre'] . '.gif';
		}
		
		/*
		$url = array(	'controller'	=> $hijo['Menu']['controller'],
						'action'		=> $hijo['Menu']['action'],
						'am'			=> $k);
		*/
        $params = array('am' => $k);
        if (!empty($hijo['Menu']['params'])) {
            foreach (explode('/', $hijo['Menu']['params']) as $key => $value) {
                $params[$key] = $value;
            }
        }
        
		$url = array_merge(
            array(	'controller'	=> $hijo['Menu']['controller'],
                    'action'		=> $hijo['Menu']['action']), $params);

        $hijos .= $appForm->tag('div', $appForm->link($appForm->image($hijo['Menu']['imagen']) . $appForm->tag('span', $hijo['Menu']['etiqueta']), $url, array('title' => $hijo['Menu']['ayuda'])));
	}
	$children =  $appForm->tag('div', $hijos, array('class' => 'panel'));
	
	if (empty($padre['Menu']['ayuda'])) {
		$padre['Menu']['ayuda'] = $padre['Menu']['etiqueta'];
	}
	if (empty($padre['Menu']['imagen'])) {
		$padre['Menu']['imagen'] = $padre['Menu']['nombre'] . '.gif';
	}
	$parent =  $appForm->link($appForm->image($padre['Menu']['imagen']) . $appForm->tag('span', $padre['Menu']['etiqueta']), null, array('class' => 'header index' . $k, 'title' => $padre['Menu']['ayuda']));
	$menu .= $appForm->tag('div', $parent . $children);
}
echo $appForm->tag('div', $menu, array('class' => 'menu'));
?>