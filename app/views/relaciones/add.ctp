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
 * @version			$Revision: 1345 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-04 16:17:50 -0300 (vie 04 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/

/**
* Datos del trabajador.
*/
$campos = null;
$campos['Relacion.trabajador_id'] = array(
	'lov' 					=> array(
		'controller'		=> 	'trabajadores',
		'seleccionMultiple'	=> 	0,
		'camposRetorno'		=> 	array(
			'Trabajador.cuil',
			'Trabajador.nombre',
			'Trabajador.apellido')));

$campos['Relacion.convenios_categoria_id'] = array(
	'label'					=>	'Categoria',
	'lov'					=>	array(
		'controller'		=> 	'convenios_categorias',
		'seleccionMultiple'	=> 	0,
		'camposRetorno'		=> 	array(	'Convenio.nombre', 'ConveniosCategoria.nombre')));

$campos['Relacion.ingreso'] = array();
$campos['Relacion.horas'] = array('label' => 'Horas de Trabajo');
$campos['Relacion.basico'] = array(
	'label' 				=> 'Basico $',
	'aclaracion' 			=> 'Si lo deja en cero, se utilizara el basico de convenio.');
$campos['Relacion.estado'] = array('type' => 'soloLectura');
$campos['Relacion.antiguedad_reconocida'] = array();
$fieldsets[] = array(
	'campos' 	=> $campos,
	'opciones' 	=> array(
		'div' 	=> array(
			'class' => 'subset'),
			'fieldset' => array('legend' => 'Trabajador', 'imagen' => 'trabajadores.gif')));


/**
* Datos del empleador.
*/
$campos = null;
$campos['Relacion.id'] = array();
$campos['Relacion.empleador_id'] = array(
	'lov'					=> array(
		'controller'		=> 	'empleadores',
		'seleccionMultiple'	=> 	0,
		'camposRetorno'		=> 	array(
			'Empleador.cuit', 'Empleador.nombre')));
$campos['Relacion.area_id'] = array(
	'type' 					=> 'relacionado',
	'verificarRequerido' 	=> 'forzado',
	'valor'					=> 'Area.nombre',
	'relacion'				=> 'Relacion.empleador_id',
	'url'					=> 'relaciones/areas_relacionado');
$campos['Relacion.legajo'] = array(
	'aclaracion' 			=> 'Si lo deja en blanco, se utilizara el numero de documento del Trabajador.');

$campos['Relacion.recibo_id'] = array(
	'type' 					=> 'relacionado',
	'verificarRequerido' 	=> 'forzado',
	'valor' 				=> 'Recibo.nombre',
	'relacion' 				=> 'Relacion.empleador_id',
	'url' 					=> 'relaciones/recibos_relacionado');
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Empleador', 'imagen' => 'empleadores.gif')));


/**
* Datos de la afip.
*/
$campos = null;
$campos['Relacion.situacion_id'] = array(   'options'       => 'listable',
                                            'order'         => 'Situacion.nombre',
                                            'displayField'  => 'Situacion.nombre',
                                            'model'         => 'Situacion',
                                            'aclaracion'    => 'Se refiere a la situacion que se informara (SIAP).');

$campos['Relacion.actividad_id'] = array(	'aclaracion' => 'Se refiere a la actividad (SIAP).',
											'lov'		=>	array(	'controller'		=> 	'actividades',
																	'seleccionMultiple'	=> 	0,
																		'camposRetorno'	=> 	array(	'Actividad.codigo',
																									'Actividad.nombre')));
$campos['Relacion.modalidad_id'] = array(	'lov'	=>	array(	'controller'		=> 	'modalidades',
																'seleccionMultiple'	=> 	0,
																	'camposRetorno'	=> 	array(	'Modalidad.codigo',
																								'Modalidad.nombre')));
$campos['Relacion.tarea_diferencial'] = array('aclaracion' => 'Segun ley 22.250 (Trabajadores de la construccion)');
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Afip', 'imagen' => 'afip.gif')));

$campos = null;
$campos['Relacion.observacion'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Observaciones', 'imagen' => 'historicos.gif')));

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => 'Relacion Laboral', 'imagen' => 'relaciones.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$fieldset = $appForm->pintarFieldsets($fieldsets, array(
	'div' 		=> array('class' => 'unica'),
	'fieldset' 	=> array('imagen' => 'trabajadores.gif')));
echo $this->element('add/add', array('fieldset' => $fieldset));


?>