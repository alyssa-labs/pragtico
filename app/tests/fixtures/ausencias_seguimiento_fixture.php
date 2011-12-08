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
class AusenciasSeguimientoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'AusenciasSeguimiento';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'ausencia_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'dias' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '2,1'),
        'comprobante' => array('type' => 'string', 'null' => false, 'default' => 'No', 'length' => '2'),
        'file_size' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11'),
        'file_type' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '255'),
        'file_data' => array('type' => 'binary', 'null' => false, 'default' => ''),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Pendiente', 'length' => '10'),
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
            'ausencia_id' => '2',
            'dias' => '2.0',
            'comprobante' => 'No',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'estado' => 'Confirmado',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-29 23:53:15',
            'modified' => '2008-10-29 23:53:15',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '2',
            'ausencia_id' => '2',
            'dias' => '2.0',
            'comprobante' => 'No',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'estado' => 'Confirmado',
            'observacion' => '',
            'created' => '2008-11-05 18:17:04',
            'modified' => '2008-11-05 18:17:04',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '3',
            'ausencia_id' => '2',
            'dias' => '2.0',
            'comprobante' => 'No',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'estado' => 'Pendiente',
            'observacion' => '',
            'created' => '2008-11-05 18:17:04',
            'modified' => '2008-11-05 18:17:04',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '4',
            'ausencia_id' => '3',
            'dias' => '2.0',
            'comprobante' => 'No',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'estado' => 'Confirmado',
            'observacion' => '',
            'created' => '2008-11-05 18:17:04',
            'modified' => '2008-11-05 18:17:04',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        )
    );
}

?>