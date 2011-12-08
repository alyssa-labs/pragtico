<?php
/**
 * Mantengo tags personalizados.
 *
 * En este archivo agrego los tags html personalizados.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.config
 * @since			Pragtico v 1.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */

$tags = array(
	"radio" => "<p class='radio'><input type='radio' name='%s' id='%s' %s /><label for='%2\$s' class='radio_label'>%s</label></p>",
	"dt" => "<dt %s />%s</dt>",
	"dd" => "<dd/>%s</dd>",
	"dl" => "<dl/>%s</dl>",
	"legend" => "<legend><span>%s</span></legend>",
	"table" => "<table>%s</table>",
	"tbody" => "<tbody>%s</tbody>",
	"thead" => "<thead>%s</thead>",
	"tfoot" => "<tfoot>%s</tfoot>"
);

?>