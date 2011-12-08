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
class RecibosConceptoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'RecibosConcepto';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'recibo_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'concepto_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
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
            'id' => '8',
            'recibo_id' => '1',
            'concepto_id' => '13',
            'created' => '2008-09-25 15:25:36',
            'modified' => '2008-09-25 15:25:36',
            'user_id' => '1',
            'role_id' => '0',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '9',
            'recibo_id' => '1',
            'concepto_id' => '11',
            'created' => '2008-09-25 15:25:36',
            'modified' => '2008-09-25 15:25:36',
            'user_id' => '1',
            'role_id' => '0',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '10',
            'recibo_id' => '3',
            'concepto_id' => '11',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '11',
            'recibo_id' => '3',
            'concepto_id' => '21',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '12',
            'recibo_id' => '3',
            'concepto_id' => '3',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '13',
            'recibo_id' => '3',
            'concepto_id' => '6',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '14',
            'recibo_id' => '3',
            'concepto_id' => '5',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '15',
            'recibo_id' => '3',
            'concepto_id' => '7',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '16',
            'recibo_id' => '3',
            'concepto_id' => '1',
            'created' => '2008-10-14 16:11:50',
            'modified' => '2008-10-14 16:11:50',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '17',
            'recibo_id' => '4',
            'concepto_id' => '52',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '18',
            'recibo_id' => '4',
            'concepto_id' => '60',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '19',
            'recibo_id' => '4',
            'concepto_id' => '13',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '20',
            'recibo_id' => '4',
            'concepto_id' => '53',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '21',
            'recibo_id' => '4',
            'concepto_id' => '11',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '22',
            'recibo_id' => '4',
            'concepto_id' => '14',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '23',
            'recibo_id' => '4',
            'concepto_id' => '54',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '24',
            'recibo_id' => '4',
            'concepto_id' => '3',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '25',
            'recibo_id' => '4',
            'concepto_id' => '6',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '26',
            'recibo_id' => '4',
            'concepto_id' => '5',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '27',
            'recibo_id' => '4',
            'concepto_id' => '56',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '28',
            'recibo_id' => '4',
            'concepto_id' => '10',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '29',
            'recibo_id' => '4',
            'concepto_id' => '57',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '30',
            'recibo_id' => '4',
            'concepto_id' => '1',
            'created' => '2008-10-14 17:53:29',
            'modified' => '2008-10-14 17:53:29',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '31',
            'recibo_id' => '5',
            'concepto_id' => '1',
            'created' => '2008-10-14 17:54:32',
            'modified' => '2008-10-14 17:54:32',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '37',
            'recibo_id' => '6',
            'concepto_id' => '7',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '38',
            'recibo_id' => '6',
            'concepto_id' => '1',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '39',
            'recibo_id' => '6',
            'concepto_id' => '11',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '40',
            'recibo_id' => '6',
            'concepto_id' => '3',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '41',
            'recibo_id' => '6',
            'concepto_id' => '6',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '42',
            'recibo_id' => '6',
            'concepto_id' => '5',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '43',
            'recibo_id' => '6',
            'concepto_id' => '22',
            'created' => '2008-10-30 00:58:33',
            'modified' => '2008-10-30 00:58:33',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '45',
            'recibo_id' => '6',
            'concepto_id' => '70',
            'created' => '2008-10-30 01:06:19',
            'modified' => '2008-10-30 01:06:19',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
    );
}

?>