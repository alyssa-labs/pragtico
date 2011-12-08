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
class RelacionesConceptoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'RelacionesConcepto';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'relacion_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'concepto_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'desde' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
        'hasta' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
        'formula' => array('type' => 'text', 'null' => false, 'default' => ''),
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
            'relacion_id' => '1',
            'concepto_id' => '1',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'relacion_id' => '1',
            'concepto_id' => '11',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '3',
            'relacion_id' => '1',
            'concepto_id' => '10',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '4',
            'relacion_id' => '1',
            'concepto_id' => '3',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '5',
            'relacion_id' => '1',
            'concepto_id' => '6',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '6',
            'relacion_id' => '1',
            'concepto_id' => '13',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '7',
            'relacion_id' => '1',
            'concepto_id' => '5',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '8',
            'relacion_id' => '2',
            'concepto_id' => '10',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '9',
            'relacion_id' => '2',
            'concepto_id' => '11',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '10',
            'relacion_id' => '2',
            'concepto_id' => '7',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '11',
            'relacion_id' => '2',
            'concepto_id' => '3',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '12',
            'relacion_id' => '2',
            'concepto_id' => '5',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '13',
            'relacion_id' => '2',
            'concepto_id' => '6',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-14 17:00:30',
            'modified' => '2008-10-14 17:00:30',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        )
    );
}

?>