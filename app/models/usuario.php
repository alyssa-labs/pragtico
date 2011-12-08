<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los usuarios.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1336 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-31 12:19:35 -0300 (lun 31 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los usuarios.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Usuario extends AppModel { 

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
    
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar un nombre de usuario.')
        ),
        'clave' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar la clave del usuario.')
        ),
        'email' => array(
			array(
				'rule'		=> VALID_EMAIL, 
				'message'	=> 'El correo electronico ingresado no es valido.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el correo electronico del usuario.')
        ),
        'clave_anterior' => array(
			array(
				'rule'	=> '__clave_actual', 
				'message'	=> 'La clave actual ingresada no es correcta.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar su clave actual.')
		),
        'clave_nueva' => array(
			array(
				'rule'	=> '__clave_nueva', 
				'message'	=> 'La nueva clave y el reingreso no coinciden.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'La nueva clave no puede quedar vacia.')
        ),
        'clave_nueva_reingreso' => array(
			array(
				'rule'	=> '__clave_nueva', 
				'message'	=> 'La nueva clave y el reingreso no coinciden.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe reingresar la clave.')
        ),
        'grupo_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el grupo primario.')
        ));
	
	var $hasAndBelongsToMany = array('Grupo' =>
							array('with' => 'GruposUsuario'),
									'Rol' =>
							array('with' => 'RolesUsuario'),
									'Preferencia' =>
							array('with' => 'PreferenciasUsuario'));


/**
 * Before saving encrypt password.
 */
	function beforeSave($options = array()) {
		if (empty($this->data['Usuario']['id'])) {
			$this->data['Usuario']['clave'] = md5($this->data['Usuario']['clave']);
		} elseif (!empty($this->data['Usuario']['clave_nueva'])) {
			$this->data['Usuario']['clave'] = md5($this->data['Usuario']['clave_nueva']);
		}
		return parent::beforeSave($options);
	}


/**
 * Busca los menus (padres e hijos) a los que puede acceder el usuario segun su rol.
 *
 * @param array $usuario Los datos del usuario de la forma en que estan guardados en la session.
 * @return array $MenuItems Los menus con sus hijos ordenados por el campo orden.
 * @access public
 */
	function traerMenus($usuario) {
		/**
		* Para el rol administradores, no verifico nada, traigo siempre todos los menus.
		*/
		$MenuItems = array();
		if ((int)$usuario['Usuario']['roles'] & 1) {
			$MenuItems = $this->RolesUsuario->Rol->RolesMenu->Menu->find('threaded', array(
				'checkSecurity' => false,
				'recursive'		=> -1,
				'order' 		=> 'Menu.orden'));
		} else {

			$dbo = $this->getDataSource();
			$sql = $dbo->buildStatement(array(
				'fields'		=> array('Menu.id'),
				'table' 		=> 'menus',
				'alias' 		=> 'Menu',
				'conditions'	=> array('Menu.estado' => 'Activo'),
				'limit' 		=> null,
				'offset' 		=> null,
				'order' 		=> null,
				'group' 		=> null,
				'joins' 		=> array(
					array(
						'table' 		=> 'roles_menus',
						'alias' 		=> 'RolesMenu',
						'type' 			=> 'INNER',
						'conditions' 	=> array(
							array(	'RolesMenu.menu_id' => DboSource::identifier('Menu.id'),
									'RolesMenu.estado' 	=> 'Activo'))),
					array(
						'table' 		=> 'roles',
						'alias' 		=> 'Rol',
						'type' 			=> 'INNER',
						'conditions' 	=> array(
							array(	'RolesMenu.rol_id' 	=> DboSource::identifier('Rol.id'),
									'Rol.estado' 		=> 'Activo',
									'Rol.id'			=> Set::extract('/Rol/id', $usuario))))
				)), $this);
			$menus = $this->query($sql);

			/**
			* Para entrar usando findAllThreaded debo conocer los ids porque no hace joins, entonces los busco.
			*/
			if (!empty($menus)) {
				$MenuItems = $this->RolesUsuario->Rol->RolesMenu->Menu->find('threaded', array(
					'checkSecurity'	=> false,
					'recursive'		=> -1,
					'conditions'	=> array('Menu.id' => Set::extract('/Menu/id', $menus)),
					'order'			=> 'Menu.orden'));
			}
		}
		return $MenuItems;
	}


