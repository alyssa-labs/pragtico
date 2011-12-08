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
 * @subpackage      app.tests.cases.helpers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

App::import('Helper', array('AppForm', 'Formato', 'Html'));
App::import('Component', 'Session');
//App::import('Core', array('ClassRegistry', 'Controller', 'View', 'Model', 'Security'));
require_once(APP . 'tests' . DS . 'cases' . DS . 'controllers' . DS . 'fake_test_controller.test.php');
require_once(APP . 'tests' . DS . 'cases' . DS . 'models' . DS . 'fake.test.php');


/**
 * ContactTestController class
 *
 * @package       cake
 * @subpackage    app.tests.cases.view.helpers
 */
class ContactTestController extends Controller {
/**
 * name property
 *
 * @var string 'ContactTest'
 * @access public
 */
	var $name = 'ContactTest';
/**
 * uses property
 *
 * @var mixed null
 * @access public
 */
	var $uses = null;
}


/**
 * Caso de prueba para el Helper AppForm.
 *
 * @package app.tests
 * @subpackage app.tests.cases.helpers
 */

class AppFormTest extends CakeTestCase {

/**
 * Helper que usare en este caso de prueba.
 *
 * @var array
 * @access public
 */
	var $appForm;

	
	var $fixtures = array('fake_test');
	

/**
 * Metodo que se ejecuta antes de cada test.
 *
 * @access public
 */
	function startTest() {
		$this->appForm = new AppFormHelper();
		$this->appForm->Html = new HtmlHelper();
		$this->appForm->Formato = new FormatoHelper();
		
		$this->FakeController = new FakeTestController();
		$this->FakeController->FakeTestModel = new FakeModel();
		$this->View =& new View($this->FakeController);
	}

	
/**
 * testInputLov method
 *
 * @access public
 * @return void
 */
	function testInputLov() {
		
		$tagName = 'FakeTestModel.string_field';
		$options = array(	'lov' => array(
				'controller'		=> 'some_controller',
				'seleccionMultiple'	=> 0,
				'camposRetorno'		=> array('Model1.field1', 'Model2.field2')));
		
		$result = $this->appForm->input($tagName, $options);
		/*
		
	<div class="input text"> -
	<label for="FakeTestModelStringField__">String Field</label> -
	<input 	name="data[FakeTestModel][string_field__]" -
			type="text" -
			id="FakeTestModelStringField__" -
			readonly="readonly" -
			class="izquierda" -
			value="" />-
	<a 		href="javascript:void(0);" -
			onclick="abrirVentana(&#039;1279301613&#039;, &#039;/some_controller/index/layout:lov/separadorRetorno:-/retornarA:FakeTestModelStringField/targetId:target_1279301613/seleccionMultiple:0/camposRetorno:Model1.field1|Model2.field2&#039;)">
		<img 	src="img/search.gif" -
				alt="Buscar" -
				class="lupa_lov" -
				id="lupa_1279301613" -
				title="Buscar" />-
	</a>
	<input 	type="hidden" -
			name="data[FakeTestModel][string_field]" -
			id="FakeTestModelStringField" -
			maxlength="50" -
			value="" />-
	</div>

							'onclick' => 'preg:/abrirVentana\(&#039;[0-9]+&#039;, &#039;\/some_controller\/index\/layout:lov\/separadorRetorno:-\/retornarA:FakeTestModelStringField\/targetId:target_[0-9]+\/seleccionMultiple:0\/camposRetorno:Model1.field1|Model2.field2&#039;\)/'),
		*/

		
		$expected = array(
			'div' => array('class' => 'input text'),
			'label' => array('for' => 'FakeTestModelStringField__'),
			'String Field',
   			'/label',
			'input' => array(	'name' => 'data[FakeTestModel][string_field__]', 
							 	'type' => 'text', 
								'readonly' => 'readonly', 
								'value' => '', 
								'class' => 'izquierda', 
								'id' => 'FakeTestModelStringField__'),
			'a' => array(	'href' => 'javascript:void(0);', 
							'onclick' => 'preg:/.*/'),
			'img' => array('src' 	=> 'img/search.gif', 
						   'class' 	=> 'lupa_lov', 
		 					'alt' 	=> 'Buscar', 
							'title' => 'Buscar', 
	   						'id' 	=> 'preg:/lupa_[0-9]+/'),
			'/a',
			array('input' => array(	'name' 	=> 'data[FakeTestModel][string_field]', 
				  					'type' 	=> 'hidden', 
		   							'value' => '', 
									'id' => 'FakeTestModelStringField', 
		 							'maxlength' => '50', 
		  							'value' => '')),
			'/div'
		);
		//d($expected);
		$this->assertTags($result, $expected);
	}

	
/**
 * testInputDateAndDateTime method
 *
 * @access public
 * @return void
 */
	function testInputDateAndDateTime() {

		$tagName = 'FakeTestModel.date_field';
		$options = array('after' => 'Some text after');
		$result = $this->appForm->input($tagName, $options);
		$expected = array(
			'div' => array('class' => 'input text'),
			'label' => array('for' => 'FakeTestModelDateField'),
			'Date Field',
   			'/label',
			'input' => array('name' => 'data[FakeTestModel][date_field]', 'type' => 'text', 'value' => '', 'class' => 'fecha', 'id' => 'FakeTestModelDateField'),
			'a' => array('href' => 'javascript:NewCal(\'FakeTestModelDateField\', \'dd/mm/yyyy\')', 'id' => 'FakeTestModelDateField'),
			'img' => array('src' => 'img/calendar.gif', 'class' => 'fecha', 'alt' => 'PickDate', 'title' => 'Pick date'),
			'/a',
   			'Some text after',
			'/div'
		);
		$this->assertTags($result, $expected);
		
		
		$tagName = 'FakeTestModel.date_time_field';
		$options = array('label' => 'MyLabel', 'before' => 'Some text before');
		$result = $this->appForm->input($tagName, $options);
		$expected = array(
			'div' => array('class' => 'input text'),
			'Some text before',
			'label' => array('for' => 'FakeTestModelDateTimeField'),
			'MyLabel',
   			'/label',
			'input' => array('name' => 'data[FakeTestModel][date_time_field]', 'type' => 'text', 'value' => '', 'class' => 'fecha', 'id' => 'FakeTestModelDateTimeField'),
			'a' => array('href' => 'javascript:NewCal(\'FakeTestModelDateTimeField\', \'dd/mm/yyyy\', true, 24, \'dropdown\', true)', 'id' => 'FakeTestModelDateTimeField'),
			'img' => array('src' => 'img/calendar.gif', 'class' => 'fecha', 'alt' => 'PickDate', 'title' => 'Pick date'),
			'/a',
			'/div'
		);
		$this->assertTags($result, $expected);
	}


/**
 * testInputSelectMultipleCheckbox method
 *
 * @access public
 * @return void
 */
	function xxtestInputSelectMultipleCheckbox() {
		
		$tagName = 'FakeModel.date_field';
		//d($this->FakeController->FakeModel->find('all', array('checkSecurity' => false)));
		
		$this->View->data = array('Bar.foo' => array('1' => 'Hello', '2' => 'Perfect', '4' => 'World'));
		$options = array(	'label' 	=> 'MyLabel',
						 	'multiple' 	=> 'checkbox',
							'value' 	=> '3',
							'options' 	=> array('1' => 'Hello', '2' => 'Perfect', '4' => 'World'));
		$result = $this->appForm->input($tagName, $options);
		d($result);
		$expected = array(
			'div' => array('class' => 'input select'),
			'label' => array('for' => 'BarFoo'),
			'MyLabel',
   			'/label',
			'input' => array('type' => 'hidden', 'name' => 'data[Bar][foo]', 'value' => ''),
			array('div' => array('class' => 'checkbox')),
			array('input' => array('type' => 'checkbox', 'name' => 'data[Bar][foo][]', 'value' => '1', 'id' => 'BarFoo1')),
			array('label' => array('for' => 'BarFoo1')),
			'Hello',
   			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array('type' => 'checkbox', 'name' => 'data[Bar][foo][]', 'value' => '2', 'id' => 'BarFoo2')),
			array('label' => array('for' => 'BarFoo2')),
			'Perfect',
   			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array('type' => 'checkbox', 'name' => 'data[Bar][foo][]', 'value' => '4', 'id' => 'BarFoo4')),
			array('label' => array('for' => 'BarFoo4')),
			'World',
   			'/label',
			'/div',
			'/div'
		);
		$this->assertTags($result, $expected);
		
		
		$options = array(	'multiple' 	=> 'checkbox', 
							'options' 	=> array('0' => 'Hello', '1' => 'World'));
		$result = $this->appForm->input($tagName, $options);
		$expected = array(
			'div' => array('class' => 'input select'),
			'label' => array('for' => 'BarFoo'),
			'Foo',
   			'/label',
			'input' => array('type' => 'hidden', 'name' => 'data[Bar][foo]', 'value' => ''),
			array('div' => array('class' => 'checkbox')),
			array('input' => array('type' => 'checkbox', 'name' => 'data[Bar][foo][]', 'value' => '0', 'id' => 'BarFoo0')),
			array('label' => array('for' => 'BarFoo0')),
			'Hello',
   			'/label',
			'/div',
			array('div' => array('class' => 'checkbox')),
			array('input' => array('type' => 'checkbox', 'name' => 'data[Bar][foo][]', 'value' => '1', 'id' => 'BarFoo1')),
			array('label' => array('for' => 'BarFoo1')),
			'World',
   			'/label',
			'/div',
			'/div'
		);
		$this->assertTags($result, $expected);
	}
	
	
