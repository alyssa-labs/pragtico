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
 * @version			$Revision: 1155 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-11-24 11:32:08 -0300 (mar 24 de nov de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Relacion-trabajador_id'] = array(
    'lov'               => array('controller' => 'trabajadores',
    'separadorRetorno'	=> ' ',
    'camposRetorno'		=> array(
        'Trabajador.apellido',
        'Trabajador.nombre')));

$condiciones['Condicion.Relacion-empleador_id'] = array(	'lov'=>array('controller'	=> 'empleadores',
																		'camposRetorno'	=> array('Empleador.nombre')));

$condiciones['Condicion.Vacacion-relacion_id'] = array(	'lov'=>array('controller'	=>	'relaciones',
																		'camposRetorno'	=>array('Empleador.nombre',
																								'Trabajador.apellido')));

$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => 'Vacaciones', 'imagen' => 'vacaciones.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $v) {
	$fila = null;
	$fila[] = array('model' => 'Vacacion', 'field' => 'id', 'valor' => $v['Vacacion']['id'], 'write' => $v['Vacacion']['write'], 'delete' => $v['Vacacion']['delete']);
    $fila[] = array('tipo' => 'desglose', 'id' => $v['Vacacion']['id'], 'imagen' => array('nombre' => 'detalles.gif', 'alt' => 'Detalles'), 'url' => 'detalles');
    //$fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Documento')), array('controller' => 'documentos', 'action' => 'generar', 'model' => 'Vacacion', 'id' => $v['Vacacion']['id'])));
    $fila[] = array('tipo'=>'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Documento')), array('action' => 'notificaciones', 'id' => $v['Vacacion']['id'])));
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado'=>'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'class'=>'derecha', 'nombreEncabezado'=>'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado'=>'Trabajador');
    $fila[] = array('model' => 'Vacacion', 'field' => 'periodo', 'valor' => $v['Vacacion']['periodo'], 'class' => 'centro');
    $fila[] = array('model' => 'Vacacion', 'field' => 'corresponde', 'valor' => $v['Vacacion']['corresponde']);
    $fila[] = array('model' => 'Vacacion', 'field' => 'dias', 'valor' => $v['Vacacion']['dias'], 'class' => 'derecha');

    if ($v['Vacacion']['dias'] > $v['Vacacion']['corresponde']) {
        $cuerpo[] = array('contenido' => $fila, 'opciones' =>
        array('title'   => 'El trabajador ha obtenido mas dias de vacaciones de los que le corresponden',
                'class' => 'fila_resaltada'));
    } elseif ($v['Vacacion']['dias'] < $v['Vacacion']['corresponde']) {
        $cuerpo[] = array('contenido' => $fila, 'opciones' =>
        array('title'   => 'El trabajador tiene dias de vacaciones pendientes',
                'class' => 'fila_resaltada'));
    } else {
        $cuerpo[] = $fila;
    }
}

$actions[] = $appForm->link('Generar Dias', 'generar_dias', array('title' => 'Genera los Dias en funcion del Periodo', 'class' => 'link_boton'));
$actions[] = $appForm->link('Notificaciones', null, array('title' => 'Imprime las notificaciones de vacaciones', 'class' => 'link_boton', 'id' => 'notificaciones'));
$accionesExtra['opciones'] = array('acciones' => $actions);

echo $this->element('index/index', array('accionesExtra' => $accionesExtra, 'condiciones' => $fieldset, 'cuerpo' => $cuerpo));

/**
* Agrego el evento click asociado al detalle de cambio.
*/
$js = '
    jQuery("#notificaciones").click(
        function () {
            var c = jQuery(".tabla :checkbox").checkbox("contar");
            if (c == 0) {
                alert("Debe seleccionar al menos una vacacion a notificar.");
                return false;
            }
            jQuery("#form")[0].action = jQuery.url("' . $this->params['controller'] . '/notificaciones");
            jQuery("#form")[0].submit();
        }
    );
';
$appForm->addScript($js);
?>