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
class ReciboFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Recibo';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'empleador_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'descripcion' => array('type' => 'text', 'null' => false, 'default' => ''),
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
            'empleador_id' => '1',
            'nombre' => 'test1',
            'descripcion' => '',
            'created' => '2008-09-02 10:57:02',
            'modified' => '2008-09-02 10:57:02',
            'user_id' => '1',
            'role_id' => '0',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '3',
            'empleador_id' => '197',
            'nombre' => 'Operarios',
            'descripcion' => '',
            'created' => '2008-10-14 16:02:17',
            'modified' => '2008-10-14 16:02:17',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '4',
            'empleador_id' => '1',
            'nombre' => 'Comercio',
            'descripcion' => '',
            'created' => '2008-10-14 17:48:48',
            'modified' => '2008-10-14 17:48:48',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '5',
            'empleador_id' => '1',
            'nombre' => 'Pasante',
            'descripcion' => '',
            'created' => '2008-10-14 17:49:05',
            'modified' => '2008-10-14 17:49:05',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '6',
            'empleador_id' => '237',
            'nombre' => 'Quincenal',
            'descripcion' => '',
            'created' => '2008-10-30 00:45:59',
            'modified' => '2008-10-30 00:45:59',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
    );
}

?>