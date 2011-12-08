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
 * @version			$Revision: 1294 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-05-05 12:40:26 -0300 (mié 05 de may de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */

/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Relacion-empleador_id'] = array(	'lov'=>array('controller'	=> 'empleadores',
                                                                        'mask'          =>  '%s, %s',
																		'camposRetorno'	=> array('Empleador.cuit', 'Empleador.nombre')));

$condiciones['Condicion.Relacion-trabajador_id'] = array(	'lov'=>array('controller'	=>	'trabajadores',
																		'mask'	        =>	'%s, %s',
																		'camposRetorno'		=>array('Trabajador.apellido',
																									'Trabajador.nombre')));
$condiciones['Condicion.Trabajador-apellido'] = array('label' => 'Apellido');
$condiciones['Condicion.Trabajador-nombre'] = array();
$condiciones['Condicion.Trabajador-numero_documento'] = array('label' => 'Documento');
$condiciones['Condicion.Relacion-area_id'] = array(
        'label' => 'Area',
        'mask'  =>  '%s, %s',
        'lov'   => array('controller'   => 'areas',
                        'camposRetorno' => array(   'Empleador.nombre',
                                                    'Area.nombre')));
$condiciones['Condicion.Relacion-estado'] = array('type' => 'select', 'multiple' => 'checkbox');
$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('imagen' => 'relaciones.gif')));

/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$id = $v['Relacion']['id'];
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'ausencias.gif', 'alt' => 'Ausencias'), 'url' => 'ausencias');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'conceptos.gif', 'alt' => 'Conceptos'), 'url' => 'conceptos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'ropas.gif', 'alt' => 'Ropa'), 'url' => 'ropas');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'horas.gif', 'alt' => 'Horas'), 'url' => 'horas');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'descuentos.gif', 'alt' => 'Descuentos y Anticipos'), 'url' => 'descuentos');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'vacaciones.gif', 'alt' => 'Vacaciones'), 'url' => 'vacaciones');
	$fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'pagos.gif', 'alt' => 'Pagos'), 'url' => 'pagos');
    $fila[] = array('tipo' => 'desglose', 'id' => $id, 'imagen' => array('nombre' => 'historicos.gif', 'alt' => 'Historical'), 'url' => 'historial');
	//$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('acciones/bloquear.gif', array('alt' => 'Pasar a historico', 'title' => 'Pasar a historico')), 'pasarAHistorico/' . $id, array(), 'Desea pasar la relacion laboral al historico?'));
	//$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Documento')), array('controller' => 'documentos', 'action' => 'generar', 'model' => 'Relacion', 'id'' . $id));
	$fila[] = array('tipo' => 'accion', 'valor' => $appForm->link($appForm->image('documentos.gif', array('alt' => 'Generar Documento')), array('controller' => 'documentos', 'action' => 'generar', 'model' => 'Relacion', 'id' => $id)));
	$fila[] = array('model' => 'Relacion', 'field' => 'id', 'valor' => $v['Relacion']['id'], 'write' => $v['Relacion']['write'], 'delete' => $v['Relacion']['delete']);
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Trabajador']['numero_documento'], 'class' => 'derecha', 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Trabajador']['apellido'] . ' ' . $v['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');
	$fila[] = array('model' => 'Relacion', 'field' => 'ingreso', 'valor' => $v['Relacion']['ingreso']);
	$fila[] = array('model' => 'Relacion', 'field' => 'horas', 'valor' => $v['Relacion']['horas']);
	$fila[] = array('model' => 'Relacion', 'field' => 'basico', 'valor'=> $v['Relacion']['basico'], 'tipoDato' => 'moneda');
	$fila[] = array('model' => 'Relacion', 'field' => 'estado', 'valor' => $v['Relacion']['estado']);
	$cuerpo[] = $fila;
}

$opcionesTabla =  array('tabla' => array('eliminar' => false));
echo $this->element('index/index', array('condiciones' => $fieldset, 'cuerpo' => $cuerpo, 'opcionesTabla'=>$opcionesTabla));
?>