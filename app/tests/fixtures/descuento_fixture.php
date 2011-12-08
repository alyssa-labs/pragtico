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
class DescuentoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Descuento';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'relacion_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'alta' => array('type' => 'date', 'null' => false),
        'desde' => array('type' => 'date', 'null' => false),
        'descripcion' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '100'),
        'monto' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'cuotas' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '2'),
        'maximo' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'descontar' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'concurrencia' => array('type' => 'string', 'null' => false, 'default' => 'Permite superponer', 'length' => '18'),
        'tipo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '17'),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Activo', 'length' => '10'),
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
            'id' => '5',
            'relacion_id' => '5',
            'alta' => '2008-09-01',
            'desde' => '2008-09-01',
            'descripcion' => '',
            'monto' => '200.00',
            'cuotas' => '0',
            'maximo' => '0.00',
            'descontar' => '1',
            'concurrencia' => 'Permite superponer',
            'tipo' => 'Vale',
            'estado' => 'Activo',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-20 17:47:33',
            'modified' => '2008-10-20 17:47:33',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '6',
            'relacion_id' => '6',
            'alta' => '2008-09-01',
            'desde' => '2008-09-01',
            'descripcion' => '',
            'monto' => '300.00',
            'cuotas' => '0',
            'maximo' => '0.00',
            'descontar' => '1',
            'concurrencia' => 'Permite superponer',
            'tipo' => 'Vale',
            'estado' => 'Activo',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-20 17:47:33',
            'modified' => '2008-10-20 17:47:33',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
        array(
            'id' => '7',
            'relacion_id' => '7',
            'alta' => '2008-09-01',
            'desde' => '2008-09-01',
            'descripcion' => '',
            'monto' => '100.00',
            'cuotas' => '0',
            'maximo' => '0.00',
            'descontar' => '1',
            'concurrencia' => 'Permite superponer',
            'tipo' => 'Vale',
            'estado' => 'Activo',
            'observacion' => 'Ingresado desde planilla',
            'created' => '2008-10-29 23:53:16',
            'modified' => '2008-10-29 23:53:16',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '496',
        ),
    );
}

?>