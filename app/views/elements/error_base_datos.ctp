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
 * @version			$Revision: 24 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-10-17 15:49:35 -0300 (vie 17 de oct de 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
	//d("X");
	//d($dbError);
	$return = "";
	if(!empty($dbError['errorDescripcion'])) {
		$return .= "<h1><span class='color_rojo'>" . $dbError['errorDescripcion'] . "</span></h1>";
	}
	if(!empty($dbError['errorDescripcionAdicional'])) {
		$return .= "<h2>" . $dbError['errorDescripcionAdicional'] . "</h2>";
	}
	echo $return;
?>