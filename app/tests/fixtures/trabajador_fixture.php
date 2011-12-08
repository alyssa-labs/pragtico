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
class TrabajadorFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Trabajador';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'obra_social_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'localidad_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'condicion_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'siniestrado_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'cuil' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '13', 'key' => 'unique'),
        'tipo_documento' => array('null' => false, 'default' => 'Dni', 'length' => '9'),
        'numero_documento' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'apellido' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'sexo' => array('null' => false, 'default' => '', 'length' => '9'),
        'estado_civil' => array('null' => false, 'default' => '', 'length' => '10'),
        'file_size' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11'),
        'file_type' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '255'),
        'file_data' => array('type' => 'binary', 'null' => false, 'default' => ''),
        'cbu' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '22'),
        'tipo_cuenta' => array('null' => false, 'default' => 'Caja de Ahorro', 'length' => '14'),
        'nacimiento' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
        'direccion' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'codigo_postal' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '6'),
        'jubilacion' => array('null' => false, 'default' => '', 'length' => '14'),
        'adherentes_os' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '2'),
        'adicional_os' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,3'),
        'excedentes_os' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,3'),
        'aporte_adicional_os' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,3'),
        'barrio' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'ciudad' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'pais' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'nacionalidad' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'telefono' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'celular' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'email' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
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
            'obra_social_id' => '1',
            'localidad_id' => '1',
            'condicion_id' => '8',
            'siniestrado_id' => '1',
            'cuil' => '20-27077986-8',
            'tipo_documento' => 'Dni',
            'numero_documento' => '27077986',
            'nombre' => 'Trabajador 2',
            'apellido' => 'de Prueba',
            'sexo' => 'Masculino',
            'estado_civil' => 'Soltero',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'cbu' => '0720205888000032976436',
            'tipo_cuenta' => 'Caja de Ahorro',
            'nacimiento' => '1943-03-10',
            'direccion' => '',
            'codigo_postal' => '',
            'jubilacion' => 'Reparto',
            'adherentes_os' => '0',
            'adicional_os' => '0.000',
            'excedentes_os' => '0.000',
            'aporte_adicional_os' => '0.000',
            'barrio' => '',
            'ciudad' => '',
            'pais' => 'Argentina',
            'nacionalidad' => 'Argentina',
            'telefono' => '',
            'celular' => '',
            'email' => '',
            'observacion' => 'Comercio Mensual',
            'created' => '2008-10-06 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '2',
            'group_id' => '1',
            'permissions' => '496',
        ),
        array(
            'id' => '2',
            'obra_social_id' => '2',
            'localidad_id' => '1',
            'condicion_id' => '8',
            'siniestrado_id' => '1',
            'cuil' => '20-07843422-9',
            'tipo_documento' => 'Dni',
            'numero_documento' => '07843422',
            'nombre' => 'Trabajador 2',
            'apellido' => 'de Prueba',
            'sexo' => 'Masculino',
            'estado_civil' => 'Casado',
            'file_size' => '0',
            'file_type' => '',
            'file_data' => '',
            'cbu' => '',
            'tipo_cuenta' => 'Caja de Ahorro',
            'nacimiento' => '1949-07-17',
            'direccion' => 'Direccion A',
            'codigo_postal' => '',
            'jubilacion' => 'Reparto',
            'adherentes_os' => '0',
            'adicional_os' => '0.000',
            'excedentes_os' => '0.000',
            'aporte_adicional_os' => '0.000',
            'barrio' => 'Cuesta Dorada',
            'ciudad' => 'La Calera',
            'pais' => 'Argentina',
            'nacionalidad' => '',
            'telefono' => '0351 155144823',
            'celular' => '',
            'email' => '',
            'observacion' => 'Construccion por Hora',
            'created' => '2008-10-06 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
        )
    );
}

?>