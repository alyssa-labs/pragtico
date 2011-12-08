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
class RolFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Rol';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50', 'key' => 'unique'),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Activo', 'length' => '9'),
        'observacion' => array('type' => 'text', 'null' => false, 'default' => ''),
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
            'nombre' => 'Administrador',
            'estado' => 'Activo',
            'observacion' => '',
            'user_id' => '1',
            'role_id' => '0',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'nombre' => 'Liquidador',
            'estado' => 'Activo',
            'observacion' => '',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '3',
            'permissions' => '500',
        ),
        array(
            'id' => '4',
            'nombre' => 'Personal',
            'estado' => 'Activo',
            'observacion' => '',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '3',
            'permissions' => '500',
        ),
    );
}

?>