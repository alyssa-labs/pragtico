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
class SiniestradoFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Siniestrado';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'codigo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '20', 'key' => 'unique'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
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
            'codigo' => '0',
            'nombre' => 'No Incapacitado',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '2',
            'codigo' => '1',
            'nombre' => 'ILT Incapacidad Laboral Temporaria',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '3',
            'codigo' => '2',
            'nombre' => 'ILPPP Incapacidad Laboral Permanente Parcial Provisoria',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '4',
            'codigo' => '3',
            'nombre' => 'ILPPD Incapacidad Laboral Permanente Parcial Definitiva',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '5',
            'codigo' => '4',
            'nombre' => 'ILPTP Incapacidad Laboral Permanente Total Provisoria',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '6',
            'codigo' => '5',
            'nombre' => 'Capital de recomposición Art. 15, ap. 3, Ley 24557',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '7',
            'codigo' => '6',
            'nombre' => 'Ajuste Definitivo ILPPD de pago mensual',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '8',
            'codigo' => '7',
            'nombre' => 'RENTA PERIODICA ILPPD Incapacidad Laboral Permanente Parcial Definitivo >50%<66%',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '9',
            'codigo' => '8',
            'nombre' => 'SRT/SSN F.Garantía /F Reserva ILT Incapacidad Laboral Temporaria',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '10',
            'codigo' => '9',
            'nombre' => 'SRT/SSN F.Garantía /F Reserva ILPPP Incapacidad Laboral Permanente Parcial Provisoria',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '11',
            'codigo' => '10',
            'nombre' => 'SRT/SSN F.Garantía /F Reserva ILPTP Incapacidad Laboral Permanente Total Provisoria',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '12',
            'codigo' => '11',
            'nombre' => 'SRT/SSN F.Garantía /F Reserva ILPPD Incapacidad Laboral Permanente Parcial Definitiva',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
        array(
            'id' => '13',
            'codigo' => '12',
            'nombre' => 'ILPPD Beneficios Devengados Art. 11 p.4',
            'observacion' => '',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '452',
        ),
    );
}

?>