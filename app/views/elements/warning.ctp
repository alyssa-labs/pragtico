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
 * @version			$Revision: 1340 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-01 23:12:26 -0300 (mar 01 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
$mensaje[0] = $appForm->tag('span', $appForm->link('Cerrar', null, array('class'=>'link_boton', 'title'=>'Cerrar')));
$mensaje[1] = $appForm->image('ok_icono_amarillo.gif');
$mensaje[2] = $appForm->tag('span', $message, array('class'=>'contenido'));

echo $appForm->tag('div', $mensaje ,array('class'=>'session_flash session_flash_warning'));


/**
* Si no hay warning, hago que se desaparezca solo el cartel ed aviso, sino, debe hacerlo el usuario para
* asegurarse de que leyo el mensaje de warning.
*/
if(empty($warnings)) {
	$js = "setTimeout(vOcultar, 6000);";
}
else {
	$js = "
		jQuery('.session_flash img').attr('style', 'cursor:pointer');
		jQuery('.session_flash img').attr('alt', 'Ver Detalle');
		jQuery('.session_flash img').attr('title', 'Ver Detalle');
		jQuery('.session_flash img').bind('click', function() {
			jQuery('.session_flash_warning_detalle').fadeIn('slow');
		});
	";
}
$js .= "jQuery('.session_flash .link_boton').bind('click', vOcultar);";

echo $appForm->codeBlock($js);
?>