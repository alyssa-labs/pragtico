<?php
/**
 * Este archivo contiene un caso de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.cases.behaviors
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

require_once(APP . "tests" . DS . "cases" . DS . "models" . DS . "fake.test.php");


/**
 * Caso de prueba para el Behavior Validaciones.
 *
 * @package app.tests
 * @subpackage app.tests.cases.behaviors
 */
class ValidacionesTestCase extends CakeTestCase {

/**
 * Model que usare en este caso de prueba.
 *
 * @var array
 * @access public
 */
    var $model;


/**
 * Fixtures asociados a este caso de prueba.
 *
 * @var array
 * @access public
 */
	var $fixtures = array('fake_test');


/**
 * Metodo que se ejecuta antes de cada test.
 *
 * @access public
 */
	function startTest() {
		$this->model =& new FakeTest();

		/**
		* Asocio el behavior que debo utilizar.
		*/
		$this->model->Behaviors->attach('Validaciones');
	}


/**
 * Pruebo la validacion del cuit/cuil.
 *
 * @access public
 */
	function testValidCuitCuil() {

		$this->model->validate = array(
			'title' => array(
				array(
					'rule'	=> 'validCuitCuil',
					'message'	=>'La Cuit no es valida.')
			)
		);

		/**
		 * Pruebo con una cuit valida.
		 */
		$data = array('FakeTest' => array(
			'title' => '20-27959940-4'
		));
		$this->model->create($data);
		$result = $this->model->validates();
		$this->assertTrue($result);

		/**
		 * Pruebo con una cuit valida.
		 */
		$data = array('FakeTest' => array(
			'title' => '20-11363961-0'
		));
		$this->model->create($data);
		$result = $this->model->validates();
		$this->assertTrue($result);

		/**
		 * Pruebo con una cuit no valida.
		 * Pruebo que el mensaje de error sea el correcto.
		 */
		$data = array('FakeTest' => array(
			'title' => '20-27959940-5'
		));
		$this->model->create($data);
		$result = $this->model->validates();
		$this->assertFalse($result);
		
		$result = $this->model->validationErrors;
		$expected = array('title' => 'La Cuit no es valida.');
		$this->assertEqual($result, $expected);
		
	}

	
/**
 * Metodo que se ejecuta despues de cada test.
 *
 * @access public
 */
	function endTest() {
		unset($this->model);
	}
}

?>