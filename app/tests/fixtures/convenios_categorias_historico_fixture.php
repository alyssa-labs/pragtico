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
class ConveniosCategoriasHistoricoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'ConveniosCategoriasHistorico';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'convenios_categoria_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'desde' => array('type' => 'date', 'null' => false),
        'hasta' => array('type' => 'date', 'null' => false),
        'costo' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,3'),
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
            'convenios_categoria_id' => '1',
            'desde' => '2008-06-01',
            'hasta' => '2009-03-31',
            'costo' => '1348.920',
            'observacion' => '',
            'created' => '2008-08-28 02:39:56',
            'modified' => '2008-08-28 23:18:12',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'convenios_categoria_id' => '2',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'costo' => '7.540',
            'observacion' => '',
            'created' => '2008-08-28 02:41:52',
            'modified' => '2008-08-28 03:09:07',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        )
    );
}

?>