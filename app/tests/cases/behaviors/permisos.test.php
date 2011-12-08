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
require_once(APP . "tests" . DS . "cases" . DS . "models" . DS . "usuario.test.php");

//if (!class_exists('CakeSession')) {
//	App::import('Core', 'Session');
//}
App::import('Component', 'Session');
//App::import("Model", array('Usuario'));

/**
 * Caso de prueba para el Behavior Permisos.
 *
 * @package app.tests
 * @subpackage app.tests.cases.behaviors
 */
class PermisosTestCase extends CakeTestCase {

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
	var $fixtures = array('fake_test', 'usuario', 'rol', 'grupo', 'roles_usuario', 'grupos_usuario', 'grupos_parametro', 'menu', 'roles_menu', 'roles_accion', 'accion', 'controlador', 'preferencia', 'preferencias_usuario', 'preferencias_valor');


/**
 * setUp method
 *
 * @access public
 * @return void
 */
	function setUp() {
		$this->Session = &new SessionComponent();
		$this->Session->destroy();
	}
	
/**
 * Metodo que se ejecuta al comenzar el caso de prueba.
 *
 * @access public
 */
	function startCase() {
		$this->model =& new FakeTest();

		/**
		* Asocio el behavior que debo utilizar.
		*/
		$this->model->Behaviors->attach('Permisos');
	}

	
	
/**
* Simula un login como administrador.
*/
	function __login($usuario, $clave, $return = false) { 
		$Usuario = new UsuarioTest();
		$condiciones['nombre'] = $usuario;
		$condiciones['clave'] = $clave;
		$usuario = $Usuario->verificarLogin($condiciones);
		if ($return === true) {
			return $usuario;
		}
		else {
			$this->Session->write('__Usuario', $usuario);
		}
	}	

	
	
/**
 * Pruebo la escritura.
 *
 * @access public
 */
	function testEscrituraYEliminacion() {

		$usuario = $this->__login('usuario2', 'x', true);
		
		$registrosBase = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 1,
					'string_field' 		=> 'String',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00')));
		
		
		/**
		* Compruebo la eliminacion para los Otros.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '1',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '1'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => false, 'delete' => true))));
		$this->assertEqual($result, $expected);
				
		
		/**
		* Compruebo la escritura y eliminacion para los Otros.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '1',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '3'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => true))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura y eliminacion para los Otros siendo el Usurio dueno tambien.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '2',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '3'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => false, 'delete' => false))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura para los Otros.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '1',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '2'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => false))));
		$this->assertEqual($result, $expected);
		
		/**
		* Compruebo la escritura y Eliminacion para los Otros.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '1',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '3'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => true))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura y Eliminacion para el Grupo de otro Rol.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '2',
								  	'role_id' 		=> '1',
								 	'group_id' 		=> '1',
								 	'permissions' 	=> '24'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => false, 'delete' => false))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura y Eliminacion para el Grupo.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '2',
								  	'role_id' 		=> '2',
								 	'group_id' 		=> '1',
								 	'permissions' 	=> '24'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => true))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura y Eliminacion para el Dueno.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '2',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '448'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => true))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura para el Dueno.
		*/
		$registros = array(array('FakeTest' =>	
				array_merge($registrosBase[0]['FakeTest'], 
							array(	'user_id' 		=> '2',
								  	'role_id' 		=> '0',
								 	'group_id' 		=> '0',
								 	'permissions' 	=> '384'))));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => false))));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Compruebo la escritura para el Dueno.
		*/
		$registros = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 11,
					'string_field' 		=> 'Escritura Owner',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '2',
					'role_id' 			=> '0',
					'group_id' 			=> '0',
					'permissions' 		=> '128')));
		$result = $this->model->colocarPermisos($registros, $usuario);
		$expected = array(array('FakeTest' =>	
				array_merge($registros[0]['FakeTest'], array('write' => true, 'delete' => false))));
		$this->assertEqual($result, $expected);
	}
	
	
