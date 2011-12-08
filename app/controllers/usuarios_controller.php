<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al manejo de usuarios.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1255 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-04-05 12:11:01 -0300 (lun 05 de abr de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al manejo de usuarios.
 *
 * @package	    pragtico
 * @subpackage  app.controllers
 */
class UsuariosController extends AppController { 


    var $paginate = array(
        'order' => array(
            'Usuario.nombre' => 'asc'
        )
    );


	function add() {
		
		if (!empty($this->data['GruposUsuario']['grupo_id'])) {
			foreach ($this->data['GruposUsuario']['grupo_id'] as $v) {
				$grupos[] = array('grupo_id' => $v);
			}
			$this->data['GruposUsuario'] = $grupos;
			$this->Usuario->bindModel(
				array(
					'hasMany' => array(
						'GruposUsuario'=> array('className'    => 'GruposUsuario',
												'foreignKey'   => 'usuario_id')
			)));
			$this->Usuario->GruposUsuario->unique = array('grupo_id', 'usuario_id');
		}
		
		if (!empty($this->data['RolesUsuario']['rol_id'])) {
			foreach ($this->data['RolesUsuario']['rol_id'] as $v) {
				$roles[] = array('rol_id' => $v);
			}
			$this->data['RolesUsuario'] = $roles;
			$this->Usuario->bindModel(
				array(
					'hasMany' => array(
						'RolesUsuario' => array('className'    => 'RolesUsuario',
												'foreignKey'   => 'usuario_id')
			)));
			$this->Usuario->RolesUsuario->unique = array('rol_id', 'usuario_id');
		}
		
		parent::add();
	}
	
	
/**
 * Muestra via desglose los roles asociados a este Usuario.
 *
 * @param integer $id El identificador unico del registro.
 * @return void
 * @access public 
 */
	function roles($id) {
		$this->Usuario->contain(array('RolesUsuario', 'Rol'));
		$this->data = $this->Usuario->read(null, $id);
	}


/**
 * Muestra via desglose grupos a los que pertenece este usuario.
 *
 * @param integer $id El identificador unico del registro.
 * @return void
 * @access public 
 */
	function grupos($id) {
		/**
		* Como un usuario tiene un grupo primario y puede tener grupos secundarios, y la clase del model para ambos
		* casos es Grupo, el framework hace un merge del array de resultados, lo cual no es correcto, por lo que
		* para este caso (mostrar los grupos secundarios del usuario) deseteo los elementos del grupo primario.
		*/
		$this->Usuario->contain(array('Grupo'));
		$usuario = $this->Usuario->read(null, $id);
		foreach ($usuario['Grupo'] as $k=>$v) {
			if (!is_numeric($k)) {
				unset($usuario['Grupo'][$k]);
			}
			
		}
		$this->data = $usuario;
	}

	
/**
 * Permite el ingreso de un usuario al sistema.
 *
 * @return void
 * @access public 
 */
    function login($user = null, $password = null) {

        if (!empty($this->data['Usuario']['loginNombre']) && !empty($this->data['Usuario']['loginClave'])) {
            $user = $this->data['Usuario']['loginNombre'];
            $password = $this->data['Usuario']['loginClave'];
        }
        
        if (!empty($user) && !empty($password)) {
            $selectedGroup = null;
            if (!empty($this->data['Usuario']['loginGroup'])) {
                $selectedGroup = $this->data['Usuario']['loginGroup'];
            }
            $usuario = $this->Usuario->verificarLogin(array('nombre' => $user, 'clave' => $password, 'selectedGroup' => $selectedGroup));

            if (!empty($usuario)) {

                if (!empty($usuario['Grupo'])
                    && count($usuario['Grupo']) > 1
                    && empty($this->data['Usuario']['loginGroup'])) {
                    $this->set('groups', Set::combine($usuario['Grupo'], '{n}.id', '{n}.nombre'));
                } else {
                    
                    /** Guardo en la session el usuario.*/
                    $this->Session->write('__Usuario', $usuario);

                    /** Busco los menus.*/
                    $this->Session->write('__itemsMenu', $this->Usuario->traerMenus($usuario));
                    
                    if (!empty($this->data['Usuario']['loginGroup'])) {
                        $this->requestAction('grupos/setear_grupo_default/' . $this->data['Usuario']['loginGroup'] . '/true');
                    } elseif (count($usuario['Grupo']) === 1) {
                        $this->requestAction('grupos/setear_grupo_default/' . $usuario['Grupo'][0]['id'] . '/true');
                    }

                    $this->redirect(array('controller' => 'infos', 'action' => 'index'), null, true);
                }
                
            } else {
                $this->Session->setFlash(__('Login failed. Invalid username or password.', true), 'error');
                $this->redirect('login', null, true);
            }
        }
        $this->layout = 'login';
    }
    
/**
 * Permite salir del sistema a un usuario de forma segura eliminado datos de la session.
 *
 * @return void
 * @access public 
 */
    function logout() {
        $this->Session->destroy('Usuario');
        $this->redirect('login', null, true);
    } 


/**
 * Permite realizar el cambio de clave de un usuario.
 *
 * @param integer $id El identificador unico del usuario al que se le desea cambiar la clave.
 * @return void.
 * @access public 
 */
    function cambiar_clave($id = null) {
    	if (!empty($id) && is_numeric($id)) {
    		$this->set('usuario', $this->Usuario->findById($id));
    		$this->set('noVerificar', false);
    	} else if (!empty($this->data)) {
    		if (!empty($this->data['Form']['accion']) && $this->data['Form']['accion'] === 'grabar' && $this->Usuario->validates()) {
    			unset($this->data['Form']);
    			if ($this->Usuario->save($this->data)) {
    				//$this->Session->setFlash('La clave se cambio correctamente.', 'ok');
					$this->Session->setFlash('Password successfully updated.', 'ok');
					$this->History->goBack();
    			}
    		}
    	} else {
			$this->set('usuario', $this->Session->read('__Usuario'));
		}
	}

} 

?>