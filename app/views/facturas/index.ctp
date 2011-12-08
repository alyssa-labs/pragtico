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
 * @version			$Revision: 477 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-04-07 18:08:38 -0300 (Tue, 07 Apr 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Factura-empleador_id'] = array('lov' => array(
		'controller'	=> 'empleadores',
		'camposRetorno'	=> array('Empleador.cuit', 'Empleador.nombre')));
$condiciones['Condicion.Factura-fecha__desde'] = array('label' => 'Desde', 'type' => 'date');
$condiciones['Condicion.Factura-fecha__hasta'] = array('label' => 'Hasta', 'type' => 'date');
$condiciones['Condicion.Bar-periodo_largo'] = array('label' => 'Periodo Liquidado', 'type' => 'periodo');

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Facturas', 'imagen' => 'facturas.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'accion', 'id' => $v['Factura']['id'],
					'valor' => $appForm->link(
						$appForm->image('resumen.gif', array('alt' => 'Reporte Facturacion', 'title' => 'Reporte  Facturacion')),
						array('action' => 'reporte_facturacion', $v['Factura']['id'])));
	$fila[] = array('tipo' => 'accion',
					'valor' => $appForm->image('numero.gif', array('id' => $v['Factura']['id'], 'class' => 'asignar_numero', 'alt' => 'Asignar Numero', 'title' => 'Asignar Numero')));
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Factura']['id'], 'update' => 'desglose1', 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Detalles'), 'url' => 'detalles');
	$fila[] = array('model' => 'Factura', 'field' => 'id', 'valor' => $v['Factura']['id'], 'write' => $v['Factura']['write'], 'delete' => $v['Factura']['delete']);
	$fila[] = array('model' => 'Factura', 'field' => 'numero', 'valor' => $v['Factura']['numero'], 'class' => 'derecha');
	$fila[] = array('model' => 'Factura', 'field' => 'fecha', 'valor' => $v['Factura']['fecha']);
	$fila[] = array('model' => 'Factura', 'field' => 'tipo', 'valor' => $v['Factura']['tipo']);
	$fila[] = array('model' => 'Factura', 'field' => 'ano', 'valor' => $v['Factura']['ano'] . str_pad($v['Factura']['mes'], 2, '0' ,STR_PAD_LEFT) . $v['Factura']['periodo'], 'nombreEncabezado'=>'Periodo');
	$fila[] = array('model' => 'Factura', 'field' => 'estado', 'valor' => $v['Factura']['estado']);
	$fila[] = array('model' => 'Empleador', 'field' => 'cuit', 'valor' => $v['Empleador']['cuit'], 'class' => 'centro');
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Area', 'field' => 'nombre', 'valor' => $v['Area']['nombre'], 'nombreEncabezado' => 'Area');
	$fila[] = array('model' => 'Factura', 'field' => 'total', 'valor' => $v['Factura']['total'], 'tipoDato' => 'moneda');
	$cuerpo[] = $fila;
}
$accionesExtra['opciones'] = array('acciones' => array($appForm->link('Imprimir', null, array('class' => 'link_boton', 'id' => 'imprimir', 'title' => 'Imprime las preliquidaciones seleccionadas'))));

echo $this->element('index/index', array(
		'accionesExtra' => $accionesExtra,
  		'opcionesTabla' => array('tabla' => array('eliminar' => false, 'modificar' => false)), 
  		'condiciones' 	=> $fieldset, 'cuerpo' => $cuerpo));

/**
* Agrego el evento click asociado al boton confirmar.
*/
$appForm->addScript('

	jQuery(".asignar_numero").css("cursor", "pointer").click(
		function() {
			var number = prompt("Ingrese el numero de la factura");
			if (number != "") {
				jQuery.get("' . Router::url(array('controller' => 'facturas', 'action' => 'asignar_numero')) . '/" + jQuery(this).attr("id") + "/" + number, function(data) {
					if (data == "ok") {
						alert("El numero se asigno correctamente.");
					} else {
						alert("No fue posible asignar el numero a la factura.");
					}
				});
			}
		}
	);

	jQuery("#imprimir").click(
		function() {
			var c = jQuery(".tabla :checkbox").checkbox("contar");
			if (c > 0) {
				jQuery("#form")[0].action = "' . Router::url(array('controller' => $this->params['controller'], 'action' => 'imprimir')) . '";
				jQuery("#form")[0].submit();
			} else {
				alert("Debe seleccionar al menos una factura para imprimir.");
			}
		}
	);', 'ready');


?>