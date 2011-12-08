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
 * @version			$Revision: 1267 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-04-08 11:49:57 -0300 (Thu, 08 Apr 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Si hay algo que mostrar en la session, lo obtengo para mostralo luego.
*/

$codigo_html[] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$codigo_html[] = '<html xmlns="http://www.w3.org/1999/xhtml">';
$codigo_html[] = "\t" . '<head>';
$codigo_html[] = "\t\t" . '<title>Pragtico - Sistema para la Liquidación de Sueldos y Haberes Open Source</title>';
$codigo_html[] = "\t\t" . '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
$codigo_html[] = "\t\t" . '<meta name="Keywords" content="Open Source, Free, Gratis, Libre, Liquidacion de Sueldos, Liquidacion de Haberes, Sueldos, Recibos, Recursos Humanos, Software" />';
$codigo_html[] = "\t\t" . '<meta name="description" content="Primer y unico software de liquidacion de sueldos gratuito y con licencia libre. Software de liquidacion de haberes multi-idioma y multi-empresa especialmente diseñado para estudios contables y empresas dedicadas a la contratacion de personal eventual">';

$codigo_html[] = "\t\t" . '<link rel="icon" href="' . $this->webroot . 'favicon.ico" type="image/x-icon"/>';
$codigo_html[] = "\t\t" . '<link rel="shortcut icon" href="' . $this->webroot . 'favicon.ico" type="image/x-icon"/>';


$codigo_html[] = "\t\t" . $html->css('aplicacion.install.screen.min', 'stylesheet', array('media' => 'all'));
$codigo_html[] = "\t\t" . $javascript->link('jquery/jquery-1.3.2.min');
$codigo_html[] = "\t" . '</head>';
$codigo_html[] = '';

$codigo_html[] = "\t" . '<body>';
$links = null;
$links[] = "\t\t" . $appForm->link('Acerca de Pragtico', 'http://www.pragtico.com.ar/wiki', array('tabindex' => '50'));
$links[] = "\t\t" . $appForm->link('Manual', 'http://www.pragtico.com.ar/wiki/index.php/Manual_de_Usuario', array('tabindex' => '51'));
$links[] = "\t\t" . $appForm->link('Preguntas Frecuentes', 'http://www.pragtico.com.ar/wiki/index.php/FAQ', array('tabindex' => '52'));
$links[] = "\t\t" . $appForm->link('Contáctenos', 'http://www.pragtico.com.ar/wiki/index.php/Especial:Contactar', array('tabindex' => '53'));

$codigo_html[] = "\t\t" . '<div class="install">';
$codigo_html[] = "\n\t\t\t" . '<div class="tabs">';
$codigo_html[] = "\t\t" . implode("\n\t\t", $links);
$codigo_html[] = "\t\t\t" . '</div>';
if (empty($flash)) {
	$codigo_html[] = '<div class="message">&nbsp;</div>';
} else {
	$codigo_html[] = $flash;
}
$codigo_html[] = "\n\t\t\t" . $content_for_layout;
$codigo_html[] = "\n\t\t\t" . '</div>';



$links = null;
$codigo_html[] = $this->element('sql_dump');
$codigo_html[] = "\t" . '</body>';
$codigo_html[] = '</html>';

echo implode("\n", $codigo_html);
?>