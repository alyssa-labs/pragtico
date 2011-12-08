<?php
/**
 * Este archivo contiene los datos de un fixture para los casos de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.fixtures
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase para un fixture para un caso de prueba.
 *
 * @package app.tests
 * @subpackage app.tests.fixtures
 */
class RelacionFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Relacion';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'convenios_categoria_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'trabajador_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'empleador_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'area_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'situacion_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'actividad_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'modalidad_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'legajo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '20'),
        'ingreso' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
        'egreso' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
        'antiguedad_reconocida' => array('type' => 'date', 'null' => false),
        'horas' => array('type' => 'integer', 'null' => false, 'default' => '8', 'length' => '2'),
        'basico' => array('type' => 'float', 'null' => false, 'default' => '0.00', 'length' => '10,2'),
        'estado' => array('null' => false, 'default' => 'Activa', 'length' => '9'),
        'observacion' => array('type' => 'text', 'null' => false, 'default' => ''),
        'created' => array('type' => 'datetime', 'null' => false),
        'modified' => array('type' => 'datetime', 'null' => false),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'role_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'permissions' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
  );


/**
 * Los registros.
 *
 * @var array
 * @access public
 */
    var $records = array(
        array(
            'id' => '1',
            'convenios_categoria_id' => '1',
            'trabajador_id' => '1',
            'empleador_id' => '1',
            'area_id' => '1',
            'situacion_id' => '2',
            'actividad_id' => '2',
            'modalidad_id' => '46',
            'legajo' => '27077986',
            'ingreso' => '2006-08-01',
            'egreso' => '0000-00-00',
            'antiguedad_reconocida' => '0000-00-00',
            'horas' => '8',
            'basico' => '0.00',
            'estado' => 'Activa',
            'observacion' => '',
            'created' => '2008-10-14 18:00:17',
            'modified' => '2008-10-14 18:00:17',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
    	),
        array(
            'id' => '2',
            'convenios_categoria_id' => '2',
            'trabajador_id' => '2',
            'empleador_id' => '2',
            'area_id' => '332',
            'situacion_id' => '2',
            'actividad_id' => '77',
            'modalidad_id' => '6',
            'legajo' => '04986076',
            'ingreso' => '2007-10-22',
            'egreso' => '0000-00-00',
            'antiguedad_reconocida' => '0000-00-00',
            'horas' => '8',
            'basico' => '0.00',
            'estado' => 'Activa',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '2008-10-31 10:16:45',
            'user_id' => '15',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
    	),
        array(
            'id' => '3',
            'convenios_categoria_id' => '0',
            'trabajador_id' => '4326',
            'empleador_id' => '47',
            'area_id' => '0',
            'situacion_id' => '2',
            'actividad_id' => '77',
            'modalidad_id' => '6',
            'legajo' => '',
            'ingreso' => '0000-00-00',
            'egreso' => '0000-00-00',
            'antiguedad_reconocida' => '0000-00-00',
            'horas' => '8',
            'basico' => '0.00',
            'estado' => 'Activa',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '15',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
    	),
        array(
            'id' => '10',
            'convenios_categoria_id' => '0',
            'trabajador_id' => '4327',
            'empleador_id' => '83',
            'area_id' => '0',
            'situacion_id' => '2',
            'actividad_id' => '77',
            'modalidad_id' => '6',
            'legajo' => '',
            'ingreso' => '0000-00-00',
            'egreso' => '0000-00-00',
            'antiguedad_reconocida' => '0000-00-00',
            'horas' => '8',
            'basico' => '0.00',
            'estado' => 'Activa',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '15',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
    	)
	);
}

?>