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
 * @subpackage      app.tests.cases.components
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */


App::import('Component', array('Paginador', 'Session', 'Util'));

require_once(APP . 'tests' . DS . 'cases' . DS . 'controllers' . DS . 'fake_test_controller.test.php');
require_once(APP . 'tests' . DS . 'cases' . DS . 'models' . DS . 'fake.test.php');


/**
 * Caso de prueba para el Component Paginador.
 *
 * @package app.tests
 * @subpackage app.tests.cases.components
 */
class PaginadorComponentTestCase extends CakeTestCase {

/**
 * El component que probare.
 *
 * @var array
 * @access public
 */
    var $PaginadorComponentTest;
    
    
/**
 * Controller que usare en este caso de prueba.
 *
 * @var array
 * @access public
 */
    var $controller;


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
    	$this->PaginadorComponentTest =& new PaginadorComponent();
    	$this->PaginadorComponentTest->Util = new UtilComponent();
    	$this->FakeController = new FakeTestController();
    	$this->FakeController->FakeTestModel = new FakeModel();
    	$this->FakeController->Session = new SessionComponent();

    	/**
    	* Me aseguro que no existan datos en la session.
    	*/
    	$this->FakeController->Session->destroy();
		
    	$this->PaginadorComponentTest->startup($this->FakeController);
    }


/**
 * Pruebo la generacion de condiciones.
 *
 * @access public
 */
    function testGenerarCondicion() {
    	$this->FakeController->data['Condicion']['FakeTestModel-id'] = '1';
    	$this->FakeController->data['Condicion']['FakeTestModel-string_field'] = 'texto string';
    	$this->FakeController->data['Condicion']['FakeTestModel-test_field'] = 'texto text';
    	$this->FakeController->data['Condicion']['FakeTestModel-integer_field'] = '145';
    	$this->FakeController->data['Condicion']['FakeTestModel-decimal_field'] = '145.456';
    	$this->FakeController->data['Condicion']['FakeTestModel-date_field'] = '21/10/2008';
    	$this->FakeController->data['Condicion']['FakeTestModel-date_time_field'] = '22/10/2008 18:45:43';
    	$this->FakeController->action = 'index';
    	
		$expected = array(
			'FakeTestModel.id' 						=> '1',
			'FakeTestModel.string_field like' 		=> '%texto string%',
			'FakeTestModel.test_field like' 		=> '%texto text%',
			'FakeTestModel.integer_field' 			=> '145',
			'FakeTestModel.decimal_field' 			=> '145.456',
			'FakeTestModel.date_field' 				=> '2008-10-21',
			'FakeTestModel.date_time_field' 		=> '2008-10-22 18:45:43'
		);
		
		$result = $this->PaginadorComponentTest->generarCondicion();
		$this->assertEqual($result, $expected);


		$result = $this->FakeController->Session->read('filtros.' . $this->FakeController->name . '.' . $this->FakeController->action);
		$expected = array('condiciones' => $expected, 'valoresLov' => array());
		$this->assertEqual($result, $expected);


		$expected = array(
			'FakeTestModel-id' 						=> '1',
			'FakeTestModel-string_field' 			=> 'texto string',
			'FakeTestModel-test_field' 				=> 'texto text',
			'FakeTestModel-integer_field' 			=> '145',
			'FakeTestModel-decimal_field' 			=> '145.456',
			'FakeTestModel-date_field' 				=> '21/10/2008',
			'FakeTestModel-date_time_field' 		=> '22/10/2008 18:45:43'
		);
		$result = $this->FakeController->data;
		$this->assertEqual($result, array('Condicion'=>$expected));
	}
	

