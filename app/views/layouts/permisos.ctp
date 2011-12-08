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
 * @version			$Revision: 229 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-01-18 22:32:17 -0200 (dom 18 de ene de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

$mensaje[] = $appForm->tag("span", $appForm->link("Cerrar", null, array("class"=>"link_boton", "title"=>"Cerrar")));
$mensaje[] = $appForm->image('permisos.gif');
$mensaje[] = $appForm->tag("span", "Usted no tiene permisos suficientes para realizar esta operacion.", array("class"=>"contenido"));
$erroresTmp[] = $appForm->tag("span", "Detalles (el registro no se ha modificado)", array("class"=>"titulos"));
echo $appForm->tag("div", $mensaje, array("class"=>"session_flash session_flash_error"));

$js = "
	jQuery('.session_flash .link_boton').bind('click', vOcultar);
";

$appForm->addScript($js);

?>