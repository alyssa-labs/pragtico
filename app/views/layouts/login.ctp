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
 
$codigo_html[] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
$codigo_html[] = '<html xmlns="http://www.w3.org/1999/xhtml">';
$codigo_html[] = "\t" . '<head>';
$codigo_html[] = "\t\t" . '<title>Pragtico - Sistema para la Liquidación de Sueldos y Jornales libre y gratuito</title>';
$codigo_html[] = "\t\t" . '<meta http-equiv="content-type" content="text/html; charset=UTF-8">';
$codigo_html[] = "\t\t" . '<link rel="icon" href="' . $this->webroot . 'favicon.ico" type="image/x-icon"/>';
$codigo_html[] = "\t\t" . '<link rel="shortcut icon" href="' . $this->webroot . 'favicon.ico" type="image/x-icon"/>';


$codigo_html[] = "\t\t" . $html->css('aplicacion.default.screen.min', 'stylesheet', array('media' => 'all'));
$codigo_html[] = "\t\t" . $javascript->link('jquery/jquery-1.3.2.min');

$codigo_html[] = "\n\t\t" . '<script type="text/javascript">
			jQuery(document).ready(function($) {


				/** Set focus on element */
				if (jQuery("#UsuarioLoginGroup").length > 0) {
					jQuery("#UsuarioLoginGroup").focus();
				} else {
					jQuery("#UsuarioLoginNombre").focus();
				}

				/** Binds enter key to sumbit form */
				jQuery("#UsuarioLoginNombre, #UsuarioLoginClave, #UsuarioLoginGroup").keypress(function (e) {
					if (e.which == 13) {
						jQuery("#form").submit();
					}
				});
			});
		</script>';

$codigo_html[] = "\t" . '</head>';
$codigo_html[] = '';

$codigo_html[] = "\t" . '<body>';
$codigo_html[] = $session->flash();
$links = null;
$links[] = "\t\t" . $appForm->link('Acerca de Pragtico', 'http://www.pragtico.com.ar/wiki', array('tabindex' => '50'));
$links[] = "\t\t" . $appForm->link('Manual', 'http://www.pragtico.com.ar/wiki/index.php/Manual_de_Usuario', array('tabindex' => '51'));
$links[] = "\t\t" . $appForm->link('Preguntas Frecuentes', 'http://www.pragtico.com.ar/wiki/index.php/FAQ', array('tabindex' => '52'));
$links[] = "\t\t" . $appForm->link('Contáctenos', 'http://www.pragtico.com.ar/wiki/index.php/Especial:Contactar', array('tabindex' => '53'));


$codigo_html[] = "\t\t" . '<div class="login">';
$codigo_html[] = "\n\t\t\t" . '<div class="tabs">';
$codigo_html[] = "\t\t" . implode("\n\t\t", $links);
$codigo_html[] = "\t\t\t" . '</div>';
$codigo_html[] = "\n\t\t\t" . $content_for_layout;
$codigo_html[] = "\n\t\t\t" . '</div>';



$links = null;
$codigo_html[] = "\n\t\t\t" . '<div class="links_externos">';
$links[] = $appForm->link($appForm->image('logo_pragmatia.jpg', array('alt' => 'Pragmatia')), 'http://www.pragmatia.com');
$links[] = $appForm->link($appForm->image('cake.power.gif', array('alt' => 'CakePhp')), 'http://www.cakephp.org');
$links[] = $appForm->link($appForm->image('firefox.png', array('alt' => 'Descargar Firefox 3.5')), 'http://www.spreadfirefox.com/node&id=0&t=308');
$codigo_html[] = "\n\t\t\t\t" . implode("\n\t\t\t\t", $links);
$codigo_html[] = "\n\t\t\t" . '</div>';
$codigo_html[] = $this->element('sql_dump');
$codigo_html[] = "\t" . '</body>';
$codigo_html[] = '</html>';

echo implode("\n", $codigo_html);
?>