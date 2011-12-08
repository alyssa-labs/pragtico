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
class AusenciasMotivoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'AusenciasMotivo';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'motivo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50', 'key' => 'unique'),
        'tipo' => array('type' => 'string', 'null' => false, 'default' => 'Justificada', 'length' => '13'),
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
            'motivo' => 'Justificada (sin especificar)',
            'tipo' => 'Justificada',
            'created' => '2008-09-04 13:09:23',
            'modified' => '2008-09-04 13:09:23',
            'user_id' => '1',
            'role_id' => '0',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '2',
            'motivo' => 'Injustificada (sin especificar)',
            'tipo' => 'Injustificada',
            'created' => '2008-10-15 23:21:51',
            'modified' => '2008-10-15 23:21:51',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        )
    );
}

?>