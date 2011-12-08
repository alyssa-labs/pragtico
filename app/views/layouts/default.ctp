<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.views
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1423 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

$barra = $this->element('layout' . DS . 'barra');

$codigo_html[] = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
$codigo_html[] = "<html xmlns=\"http://www.w3.org/1999/xhtml\">";
$codigo_html[] = "<head>";
$codigo_html[] = "\t<title>" . $title_for_layout . "</title>";
$codigo_html[] = "\t" . $html->charset('UTF-8');
$codigo_html[] = "\t<link rel='icon' href='" . $this->webroot . "favicon.ico' type='image/x-icon'/>";
$codigo_html[] = "\t<link rel='shortcut icon' href='" . $this->webroot . "favicon.ico' type='image/x-icon'/>";


/*
$codigo_html .= '\n
        <!-- Additional IE/Win specific style sheet (Conditional Comments) -->
        <!--[if lte IE 7]>
        <link rel="stylesheet" href="jquery.tabs-ie.css" type="text/css" media="screen">
        <![endif]-->';
*/
//$codigo_html .= $html->css(array( "aplicacion.default.screen", "jquery.jqmodal", "liquidcorners/liquidcorners"),
//$html->css(array( "aplicacion.default.screen",
//                  "jquery.jqmodal"), null, array("media"=>"screen"), false);

/**
$css[] = "aplicacion.default.screen";
$css[] = "jquery.autocomplete";
*/
$css = null;
$css[] = 'aplicacion.default.screen.min';
$css[] = 'jscal/jscal2.min';
$css[] = 'jscal/border-radius.min';
$codigo_html[] = $html->css($css, 'stylesheet', array('media' => 'all'));

$js = null;
$js[] = 'jquery/jquery-1.3.2.min';
$js[] = 'jquery/jquery.cookie.min';
$js[] = 'jquery/jquery.accordion.min';
$js[] = 'jquery/jquery.checkbox.min';
$js[] = 'jquery/jquery.simplemodal.min';
$js[] = 'jquery/jquery.form.min';
$js[] = 'jquery/jquery.sprintf.min';
$js[] = 'default.min';
$js[] = 'master_detail';
$js[] = 'jscal/jscal2.min';
$js[] = 'jscal/es.min';
$js[] = 'jquery.flydom.min'; // lo usa en carga rapida de conceptos desde convenios
$codigo_html[] = $html->script($js);
$codigo_html[] = $scripts_for_layout;

$View = ClassRegistry::getObject('view');
if (!empty($View->__jsCodeForReady)) {
    $codigo_html[] = $javascript->codeBlock(sprintf('jQuery(document).ready(function($) {%s});', implode("\n", $View->__jsCodeForReady)));
}
$codigo_html[] = '</head>';

$codigo_html[] = '<body>';
$codigo_html[] = '<input id="base_url" type="hidden" value="' . Router::url('/') . '" />';
$codigo_html[] = '<div id="lov" class="index"></div>';
/** When opening a Lov Control, all necessary options are temporaly saved in this hidden text field */
$codigo_html[] = '<input id="opened_lov_options" type="hidden" />';

if (class_exists('User')) {
	$menu = $this->element('layout' . DS . 'menu', array('key' => implode('.', User::get('/Rol/nombre', array('flatten' => false))), 'cache' => '+1 day'));
} else {
	$menu = 'Can\'t load Menu';
}

$codigo_html[] = $session->flash();
$codigo_html[] = $this->element('layout' . DS . 'encabezado');
$codigo_html[] = $barra;
$contenido = $appForm->tag('div', $content_for_layout, array('class' => 'cuerpo'));
$codigo_html[] = $appForm->tag('div', $menu . $contenido, array('class' => 'contenido'));
$codigo_html[] = $this->element('sql_dump');
$codigo_html[] = '</body>';
$codigo_html[] = '</html>';

echo implode('', $codigo_html);
?>