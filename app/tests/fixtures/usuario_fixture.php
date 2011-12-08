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
class UsuarioFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Usuario';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '30', 'key' => 'unique'),
        'clave' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '32'),
        'nombre_completo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '100'),
        'email' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '255'),
        'ultimo_ingreso' => array('type' => 'datetime', 'null' => false),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Activo', 'length' => '9'),
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
            'nombre' => 'root',
            'clave' => '9dd4e461268c8034f5c8564e155c67a6',
            'nombre_completo' => 'Martin Radosta',
            'email' => 'mradosta@pragmatia.com',
            'ultimo_ingreso' => '2008-11-08 00:32:43',
            'estado' => 'Activo',
            'created' => '2007-12-17 17:04:55',
            'modified' => '2008-11-08 00:32:43',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '2',
            'nombre' => 'usuario2',
            'clave' => '9dd4e461268c8034f5c8564e155c67a6',
            'nombre_completo' => 'Natalia Benitez',
            'email' => 'nataliabenitez@pragmatia.com.ar',
            'ultimo_ingreso' => '2008-10-30 10:52:47',
            'estado' => 'Activo',
            'created' => '2008-08-19 01:15:51',
            'modified' => '2008-10-30 10:52:47',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '3',
            'nombre' => 'usuario3',
            'clave' => '9dd4e461268c8034f5c8564e155c67a6',
            'nombre_completo' => 'Mario Ruggieri',
            'email' => 'mruggieri@pragmatia.com.ar',
            'ultimo_ingreso' => '2008-11-05 17:37:13',
            'estado' => 'Activo',
            'created' => '2008-09-01 15:22:49',
            'modified' => '2008-11-05 17:37:13',
            'user_id' => '25',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        )
    );
}

?>