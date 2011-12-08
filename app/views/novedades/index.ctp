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
 * @version			$Revision: 1305 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-05-18 16:10:16 -0300 (mar 18 de may de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Relacion-trabajador_id'] = array(	'lov' => array('controller'		=>	'trabajadores',
																		'separadorRetorno'	=>	' ',
																		'camposRetorno'		=>array('Trabajador.apellido',
																									'Trabajador.nombre')));

$condiciones['Condicion.Relacion-empleador_id'] = array(	'lov' => array('controller'	=> 'empleadores',
																		'camposRetorno'	=> array('Empleador.nombre')));

$condiciones['Condicion.Relacion-id'] = array(	'label'	=> 'Relacion',
												'lov'	=> array(	'controller'	=> 'relaciones',
																	'camposRetorno'	=> array(	'Empleador.nombre',
																								'Trabajador.apellido')));
$condiciones['Condicion.Novedad-tipo'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Novedad-periodo'] = array('type' => 'periodo');
$condiciones['Condicion.Novedad-estado'] = array('type' => 'select', 'multiple' => 'checkbox');

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'novedades de la relacion laboral', 'imagen' => 'novedades.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('model' => 'Novedad', 'field' => 'id', 'valor' => $v['Novedad']['id'], 'write' => $v['Novedad']['write'], 'delete' => $v['Novedad']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'class' => 'derecha', 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');
	$fila[] = array('model' => 'Novedad', 'field' => 'estado', 'valor' => $v['Novedad']['estado']);
	$fila[] = array('model' => 'Novedad', 'field' => 'liquidacion_tipo', 'valor' => $v['Novedad']['liquidacion_tipo'], 'nombreEncabezado' => 'Tipo Liq.');
	$fila[] = array('model' => 'Novedad', 'field' => 'periodo', 'valor' => $v['Novedad']['periodo']);
	$fila[] = array('model' => 'Novedad', 'field' => 'tipo', 'valor' => $v['Novedad']['tipo']);
	$fila[] = array('model' => 'Novedad', 'field' => 'subtipo', 'valor' => $v['Novedad']['subtipo'], 'nombreEncabezado' => 'Detalle');
	$fila[] = array('model' => 'Novedad', 'field' => 'data', 'valor' => $v['Novedad']['data'], 'tipoDato' => 'integer', 'nombreEncabezado' => 'Valor');
	if ($v['Novedad']['tipo'] === 'Concepto' && $v['Novedad']['estado'] === 'Liquidada') {
		$cuerpo[] = array('contenido' => $fila, 'opciones' => array('seleccionMultiple' => false, 'eliminar' => false, 'modificar' => false));
	} elseif ($v['Novedad']['existe'] === true) {
		$cuerpo[] = array('contenido' => $fila, 'opciones' => array('title' => 'Existe una novedad del mismo tipo para el mismo periodo y para la misma relacion. Verifique.', 'class' => 'fila_resaltada'));
	} else {
		$cuerpo[] = $fila;
	}
}
$generar = $appForm->link('Generar Planilla', 'generar_planilla', array('title' => 'Genera las planillas para el ingreso de novedades', 'class' => 'link_boton'));
$importar = $appForm->link('Importar Planilla', 'importar_planilla', array('class' => 'link_boton', 'title' => 'Importa las planillas de novedades'));
$confirmar = $appForm->link('Confirmar', null, array('class' => 'link_boton', 'id' => 'confirmar', 'title' => 'Confirma las novedades seleccionadas'));
$accionesExtra['opciones'] = array('acciones' => array('nuevo', $confirmar, 'eliminar', $generar, $importar));
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'accionesExtra' => $accionesExtra));


if (!empty($paymentIds)) {
    $appForm->addScript('
        window.location = "' . Router::url(array('controller' => 'descuentos', 'action' => 'reporte_vales_confirmados', $paymentIds)) . '";
    ');
}


$js = "
	jQuery('#confirmar').bind('click', function() {
		jQuery('#form').attr('action', '" . Router::url("/") . $this->params['controller'] . "/confirmar');
		jQuery('#accion').attr('value', 'confirmar');
		jQuery('#form').submit();
	});
";
$appForm->addScript($js);
?>