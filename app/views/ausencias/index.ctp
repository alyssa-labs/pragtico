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
 * @version			$Revision: 892 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-08-30 21:20:59 -0300 (dom 30 de ago de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/

$condiciones['Condicion.Relacion-trabajador_id'] = array(
		'lov'=>array('controller'		=> 'trabajadores',
					'separadorRetorno'	=> ' ',
					'camposRetorno'		=> array(	'Trabajador.apellido',
													'Trabajador.nombre')));

$condiciones['Condicion.Relacion-empleador_id'] = array(
		'lov'=>array('controller'	=> 'empleadores',
					'camposRetorno'	=> array('Empleador.nombre')));

$condiciones['Condicion.Ausencia-relacion_id'] = array(
		'lov'=>array('controller'	=> 'relaciones',
					'camposRetorno'	=> array(	'Empleador.nombre',
											'	Trabajador.apellido')));
$condiciones['Condicion.Ausencia-ausencia_motivo_id'] = array(	'empty'			=> true,
																'options'		=> 'listable',
																'order'			=> 'AusenciasMotivo.motivo',
																'displayField'	=> 'AusenciasMotivo.motivo',
																'groupField'	=> 'AusenciasMotivo.tipo',
																'model'			=> 'AusenciasMotivo',
																'label'			=> 'Motivo');
                                                                
$condiciones['Condicion.AusenciasSeguimiento-estado'] = array('type' => 'select', 'multiple' => 'checkbox', 'aclaracion' => 'Estado de los seguimientos relacionados a la ausencia');
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'ausencias.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
    $fila[] = array('tipo' => 'desglose', 'id' => $v['Ausencia']['id'], 'imagen' => array('nombre' => 'trabajadores.gif', 'alt' => "Trabajadores"), 'url' => 'trabajadores');
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Ausencia']['id'], 'imagen' => array('nombre' => 'seguimientos.gif', 'alt' => 'Seguimiento'), 'url' => 'seguimientos');
	$fila[] = array('model' => 'Ausencia', 'field' => 'id', 'valor' => $v['Ausencia']['id'], 'write' => $v['Ausencia']['write'], 'delete' => $v['Ausencia']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'class'=>'derecha', 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');
	$fila[] = array('model' => 'AusenciasMotivo', 'field' => 'motivo', 'valor' => $v['AusenciasMotivo']['motivo']);
	$fila[] = array('model' => 'Ausencia', 'field' => 'desde', 'valor' => $v['Ausencia']['desde'], 'tipoDato' => 'date');
	$fila[] = array('model' => 'Ausencia', 'field' => 'dias', 'valor' => $v['Ausencia']['dias'], 'tipoDato' => 'decimal');
	$cuerpo[] = $fila;
}

$accionesExtra['opciones'] = array('acciones' => array('nuevo', 'modificar', 'eliminar',
	$appForm->link('Confirmar', null,
		array(	'class' => 'link_boton confirmar',
				'title' => 'Confirma las ausencias seleccionadas'))));
echo $this->element('index/index', array('accionesExtra' => $accionesExtra, 'condiciones' => $fieldset, 'cuerpo' => $cuerpo));

/**
* Agrego el evento click asociado al boton confirmar.
*/
$js = '
	jQuery(".confirmar").click(
		function() {
			var c = jQuery(".tabla input[type=\'checkbox\']").checkbox("contar");
			if (c>0) {
				jQuery("#form")[0].action = "' . Router::url('/') . $this->params['controller'] . '/confirmar' . '";
				jQuery("#form")[0].submit();
			} else {
				alert("Debe seleccionar al menos una asistencia para confirmar.");
			}
		}
	);
';

$appForm->addScript($js);
?>