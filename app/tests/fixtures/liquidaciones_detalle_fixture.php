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
class LiquidacionesDetalleFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'LiquidacionesDetalle';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'liquidacion_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'concepto_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'concepto_codigo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'concepto_nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '255'),
        'concepto_tipo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '15'),
        'concepto_periodo' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11'),
        'concepto_sac' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '2'),
        'concepto_imprimir' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '14'),
        'concepto_antiguedad' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '2'),
        'concepto_remuneracion' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '5'),
        'concepto_formula' => array('type' => 'text', 'null' => false, 'default' => ''),
        'concepto_cantidad' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'concepto_orden' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '5'),
        'coeficiente_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'coeficiente_nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'coeficiente_tipo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '15'),
        'coeficiente_valor' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,3'),
        'debug' => array('type' => 'text', 'null' => false, 'default' => ''),
        'valor' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
        'valor_cantidad' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,2'),
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