/**
 * Pruebo la lectura.
 *
 * @access public
 */
	function testLectura() {
		
		$this->__login('usuario2', 'x');
		
		
		/**
		* Un usario con permisos de lectura para dos registros, pero prefiere ver solo los de uno grupo determinado.
		*/
		$usuario = $this->Session->read('__Usuario');
		$usuario['Usuario']['preferencias']['grupos_seleccionados'] = 2;
		$this->Session->write('__Usuario', $usuario);
		
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>array(6, 10))));
		$expected = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 10,
					'string_field' 		=> 'Lectura Group/Role',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '2',
					'role_id' 			=> '6',
					'group_id' 			=> '3',
					'permissions' 		=> '32',
					'write' 			=> false,
					'delete' 			=> false)));
		$this->assertEqual($result, $expected);
		

		/**
		* Un usario con solo permisos de lectura para el registro.
		*/
		$usuario = $this->Session->read('__Usuario');
		$usuario['Usuario']['preferencias']['grupos_seleccionados'] = 0;
		$this->Session->write('__Usuario', $usuario);
		
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>5)));
		$expected = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 5,
					'string_field' 		=> 'Lectura Owner',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '2',
					'role_id' 			=> '0',
					'group_id' 			=> '0',
					'permissions' 		=> '256',
					'write' 			=> false,
					'delete' 			=> false)));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Los test que hago desde aca en adelante, los resuelvo solo con todos los grupos del usuario. Ignoro las preferencias.
		*/
		$usuario = $this->Session->read('__Usuario');
		unset($usuario['Usuario']['preferencias']);
		$this->Session->write('__Usuario', $usuario);
		
		
		/**
		* Los Otros con solo permisos de lectura para el registro.
		*/
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>9)));
		$expected = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 9,
					'string_field' 		=> 'Lectura Others',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '0',
					'role_id' 			=> '0',
					'group_id' 			=> '0',
					'permissions' 		=> '4',
					'write' 			=> false,
					'delete' 			=> false)));
		$this->assertEqual($result, $expected);
		
		/**
		* Un Rol con solo permisos de lectura para el registro pero sin permiso para el Grupo.
		*/
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>8)));
		$this->assertEqual($result, array());
		
		/**
		* Un Grupo con solo permisos de lectura para el registro pero sin permiso para el Rol.
		*/
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>7)));
		$this->assertEqual($result, array());
		
		
		/**
		* Un Grupo/Rol con solo permisos de lectura para el registro.
		*/
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>array(6, 10))));
		$expected = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 6,
					'string_field' 		=> 'Lectura Group/Role',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '2',
					'role_id' 			=> '2',
					'group_id' 			=> '1',
					'permissions' 		=> '32',
					'write' 			=> false,
					'delete' 			=> false)),
			array('FakeTest' =>		 
				array(
					'id' 				=> 10,
					'string_field' 		=> 'Lectura Group/Role',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '2',
					'role_id' 			=> '6',
					'group_id' 			=> '3',
					'permissions' 		=> '32',
					'write' 			=> false,
					'delete' 			=> false)));
		$this->assertEqual($result, $expected);
		
		
		/**
		* Un usario sin permisos de lectura para el registro.
		*/
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>4)));
		$this->assertEqual($result, array());
		
	}

	
/**
 * Pruebo la lectura.
 *
 * @access public
 */
	function testRoot() {
		
		/**
		* El root.
		*/
		$this->__login('root', 'x');
		$result = $this->model->find('all', array('conditions'=>array('FakeTest.id'=>4)));
		$expected = array(
			array('FakeTest' =>	
				array(
					'id' 				=> 4,
					'string_field' 		=> 'Sin Permisos',
					'test_field' 		=> '',
					'integer_field' 	=> '0',
					'decimal_field' 	=> '0.000',
					'date_field' 		=> '0000-00-00',
					'date_time_field'	=> '0000-00-00 00:00:00',
					'created' 			=> '0000-00-00 00:00:00',
					'modified' 			=> '0000-00-00 00:00:00',
					'user_id' 			=> '2',
					'role_id' 			=> '0',
					'group_id' 			=> '0',
					'permissions' 		=> '0',
					'write' 			=> true,
					'delete' 			=> true)));
		$this->assertEqual($result, $expected);	
	}
	
}

?>