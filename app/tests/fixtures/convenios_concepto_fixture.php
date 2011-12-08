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
class ConveniosConceptoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'ConveniosConcepto';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'convenio_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
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
            'id' => '3',
            'convenio_id' => '21',
            'concepto_id' => '21',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '=@sueldo_bruto * 2.5 / 100',
            'observacion' => '',
            'created' => '2008-10-14 17:11:09',
            'modified' => '2008-10-14 17:11:09',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '500',
        ),
        array(
            'id' => '7',
            'convenio_id' => '9',
            'concepto_id' => '7',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '=if (\'#periodo_liquidacion\' = \'2Q\', 27.46, 0)',
            'observacion' => '',
            'created' => '2008-10-30 01:28:22',
            'modified' => '2008-10-30 01:37:41',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '8',
            'convenio_id' => '9',
            'concepto_id' => '70',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '=@sueldo_bruto * 1.5 / 100',
            'observacion' => '',
            'created' => '2008-10-30 01:35:22',
            'modified' => '2008-10-30 01:35:22',
            'user_id' => '2',
            'role_id' => '3',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '9',
            'convenio_id' => '32',
            'concepto_id' => '66',
            'desde' => '0000-00-00',
            'hasta' => '0000-00-00',
            'formula' => '',
            'observacion' => '',
            'created' => '2008-10-31 10:17:34',
            'modified' => '2008-10-31 10:17:34',
            'user_id' => '15',
            'role_id' => '2',
            'group_id' => '4',
            'permissions' => '496',
        )
    );
}

?>