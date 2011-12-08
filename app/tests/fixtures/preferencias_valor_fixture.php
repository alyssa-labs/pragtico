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
class PreferenciasValorFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'PreferenciasValor';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'preferencia_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'valor' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '250'),
        'predeterminado' => array('type' => 'string', 'null' => false, 'default' => 'No', 'length' => '2'),
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
            'preferencia_id' => '1',
            'valor' => 'popup',
            'predeterminado' => 'Si',
            'created' => '0000-00-00 00:00:00',
            'modified' => '2008-05-09 00:05:27',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'preferencia_id' => '3',
            'valor' => '15',
            'predeterminado' => 'Si',
            'created' => '2008-01-31 18:55:33',
            'modified' => '2008-04-23 21:41:25',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '3',
            'preferencia_id' => '4',
            'valor' => 'normal',
            'predeterminado' => 'Si',
            'created' => '2008-01-31 18:56:42',
            'modified' => '2008-06-11 18:55:05',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '4',
            'preferencia_id' => '4',
            'valor' => 'ajax',
            'predeterminado' => 'No',
            'created' => '2008-01-31 19:45:11',
            'modified' => '2008-06-11 18:55:05',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '6',
            'preferencia_id' => '2',
            'valor' => 'ajax',
            'predeterminado' => 'No',
            'created' => '2008-01-31 19:46:59',
            'modified' => '2008-09-04 12:48:08',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '7',
            'preferencia_id' => '5',
            'valor' => 'ajax',
            'predeterminado' => 'No',
            'created' => '2008-02-01 09:54:19',
            'modified' => '2008-05-09 00:04:39',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '11',
            'preferencia_id' => '2',
            'valor' => 'nomal',
            'predeterminado' => 'Si',
            'created' => '2008-02-07 10:47:10',
            'modified' => '2008-09-04 12:48:08',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '12',
            'preferencia_id' => '5',
            'valor' => 'normal',
            'predeterminado' => 'Si',
            'created' => '2008-02-08 12:06:44',
            'modified' => '2008-05-09 00:04:39',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '13',
            'preferencia_id' => '3',
            'valor' => '50',
            'predeterminado' => 'No',
            'created' => '2008-04-24 10:02:52',
            'modified' => '2008-04-24 10:02:52',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '14',
            'preferencia_id' => '3',
            'valor' => 'todos',
            'predeterminado' => 'No',
            'created' => '2008-04-24 10:03:06',
            'modified' => '2008-04-24 10:03:06',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '15',
            'preferencia_id' => '6',
            'valor' => 'activado',
            'predeterminado' => 'Si',
            'created' => '2008-07-13 23:22:17',
            'modified' => '2008-07-14 13:45:12',
            'user_id' => '1',
            'role_id' => '13',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '20',
            'preferencia_id' => '6',
            'valor' => 'desactivado',
            'predeterminado' => 'No',
            'created' => '2008-07-14 13:03:01',
            'modified' => '2008-07-14 13:45:12',
            'user_id' => '1',
            'role_id' => '13',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '21',
            'preferencia_id' => '2',
            'valor' => 'bpa',
            'predeterminado' => 'No',
            'created' => '2008-07-14 13:45:12',
            'modified' => '2008-09-04 12:48:08',
            'user_id' => '1',
            'role_id' => '13',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '23',
            'preferencia_id' => '7',
            'valor' => 'empiece',
            'predeterminado' => 'Si',
            'created' => '2008-07-31 18:36:06',
            'modified' => '2008-07-31 18:36:06',
            'user_id' => '1',
            'role_id' => '13',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '24',
            'preferencia_id' => '7',
            'valor' => 'contenga',
            'predeterminado' => 'No',
            'created' => '2008-07-31 18:36:06',
            'modified' => '2008-07-31 18:36:06',
            'user_id' => '1',
            'role_id' => '13',
            'group_id' => '0',
            'permissions' => '500',
        ),
    );
}

?>