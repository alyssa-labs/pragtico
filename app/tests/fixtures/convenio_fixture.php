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
class ConvenioFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Convenio';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'numero' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50', 'key' => 'unique'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150', 'key' => 'unique'),
        'file_size' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11'),
        'file_type' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '255'),
        'file_data' => array('type' => 'binary', 'null' => false, 'default' => ''),
        'actualizacion' => array('type' => 'date', 'null' => false),
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
            'numero' => '130/75',
            'nombre' => 'Empleados de comercio',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'actualizacion' => '0000-00-00',
            'observacion' => '',
            'created' => '2008-08-28 02:19:13',
            'modified' => '2008-09-02 09:12:11',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'numero' => '227/93',
            'nombre' => 'Construccion - Uocra',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'actualizacion' => '0000-00-00',
            'observacion' => '',
            'created' => '2008-08-28 02:19:13',
            'modified' => '2008-09-02 09:12:11',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        )
    );
}

?>