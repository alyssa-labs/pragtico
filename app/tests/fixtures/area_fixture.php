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
class AreaFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Area';


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
        'direccion' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'contacto' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '100'),
        'telefono' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'fax' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
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
            'nombre' => 'General',
            'direccion' => '',
            'contacto' => '',
            'telefono' => '',
            'fax' => '',
            'created' => '2008-09-03 15:46:51',
            'modified' => '2008-09-03 15:46:51',
            'user_id' => '2',
            'role_id' => '1',
            'group_id' => '3',
            'permissions' => '496',
        ),
        array(
            'id' => '2',
            'empleador_id' => '2',
            'nombre' => 'General',
            'direccion' => '',
            'contacto' => '',
            'telefono' => '',
            'fax' => '',
            'created' => '2008-10-14 16:00:26',
            'modified' => '2008-10-14 16:00:26',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        )
    );
}

?>