/**
 * Verifica si dados un usuario y una clave, se permite el acceso a al sistema.
 * Tiene en cuenta posibles SQLInjections.
 * En caso de tratarse de un juego de credenciales validadas se retorna un array con los datos del usuario, sus grupos
 * roles y preferencias.
 *
 * Este array y su forma es muy imporante porque se guardara en la session y todos los demas procesos consultaran por este.
 *
 * @param array $condiciones Se debe especificar necesariamente:
 *				- $condiciones['nombre']
 *				- $condiciones['clave']
 * @return mixed $usuario Un array con los datos del Usuario autenticado en el sistema, false en caso de no haberse
 * validado el par de credenciales.
 * @access public
 */
	function verificarLogin($condiciones) {
		if (!empty($condiciones['nombre']) && !empty($condiciones['clave'])) {
			App::import('Core', 'Sanitize');
			$conditions['Usuario.nombre'] = Sanitize::paranoid($condiciones['nombre']);
			$conditions['Usuario.clave'] = Security::hash(Sanitize::paranoid($condiciones['clave']), 'md5', false);
			$conditions['Usuario.estado'] = 'Activo';

			$usuario = $this->find('first', array(
				'checkSecurity' => false,
				'conditions'	=> $conditions,
				'contain'		=>
					array('Grupo'=>
						array('conditions'=>
							array(	'GruposUsuario.estado' => 'Activo',
									'Grupo.estado'         => 'Activo')),
						'Rol'=>
						array('order'       => 'Rol.prioridad',
                              'conditions'  =>
							array(	'RolesUsuario.estado'  => 'Activo',
									'Rol.estado'           => 'Activo')))));

			if (!empty($usuario) && $this->__actualizarUltimoIngreso($usuario['Usuario']['id'])) {
				$usuario['Usuario']['roles'] = array_sum(Set::extract($usuario, '/Rol/id'));
				$usuario['Usuario']['grupos'] = array_sum(Set::extract($usuario, '/Grupo/id'));
                $usuario['Usuario']['lower_role'] = array_sum(Set::extract($usuario, '/Rol[:last]/id'));
                $usuario['Usuario']['higher_role'] = array_sum(Set::extract($usuario, '/Rol[:first]/id'));
				$usuario['Usuario']['preferencias'] = $this->Preferencia->findPreferencias($usuario['Usuario']['id']);

				if (!isset($usuario['Grupo'][0]['id'])) {
					$usuario['Usuario']['preferencias']['grupo_default_id'] = 0;
				} else {
                    if (!empty($condiciones['selectedGroup'])) {
                        $usuario['Usuario']['preferencias']['grupo_default_id'] = $condiciones['selectedGroup'];
                    } else {
					   $usuario['Usuario']['preferencias']['grupo_default_id'] = $usuario['Grupo'][0]['id'];
                    }
				}
				return $usuario;
			}
		}
		return false;
	}


/**
 * Actualiza la fecha y hora del ultimo ingreso correcto del usuario al sistema.
 *
 * @param integer $usuarioId El identificador del usuario.
 * @return boolean True si su puedo actualizar, false en caso contrario.
 * @access private
 */
	function __actualizarUltimoIngreso($usuarioId) {
		return $this->save(array('Usuario'=>array('ultimo_ingreso'=>date('Y-m-d H:i:s'), 'id'=>$usuarioId)));
	}

	
/**
 * Valida que la clave actual ingresada, sea efectivamente la clave actual correcta.
 *
 * @param array $valores Los valores ingresados a validarse.
 * @param array $params Algun parametro adicional que pueda necesitar (no se utiliza de momento).
 * @return boolean
 * @access private
 */
	function __clave_actual($valores, $params=array()) {
		$session = &new SessionComponent();
		$usuario = $session->read('__Usuario');
		if ($usuario['Usuario']['clave'] === md5($valores['clave_anterior'])) {
			return true;
		} else {
			return false;
		}
	}

	
/**
 * Valida que la nueva clave y su reingreso coincidan.
 *
 * @param array $valores Los valores ingresados a validarse.
 * @param array $params Algun parametro adicional que pueda necesitar (no se utiliza de momento).
 * @return boolean
 * @access private
 */
	function __clave_nueva($valores, $params=array()) {
		if (key($valores) === 'clave_nueva') {
			$otra = $this->data['Usuario']['clave_nueva_reingreso'];
		} elseif (key($valores) === 'clave_nueva_reingreso') {
			$otra = $this->data['Usuario']['clave_nueva'];
		}
		if ($otra == $valores[key($valores)]) {
			return true;
		} else {
			return false;
		}
	}
} 
?>