/**
 * testInputDate method
 *
 * @access public
 * @return void
 */
	function testInputDate() {
		
		$tagName = 'Bar.foo';
		$options = array(	'label' 	=> 'MyLabel',
						 	'type' 		=> 'date');
		$result = $this->appForm->input($tagName, $options);
		$expected = array(
			'div' => array('class' => 'input text'),
			'label' => array('for' => 'BarFoo'),
			'MyLabel',
   			'/label',
			'input' => array('name' => 'data[Bar][foo]', 'type' => 'text', 'value' => '', 'class' => 'fecha', 'id' => 'BarFoo'),
			'a' => array('href' => 'javascript:NewCal(\'BarFoo\', \'dd/mm/yyyy\')', 'id' => 'BarFoo'),
			'img' => array('src' => 'img/calendar.gif', 'class' => 'fecha', 'alt' => 'PickDate', 'title' => 'Pick date'),
			'/a',
			'/div'
		);
		$this->assertTags($result, $expected);
	}
	
	
/**
 * testImage method
 *
 * @access public
 * @return void
 */
	function testImage() {
		
		$result = $this->appForm->image('non_existent_image.gif');
 		$expected = array(
			'img' => array('src' => 'preg:/img\/no_image\.gif/', 'alt' => 'no_image', 'title' => 'Non existing image')
		);
		$this->assertTags($result, $expected);
		
		$result = $this->appForm->image('cake.icon.gif', array('alt' => 'test'));
 		$expected = array(
			'img' => array('src' => 'preg:/img\/cake\.icon\.gif/', 'alt' => 'test', 'title' => 'Test')
		);
		$this->assertTags($result, $expected);
		
		$result = $this->appForm->image('cake.icon.gif', array('title' => 'Test'));
 		$expected = array(
			'img' => array('src' => 'preg:/img\/cake\.icon\.gif/', 'alt' => 'Test', 'title' => 'Test')
		);
		$this->assertTags($result, $expected);
    }
	

/**
 * Metodo que se ejecuta despues de cada test.
 *
 * @access public
 */
	function endTest() {
		unset($this->appForm);
		ClassRegistry::flush();
	}
	
}


?>