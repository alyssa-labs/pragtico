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
 * @version			$Revision: 1299 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-05-16 00:21:24 -0300 (dom 16 de may de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

$mensaje[] = $appForm->tag('span', $appForm->link('Cerrar', null, array('class' => 'link_boton', 'title' => 'Cerrar')));
$mensaje[] = $appForm->image('error_icono_naranja.gif');
$mensaje[] = $appForm->tag('span', $message, array('class' => 'contenido'));

if (!empty($errores['errorDescripcion'])) {
	$erroresTmp[] = $appForm->tag('span', 'Detalles (el registro no se ha modificado)', array('class' => 'titulos'));
	$erroresTmp[] = $appForm->tag('span', $errores['errorDescripcion'], array('class' => 'detalle'));
	if (!empty($dbError['errorDescripcionAdicional'])) {
		$erroresTmp[] = $appForm->tag('span', $errores['errorDescripcionAdicional'], array('class' => 'detalle'));
	}
	echo $appForm->tag('div', $erroresTmp, array('class' => 'session_flash session_flash_error_detalle'));
}
echo $appForm->tag('div', $mensaje, array('class' => 'session_flash session_flash_error'));

$js = "
	jQuery('.session_flash img').attr('style', 'cursor:pointer');
	jQuery('.session_flash img').attr('alt', 'Ver Detalle');
	jQuery('.session_flash img').attr('title', 'Ver Detalle');
	jQuery('.session_flash img').bind('click', function() {
		jQuery('.session_flash_error_detalle').fadeIn('slow');
	});
	jQuery('.session_flash .link_boton').bind('click', vOcultar);
";

echo $appForm->codeBlock($js);
?>