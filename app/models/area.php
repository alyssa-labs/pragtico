<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las areas de los empleadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1423 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las areas de los empleadores.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Area extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	var $opciones = array('pago' => array(	'1' => 'Ultimo Dia Habil del Periodo',
											'2' => 'Primer Dia Habil',
											'3' => 'Segundo Dia Habil',
											'4' => 'Tercer Dia Habil',
											'5' => 'Cuarto Dia Habil',
											'6' => 'Quinto Dia Habil'));


	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del area del empleador.')
        ),
        'zone_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la localidad.')
        ),
        'empleador_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el empleador.')
        )
	);

    var $breadCrumb = array('format'    => '%s para %s',
                            'fields'    => array('Area.nombre', 'Empleador.nombre'));

	var $hasAndBelongsToMany = array('Coeficiente');
	var $belongsTo = array('Empleador', 'Zone', 'Provincia');
	var $hasMany = array('Relacion', 'Liquidacion' => array('foreignKey' => 'relacion_area_id'));

}
?>