/**
 * Pruebo la generacion de condiciones de rango de campos fecha.
 *
 * @access public
 */
    function testGenerarCondicionRangoFecha() {
    	$this->FakeController->data['Condicion']['FakeTestModel-id'] = '1';
    	$this->FakeController->data['Condicion']['FakeTestModel-date_field__desde'] = '21/10/2008';
    	$this->FakeController->data['Condicion']['FakeTestModel-date_field__hasta'] = '25/10/2008';
    	$this->FakeController->action = 'index';
    	
		$expected = array(
			'FakeTestModel.id' 						=> '1',
			'FakeTestModel.date_field >=' 			=> '2008-10-21',
			'FakeTestModel.date_field <=' 			=> '2008-10-25'
		);
		
		$result = $this->PaginadorComponentTest->generarCondicion();
		$this->assertEqual($result, $expected);

		
		$result = $this->FakeController->Session->read('filtros.' . $this->FakeController->name . '.' . $this->FakeController->action);
		$expected = array('condiciones' => $expected, 'valoresLov' => array());
		$this->assertEqual($result, $expected);

		
		$expected = array(
			'FakeTestModel-id' 								=> '1',
			'FakeTestModel-date_field__desde' 			=> '21/10/2008',
			'FakeTestModel-date_field__hasta' 			=> '25/10/2008'
		);
		$result = $this->FakeController->data;
		$this->assertEqual($result, array('Condicion'=>$expected));
	}


/**
 * Pruebo la generacion de condiciones de rango de campos fechaHora.
 *
 * @access public
 */
    function testGenerarCondicionRangoFechaHora() {
    	$this->FakeController->data['Condicion']['FakeTestModel-date_time_field__desde'] = '21/10/2008 22:34:56';
    	$this->FakeController->data['Condicion']['FakeTestModel-date_time_field__hasta'] = '25/10/2008 12:34:56';
    	$this->FakeController->action = 'index';
    	
		$expected = array(
			'FakeTestModel.date_time_field >=' 			=> '2008-10-21 22:34:56',
			'FakeTestModel.date_time_field <=' 			=> '2008-10-25 12:34:56'
		);
		
		$result = $this->PaginadorComponentTest->generarCondicion();
		$this->assertEqual($result, $expected);


		$result = $this->FakeController->Session->read('filtros.' . $this->FakeController->name . '.' . $this->FakeController->action);
		$expected = array('condiciones' => $expected, 'valoresLov' => array());
		$this->assertEqual($result, $expected);

		$expected = array(
			'FakeTestModel-date_time_field__desde' 	=> '21/10/2008 22:34:56',
			'FakeTestModel-date_time_field__hasta' 	=> '25/10/2008 12:34:56'
		);
		$result = $this->FakeController->data;
		$this->assertEqual($result, array('Condicion'=>$expected));
	}


/**
 * Pruebo la generacion de condiciones de rango de campos fecha y fechaHora.
 *
 * @access public
 */
    function testGenerarCondicionRangoFechaHoraMixto() {
    	$this->FakeController->data['Condicion']['FakeTestModel-date_field__desde'] = '21/10/2008';
    	$this->FakeController->data['Condicion']['FakeTestModel-date_time_field__hasta'] = '25/10/2008 12:34:56';
    	$this->FakeController->action = 'index';
    	
		$expected = array(
			'FakeTestModel.date_field >=' 			=> '2008-10-21',
			'FakeTestModel.date_time_field <=' 		=> '2008-10-25 12:34:56'
		);
		
		$result = $this->PaginadorComponentTest->generarCondicion();
		$this->assertEqual($result, $expected);


		$result = $this->FakeController->Session->read('filtros.' . $this->FakeController->name . '.' . $this->FakeController->action);
		$expected = array('condiciones' => $expected, 'valoresLov' => array());
		$this->assertEqual($result, $expected);

		$expected = array(
			'FakeTestModel-date_field__desde' 		=> '21/10/2008',
			'FakeTestModel-date_time_field__hasta' 	=> '25/10/2008 12:34:56'
		);
		$result = $this->FakeController->data;
		$this->assertEqual($result, array('Condicion'=>$expected));
	}


/**
 * Metodo que se ejecuta despues de cada test.
 *
 * @access public
 */
	function endTest() {
		unset($this->FakeController);
		ClassRegistry::flush();
	}
	
}


?>