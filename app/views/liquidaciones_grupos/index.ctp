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
 * @version			$Revision: 1032 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-29 19:51:14 -0300 (Tue, 29 Sep 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.LiquidacionesGrupo-id'] = array();
$condiciones['Condicion.LiquidacionesGrupo-fecha'] = array();
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'archivo.gif', 'legend' => 'Grupo de LIquidaciones')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $v) {
	$fila = null;
	$fila[] = array(
		'tipo' 		=> 'desglose',
		'id' 		=> $v['LiquidacionesGrupo']['id'],
		'imagen' 	=> array(
			'nombre' => 'liquidaciones.gif',
			'alt' 		=> 'liquidaciones'
		),
		'url' => 'liquidaciones'
	);
	$fila[] = array(
		'tipo'		=> 'accion',
		'valor' => $appForm->link(
			$appForm->image('print.gif',
				array(	'alt' 		=> 'Pre-imprimir reporte de Liquidaciones Confirmadas',
						'title' 	=> 'Pre-imprimir reporte de Liquidaciones Confirmadas'
				)
			),
			array(
				'action' 	=> 'reprint',
				'id' 		=> $v['LiquidacionesGrupo']['id']
			)
		)
	);
	$fila[] = array(
		'model' 	=> 'LiquidacionesGrupo',
		'field' 	=> 'id',
		'valor' 	=> $v['LiquidacionesGrupo']['id'],
		'write' 	=> $v['LiquidacionesGrupo']['write'],
		'delete' 	=> $v['LiquidacionesGrupo']['delete']
	);
	$fila[] = array(
		'model' => 'LiquidacionesGrupo',
		'field' => 'fecha',
		'valor' => $v['LiquidacionesGrupo']['fecha']
	);
	$fila[] = array(
		'model' => 'LiquidacionesGrupo',
		'field' => 'observacion',
		'valor' => $v['LiquidacionesGrupo']['observacion']
	);
	$cuerpo[] = $fila;
}

$opcionesTabla['tabla']['mostrarIds'] = true;
$opcionesTabla['tabla']['modificar'] = false;
$opcionesTabla['tabla']['seleccionMultiple'] = false;
$opcionesTabla['tabla']['eliminar'] = false;
$opcionesTabla['tabla']['permisos'] = false;
echo $this->element('index/index',
	array(
		'condiciones' 		=> $fieldset,
		'opcionesTabla'		=> $opcionesTabla,
		'cuerpo' 			=> $cuerpo,
		'accionesExtra' 	=> false
	)
);

?>