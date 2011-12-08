<?php

$salir = $appForm->link($appForm->image('salir.gif'), array('controller' => 'usuarios', 'action' => 'logout'), array('title'=>__('Logout', true)));
$limpiar = $appForm->link($appForm->image('limpiar.gif'), null, array('id'=>'bandaLimpiar', 'title'=>__('Clean all searches', true)));
$cerrar = $appForm->link($appForm->image('cerrar.gif'), null, array('id'=>'closeAllBreakdowns', 'title'=>__('Close all breakdowns', true)));
$ocultar = $appForm->link($appForm->image('pinchado.gif'), null, array('id'=>'hideConditions', 'title'=>__('Hide condition frame', true)));

$href = Router::url('/') . $this->params['controller'];
$appForm->addScript("var bandaLimpiar = function() {ajaxGet('" . $href . "/limpiar_busquedas');jQuery('#accion').attr('value','limpiar');jQuery('#form').submit();}; jQuery('#bandaLimpiar').bind('click', bandaLimpiar)", "ready");

$iconos = $appForm->tag('p', $ocultar . $limpiar . $cerrar . $salir);
$banda_izquierda = $appForm->tag('div', $appForm->tag('p', $appForm->getCrumbs()), array('class'=>'banda_izquierda'));
$usuario = $session->read('__Usuario');
$usuario = $appForm->tag('span', $usuario['Usuario']['nombre_completo'] . $appForm->link($appForm->image('infos.gif'), array('controller' => 'infos', 'action' => 'index'), array('title' => 'Errores / Alertas / Informacion General')));
echo $appForm->tag('div', $banda_izquierda . $usuario . $iconos, array('class'=>'banda'));

?>