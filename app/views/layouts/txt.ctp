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
 * @version			$Revision: 119 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2008-11-04 17:27:54 -0200 (mar 04 de nov de 2008) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 

if(empty($this->viewVars['archivo']['nombre'])) {
	$this->viewVars['archivo']['nombre'] = "archivo.txt";
}

define('PMA_USR_BROWSER_AGENT', 'Gecko');
$mime_type = (PMA_USR_BROWSER_AGENT == 'IE' || PMA_USR_BROWSER_AGENT == 'OPERA')
	? 'application/octetstream'
	: 'application/octet-stream';
header('Content-Type: ' . $mime_type);
header('Content-Disposition: inline; filename="' . $this->viewVars['archivo']['nombre'] . '"');

header('Expires: 0');
if (PMA_USR_BROWSER_AGENT == 'IE') {
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Pragma: public');
}
else {
	header('Pragma: no-cache');
}
	
	Configure::write('debug', 0);
	echo $content_for_layout;	
?>