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
 * @version         $Revision: 1445 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-05-26 12:17:44 -0300 (jue 26 de may de 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase para un fixture para un caso de prueba.
 *
 * @package app.tests
 * @subpackage app.tests.fixtures
 */
class FakeTestFixture extends CakeTestFixture {

/**
 * El nombre de este Fixture.
 *
 * @var string
 * @access public
 */
	var $name = 'FakeTestFixture';
	
	var $table = 'fakes';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'key' => 'primary'),
        'string_field' => array('type' => 'string', 'length' => 50, 'null' => false),
        'test_field' => array('type' => 'text', 'null' => false),
        'integer_field' => array('type' => 'integer', 'null' => false),
        'decimal_field' => array('type' => 'float', 'length' => '10,3', 'null' => false),
        'date_field' => array('type' => 'date', 'null' => false),
        'date_time_field' => array('type' => 'datetime', 'null' => false),
        'created' => array('type' => 'datetime', 'null' => false),
        'modified' => array('type' => 'datetime', 'null' => false),
        'user_id' => array('type' => 'integer', 'null' => false),
        'role_id' => array('type' => 'integer', 'null' => false),
        'group_id' => array('type' => 'integer', 'null' => false),
        'permissions' => array('type' => 'integer', 'null' => false),
    );


/**
 * Los registros.
 *
 * @var array
 * @access public
 */
    var $records = array(
        array (
			'id' 				=> 1,
			'string_field' 		=> 'Primer valor string',
			'test_field' 		=> 'Primer valor text',
			'integer_field' 	=> '1',
			'decimal_field' 	=> '11.150',
			'date_field' 		=> '2007-03-18',
			'date_time_field'	=> '2007-03-18 10:41:31',
			'created' 			=> '2007-03-18 10:39:23',
			'modified' 			=> '2007-03-18 10:41:31',
			'user_id' 			=> '1',
			'role_id' 			=> '0',
			'group_id' 			=> '0',
			'permissions' 		=> '496'),
        array (
			'id' 				=> 2,
			'string_field' 		=> 'Segundo valor string',
			'test_field' 		=> 'Segundo valor text',
			'integer_field' 	=> '2',
			'decimal_field' 	=> '12.250',
			'date_field' 		=> '2007-04-19',
			'date_time_field'	=> '2007-04-19 11:41:31',
			'created' 			=> '2007-03-18 10:39:23',
			'modified' 			=> '2007-03-18 10:41:31',
			'user_id' 			=> '1',
			'role_id' 			=> '0',
			'group_id' 			=> '0',
			'permissions' 		=> '496'),
        array (
			'id' 				=> 3,
			'string_field' 		=> 'Tercer valor string',
			'test_field' 		=> 'Tercer valor text',
			'integer_field' 	=> '3',
			'decimal_field' 	=> '13.350',
			'date_field' 		=> '2008-08-19',
			'date_time_field'	=> '2008-08-19 21:41:31',
			'created' 			=> '2007-03-18 10:39:23',
			'modified' 			=> '2007-03-18 10:41:31',
			'user_id' 			=> '1',
			'role_id' 			=> '0',
			'group_id' 			=> '0',
			'permissions' 		=> '496'),
        array (
			'id' 				=> 4,
			'string_field' 		=> 'Sin Permisos',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '2',
			'role_id' 			=> '0',
			'group_id' 			=> '0',
			'permissions' 		=> '0'),
        array (
			'id' 				=> 5,
			'string_field' 		=> 'Lectura Owner',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '2',
			'role_id' 			=> '0',
			'group_id' 			=> '0',
			'permissions' 		=> '256'),
        array (
			'id' 				=> 6,
			'string_field' 		=> 'Lectura Group/Role',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '2',
			'role_id' 			=> '2',
			'group_id' 			=> '1',
			'permissions' 		=> '32'),
        array (
			'id' 				=> 7,
			'string_field' 		=> 'Lectura Group',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '2',
			'role_id' 			=> '0',
			'group_id' 			=> '1',
			'permissions' 		=> '32'),
        array (
			'id' 				=> 8,
			'string_field' 		=> 'Lectura Role',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '2',
			'role_id' 			=> '2',
			'group_id' 			=> '0',
			'permissions' 		=> '32'),
        array (
			'id' 				=> 9,
			'string_field' 		=> 'Lectura Others',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '0',
			'role_id' 			=> '0',
			'group_id' 			=> '0',
			'permissions' 		=> '4'),
        array (
			'id' 				=> 10,
			'string_field' 		=> 'Lectura Group/Role',
			'test_field' 		=> '',
			'integer_field' 	=> '',
			'decimal_field' 	=> '',
			'date_field' 		=> '',
			'date_time_field'	=> '',
			'created' 			=> '',
			'modified' 			=> '',
			'user_id' 			=> '2',
			'role_id' 			=> '6',
			'group_id' 			=> '3',
			'permissions' 		=> '32')
    );
}

?>