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
class AyudaFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
	var $name = 'Ayuda';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
  var $fields = array(
      'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
      'texto' => array('type' => 'text', 'null' => false, 'default' => '', 'length' => ''),
      'ayuda' => array('type' => 'text', 'null' => false, 'default' => '', 'length' => ''),
      'created' => array('type' => 'datetime', 'null' => false, 'default' => '', 'length' => ''),
      'modified' => array('type' => 'datetime', 'null' => false, 'default' => '', 'length' => ''),
      'user_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
      'role_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
      'group_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
      'permissions' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index')
  );
  
  
/**
 * Los registros.
 *
 * @var array
 * @access public
 */
  var $records = array(
  );
}

?>