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
 * @version         $Revision: 1125 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-11-10 16:45:04 -0300 (Tue, 10 Nov 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
$appForm->addCrumb($this->name,
        array('controller'  => $this->params['controller'],
              'action'      => 'index'));
$appForm->addCrumb(__('Detail', true));

$o[] = $appForm->tag('h1', 'Errores / Alertas / Informacion General');


$o[] = $appForm->tag('h2', 'Montos Liquidados sin Facturar');
foreach ($invoiceErrors as $error) {

	$o[] = $html->link($error['Liquidacion']['empleador_cuit'] . ' ' . $error['Liquidacion']['empleador_nombre'] . ' $' . $error['Liquidacion']['total'], array('controller' => 'facturas', 'action' => 'prefacturar')) . '<br/>';
}



$o[] = $appForm->tag('h2', 'Errores en Relaciones Activas');
foreach ($relationErrors as $error) {

	if (empty($error['Trabajador']['obra_social_id'])) {
		$o[] = $html->link($error['Empleador']['cuit'] . ' ' . $error['Empleador']['nombre'] . ' - ' .  $error['Trabajador']['cuil'] . ' ' . $error['Trabajador']['apellido'] . ' ' . $error['Trabajador']['nombre'] . ' Sin Obra Social definida', array('controller' => 'trabajadores', 'action' => 'edit', $error['Trabajador']['id'])) . '<br/>';
	}

	if (empty($error['Trabajador']['localidad_id'])) {
		$o[] = $html->link($error['Empleador']['cuit'] . ' ' . $error['Empleador']['nombre'] . ' - ' . $error['Trabajador']['cuil'] . ' ' . $error['Trabajador']['apellido'] . ' ' . $error['Trabajador']['nombre'] . ' Sin Localidad definida', array('controller' => 'trabajadores', 'action' => 'edit', $error['Trabajador']['id'])) . '<br/>';
	}
}

$o[] = '<br/><br/><br/>';
$out = $appForm->tag('div', $o, array('class' => 'unica'));
echo $appForm->tag('div', $out, array('class' => 'index'));

?>