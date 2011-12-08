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
class GrupoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Grupo';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'empleador_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '30', 'key' => 'unique'),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Activo', 'length' => '9'),
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
            'empleador_id' => '',
            'nombre' => 'Grupo A',
            'estado' => 'Activo',
            'observacion' => '',
            'created' => '2008-10-14 16:17:32',
            'modified' => '2008-10-12 19:21:13',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'empleador_id' => '97',
            'nombre' => 'Grupo B',
            'estado' => 'Activo',
            'observacion' => '',
            'created' => '2008-10-14 16:17:32',
            'modified' => '2008-11-04 16:05:49',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '4',
            'empleador_id' => '1',
            'nombre' => 'Grupo C',
            'estado' => 'Activo',
            'observacion' => '',
            'created' => '2008-10-14 16:31:03',
            'modified' => '2008-11-04 16:05:17',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        )
    );
}

?>