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
 * @version			$Revision: 935 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-11 10:12:24 -0300 (Fri, 11 Sep 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Familiar.id'] = array();
$campos['Familiar.trabajador_id'] = array(  'lov'=>array('controller'       =>  'trabajadores',
                                                        'seleccionMultiple' =>  0,
                                                            'camposRetorno' =>  array(  'Trabajador.cuil',
                                                                                        'Trabajador.nombre',
                                                                                        'Trabajador.apellido')));
$campos['Familiar.parentezco'] = array();
$campos['Familiar.nombre'] = array();
$campos['Familiar.apellido'] = array();
$campos['Familiar.tipo_documento'] = array();
$campos['Familiar.numero_documento'] = array();
$campos['Familiar.sexo'] = array();
$campos['Familiar.nacimiento'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => "Identificacion", 'imagen' => 'identificacion.gif')));

$campos = null;
$campos['Familiar.direccion'] = array();
$campos['Familiar.numero'] = array();
$campos['Familiar.codigo_postal'] = array();
$campos['Familiar.barrio'] = array();
$campos['Familiar.ciudad'] = array();
$campos['Familiar.pais'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Ubicacion', 'imagen' => 'ubicacion.gif')));

$campos = null;
$campos['Familiar.telefono'] = array();
$campos['Familiar.celular'] = array();
$campos['Familiar.email'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => "Contacto", 'imagen' => 'contacto.gif')));

$campos = null;
$campos['Familiar.observacion'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => "Observaciones", 'imagen' => 'observaciones.gif')));



/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'familiares.gif')));
echo $this->element('add/add', array('fieldset' => $fieldset));

?>