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
class LiquidacionFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Liquidacion';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'fecha' => array('type' => 'date', 'null' => false),
        'ano' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '4'),
        'mes' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '2'),
        'periodo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'tipo' => array('type' => 'string', 'null' => false, 'default' => 'Normal', 'length' => '17'),
        'estado' => array('type' => 'string', 'null' => false, 'default' => 'Sin Confirmar', 'length' => '13'),
        'observacion' => array('type' => 'text', 'null' => false, 'default' => ''),
        'relacion_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'relacion_ingreso' => array('type' => 'date', 'null' => false),
        'relacion_horas' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '2'),
        'relacion_basico' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'relacion_area_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'trabajador_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'trabajador_cuil' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '13'),
        'trabajador_nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'trabajador_apellido' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'empleador_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'empleador_cuit' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '13'),
        'empleador_nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'empleador_direccion' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'convenio_categoria_convenio_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'convenio_categoria_nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'convenio_categoria_costo' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'convenio_categoria_jornada' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '8'),
        'remunerativo' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'no_remunerativo' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'deduccion' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'total_pesos' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'total_beneficios' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'total' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'factura_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11'),
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
    var $records = array();
	
}

?>