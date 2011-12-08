<?php
/**
 * Behavior que contiene el manejo de permisos a nivel registros (row level).
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models.behaviors
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1261 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-04-07 12:36:09 -0300 (mié 07 de abr de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Especifico todos los metodos que me garantizan que de manera automagica cada registro que es recuperado o
 * guardado, siempre contendra el usuario y el grupo correcto, como asi tambien los permisos.
 *
 * Me baso en la idea expuesta por:
 * http://www.xaprb.com/blog/2006/08/16/how-to-build-role-based-access-control-in-sql/
 *
 * @package     pragtico
 * @subpackage  app.models.behaviors
 */
class PermisosBehavior extends ModelBehavior {


/**
 * Numeric equivalents to owner, group (or role) and others permissions.
 *
 * @var array
 * @access private
 */
	private $__permissions = array(
		'owner_read'   => 256,
		'owner_write'  => 128,
		'owner_delete' => 64,
		'group_read'   => 32,
		'group_write'  => 16,
		'group_delete' => 8,
	   	'other_read'   => 4,
	   	'other_write'  => 2,
	   	'other_delete' => 1
	);

    private $__access = array('read', 'readOwnerOnly', 'write', 'delete');

/**
 * Numeric equivalents to owner, group or role and others permissions 
 * assuming that who can write, also can read and who can delete, also can read.
 *
 * @var array
 * @access private
 */
	private $__simplifiedPermissions = array(
		'owner_read'   => 256,
		'owner_write'  => 384,
		'owner_delete' => 320,
		'group_read'   => 32,
		'group_write'  => 48,
		'group_delete' => 40,
	   	'other_read'   => 4,
	   	'other_write'  => 6,
	   	'other_delete' => 5
	);
	
	
/**
 * Mantendre el component session de cakePHP.
 *
 * @var array
 * @access public
 */
	//public $Session = null;
/**
 * Current loggedin User.
 *
 * @var array
 * @access private
 */
    private $__currentUser = null;
	

    function __getCurrentUser() {
        if (empty($this->__currentUser)) {
            $Session = &new SessionComponent();
            $this->__currentUser = $Session->read('__Usuario');
        }
        return $this->__currentUser;
    }


    function setup(&$Model, $settings = array()) {
        if (!isset($Model->permissions)) {
            $Model->permissions['permissions'] = 496;
        }
    }
    
/**
 * Sets permissions.
 *
 * @param object $Model.
 * @param integer $permissions The permissions to be used to save record.
 * @return boolean True if valid permission could be saved, false otherwise.
 * @access public
 */
    function setPermissions(&$Model, $permissions) {
        if (is_numeric($permissions) && $permissions >= 0 && $permissions <= 511) {
            $Model->permissions['permissions'] = $permissions;
            return true;
        }
        return false;
    }


/**
 * Returns permissions to be used to save the records.
 *
 * @return integer Permissions numeric value used to save records for this model.
 * @access public
 */
    function getPermissions(&$Model) {
        return $Model->permissions['permissions'];
    }    


/**
 * Returns role to be used to save the record.
 *
 * @return integer Role or sum of role ids (bitwise).
 * @access public
 */
    function getRole(&$Model) {
        if (isset($Model->permissions['role'])) {
            if ($Model->permissions['role'] === 'higher') {
                return User::get('/Usuario/higher_role');
            } elseif ($Model->permissions['role'] === 'lower') {
                return User::get('/Usuario/lower_role');
            } elseif ($Model->permissions['role'] === 'all') {
                return User::get('/Usuario/roles');
            } elseif ($Model->permissions['role'] === 'none' || $Model->permissions['role'] === false) {
                return 0;
            } else {
                trigger_error(__('Role option not supported.', true), E_USER_WARNING);
            }
        } else {
            return User::get('/Usuario/roles');
        }
    }


/**
 * Returns group to be used to save the record.
 *
 * @return integer Group or sum of groups ids (bitwise).
 * @access public
 */
    function getGroup($Model) {
        if (isset($Model->permissions['group'])) {
            if ($Model->permissions['group'] === 'all') {
                return User::get('/Usuario/grupos');
            } elseif ($Model->permissions['group'] === 'default') {
                return User::get('/Usuario/preferencias/grupo_default_id');
            } elseif ($Model->permissions['group'] === 'none' || $Model->permissions['group'] === false) {
                return 0;
            } else {
                trigger_error(__('Group option not supported.', true), E_USER_WARNING);
            }
        } else {
            return User::get('/Usuario/grupos');
        }
    }
    
    
/**
 * Returns user to be used to save the record.
 *
 * @return integer User id.
 * @access public
 */
    function getUser() {
        return User::get('/Usuario/id');
    }
    
/**
 * Before save callback
 * Set default user_id, group_id, role_id and permissions when creating a new record.
 *
 * @return boolean True if can continue, false otherwise.
 * @access public.
 */    
    function beforeSave(&$Model) {
		
    	if (empty($Model->id)) {
			if (!isset($Model->data[$Model->name]['user_id'])) {
    			$Model->data[$Model->name]['user_id'] = $this->getUser();
    		}
    		if (!isset($Model->data[$Model->name]['role_id'])) {
    			$Model->data[$Model->name]['role_id'] = $this->getRole($Model);
    		}
    		if (!isset($Model->data[$Model->name]['group_id'])) {
    			$Model->data[$Model->name]['group_id'] = $this->getGroup($Model);
    		}
    		if (!isset($Model->data[$Model->name]['permissions'])) {
    			$Model->data[$Model->name]['permissions'] = $this->getPermissions($Model);
    		}
    	}
    	return true;
    }


/**
 * Una vez que haya realizado una busqueda, a cada registro le agrego dos nuevos campos que
 * con una bandera booleana me indican si puedo escribir y/o borrar.
 *
 * @param object $model Model que usa este behavior.
 * @param array $results Los resultados que retorno alguna query.
 * @param boolean $primary Indica si este resultado viene de una query principal o de una query que
 *						   es generada por otra (recursive > 1)
 * @return array array $results Los resultados con los campos de permisos ya agregados a cada registro.
 * @access public
 */	
	function afterFind(&$model, $results, $primary = false) {
		
		$usuario = $this->__getCurrentUser();
		
		/**
		* Pueden existir casos, por ejemplo, cuando aun no tengo un usuario logueado y hago queries a la 
		* base para buscar un par de usuario/clave valido, odnde no tenga el usuario en la sesion.
		*/
		if (!empty($usuario)) {
			$results = $this->__colocarPermisos($results, $usuario);
		}
		
		return $results;
	}


/**
 * Una vez que recupero los datos, recorro el array y agrego los permisos (delete o write).
 *
 * @param object $model Model que usa este behavior.
 * @param array $results El array con datos recuperados desde una query (find).
 * @param array $usuario Array que ocntiene la informacion del usuario y grupo/s del usuario logueado.
 * @return array El array con los resultados de la query con los campos de permisos ya agregados a cada registro.
 * @access public
 */	 
	function colocarPermisos(&$model, $results, $usuario) {
		return $this->__colocarPermisos($results, $usuario);
	}
	
	
/**
 * Una vez que recupero los datos, recorro el array y agrego los permisos (delete o write).
 *
 * @param array $results El array con datos recuperados desde una query (find).
 * @param array $usuario Array que ocntiene la informacion del usuario y grupo/s del usuario logueado.
 * @return array El array con los resultados de la query con los campos de permisos ya agregados a cada registro.
 * @access private
 */	 
	function __colocarPermisos($results, $usuario) {
		
		foreach ($results as $k=>$v) {
			if (is_array($v)) {
				$results[$k] = $this->__colocarPermisos($v, $usuario);
			}
		}
		
		if (isset($results['user_id']) && isset($results['group_id']) && isset($results['permissions'])) {
			return array_merge($results, array(	'write'	=> $this->__puede($usuario, $results, 'write'),
												'delete'=> $this->__puede($usuario, $results, 'delete')));
		} else {
			return $results;
		}
	}
	
	
/**
 * Esta funcion indica si un usuario puede o no realizar un acceso sobre un registro dependiendo del
 * dueno, el grupo y rol y los demas (otros).
 *
 * Si se trata del user_id == 1 (el root) permito todo.
 * Si se trata del dueno del registro, verifico en funcion del los permisos del dueno y
 * retorno los permisos que correspondan al dueno.
 * Si el usuario forma parte de uno de los grupos del registro y al mismo tiempo forma parte de uno de los roles del
 * registo, retorno los permisos que correspondan al grupo.
 * En los otros casos donde el usuario no es ni el root, ni el dueno ni forma parte concurrentemente del grupo y rol
 * del registro, retorno los permisos correspondiente a los otros.
 */ 
	function __puede($usuario, $registro, $acceso) {

        /** If root user, can do everything */
		if ((int)$usuario['Usuario']['id'] === 1) {
			return true;
		}
		
		/** Check for owner permissions */
		if (($usuario['Usuario']['id'] === $registro['user_id'])
			&& ((int)$registro['permissions'] & (int)$this->__permissions['owner_' . $acceso])) {
			return true;
		}

        /** Check for group permissions */
		if ((((int)$usuario['Usuario']['grupos'] & (int)$registro['group_id'])
			&& ((int)$registro['permissions'] & (int)$this->__permissions['group_' . $acceso]))) {
			return true;
		}

        /** Check for role permissions */
        if (((int)$usuario['Usuario']['roles'] & (int)$registro['role_id'])
            && ((int)$registro['permissions'] & (int)$this->__permissions['group_' . $acceso])) {
            return true;
        }
        
        /** Check for other permissions */
		if ($usuario['Usuario']['id'] !== $registro['user_id'] &&
			((int)$usuario['Usuario']['grupos'] & (int)$registro['group_id'] === 0) &&
			((int)$registro['permissions'] & (int)$this->__permissions['other_' . $acceso])) {
			return true;
		}

		return false;
	}

	
	function setSecurityAccess(&$model, $access, $primaryModelOnly = true) {
		if (in_array($access, $this->__access)) {
			
			/** Assign same security access to related models */
            if ($primaryModelOnly === false) {
                foreach (array_merge($model->hasMany, $model->hasOne) as $assoc => $data) {
                    $model->{$assoc}->access = $access;
                }
            }
			$model->access = $access;
			
		} else {
			trigger_error(__('Security access method not supported. Please use one of this: "read", "readOwnerOnly", "write" or "delete"', true), E_USER_ERROR);
		}
	}
	

	function getSecurityAccess(&$Model) {
		if (!empty($Model->access)) {
			return $Model->access;
		}
		return 'read';
	}
	
	
/**
 * Antes de realizar cualquier busqueda, agrega las condiciones correspondientes a los permisos de cada usuario.
 *
 * La unica posibilidad de que este metodo no agregue las condiciones de seguridad, es que explicitamente vengan
 * seteadas del codigo del programa alguna de estas condiciones:
 * 					- $queryData['conditions']['checkSecurity'] = false;
 * 					- $queryData['checkSecurity'] = false;
 *
 * @param object $model Model que usa este behavior.
 * @param array $queryData Data utilizada para ejecutar la query, ej: conditions, order, group, etc.
 * @return array $queryData Data utilizada para ejecutar la query con las condiciones modificadas.
 * @access public
 */
	function beforeFind(&$model, $queryData) {

		if (isset($queryData['checkSecurity'])) {
			$securityAccess = $queryData['checkSecurity'];
			unset($queryData['checkSecurity']);
		} elseif (!empty($model->access)) {
			$securityAccess = $model->access;
		} else {
			$securityAccess = 'read';
		}

		/**
		* La unica posibilidad de no chequear la seguridad, es que me venga explicitamente especificado no hacerlo.
		*/
		if ($securityAccess === false) {
			unset($queryData['conditions']['checkSecurity']);
			return $queryData;
		}

		/**
		* Verifico que se trate de alguno de los unicos 3 metodos soportados.
		*/
		if (in_array($securityAccess, $this->__access)) {
			$seguridad = $this->__generarCondicionSeguridad($model, $securityAccess);
		} else {
			trigger_error(__('Security access method not supported. Please use one of this: "read", "readOwnerOnly", "write" or "delete"', true), E_USER_ERROR);
		}

		if (!empty($seguridad)) {
			if (!empty($queryData['conditions']) && is_array($queryData['conditions'])) {
				if (!empty($queryData['conditions']['OR'])) {
					$queryData['conditions']['AND']['OR'] = $seguridad['OR'];
				} else {
					$queryData['conditions'] = array_merge($queryData['conditions'], $seguridad);
				}
			} else {
				$queryData['conditions'] = $seguridad;
			}
		}
		
		return $queryData;
	}

	
/**
 * Genera las condiciones de seguridad.
 *
 * @param string $acceso Tipo de acceso que se desea realizar. Solo hay tres tipos permitidos:
 *						- read
 *						- write
 *						- delete
 * @param string $modelName Nombre del model.
 * @return array Vacio si se trata de un usuario cuyo grupo primario sea el grupo de administradores donde
 * no se chequea seguridad. Array con las condiciones en cualquier otro caso.
 * @access private
 */
	function __generarCondicionSeguridad(&$Model, $acceso) {
		
		$usuario = $this->__getCurrentUser();
		
		$usuarioId = $usuario['Usuario']['id'];
		
		/**
		* si tiene seteadas las preferencias de los grupos_seleccionados, es porque el usuario quiere trabajar
		* con alguno/s de su/s grupo/s, y no con todos.
		*/
		if (isset($usuario['Usuario']['preferencias']['grupos_seleccionados'])) {
			$grupos = $usuario['Usuario']['preferencias']['grupos_seleccionados'];
		}
		else {
			$grupos = $usuario['Usuario']['grupos'];
		}
		$roles = $usuario['Usuario']['roles'];
		

        /*
		if ($acceso === 'delete') {
			$resultPermissions = $this->__simplifiedPermissions;
		} else {
			$resultPermissions = $this->__permissions;
		}
        */
        $resultPermissions = $this->__simplifiedPermissions;
		/**
		* Si se trata de un usuario perteneciente al rol administradores, que no tiene grupo (root), no verifico permisos.
		*/
        //return array();
		if (empty($usuario['Grupo']) && (int)$usuario['Usuario']['roles'] & 1) {
			return array();
		} else {
			/**
			* Si explicitamente no ha seleccionado ningun grupo, supongo que desea ver solo sus registros...
			* Los registros de los cuales el es dueno.
			*/
            if (empty($grupos) || $acceso !== 'read') {
                $acceso = str_replace('readOwnerOnly', 'read', $acceso);
				$seguridad['OR'][] =
					array(
						$Model->name . '.user_id' => $usuarioId,
						'(' . $Model->name . '.permissions) & ' . $this->__simplifiedPermissions['owner_' . $acceso] => $resultPermissions['owner_' . $acceso]
					);
			} else {
				$seguridad['OR'][] =
					array('AND' => array(
						array(
							$Model->name . '.user_id' => $usuarioId,
							'(' . $Model->name . '.permissions) & ' . $this->__simplifiedPermissions['owner_' . $acceso] => $resultPermissions['owner_' . $acceso]
						),
						array(
							'(' . $Model->name . '.group_id) & ' . $grupos => $grupos,
							'(' . $Model->name . '.permissions) & ' . $this->__simplifiedPermissions['group_' . $acceso] => $resultPermissions['group_' . $acceso]
						)
					));
			}

            /** Check for group and role permissions together. Means than to be true, must have permissions both, group and role */
			$seguridad['OR'][] =
				array('AND' => array(
					array(
						'(' . $Model->name . '.role_id) & ' . $roles . ' >=' => $Model->name . '.role_id',
						'(' . $Model->name . '.permissions) & ' . $this->__simplifiedPermissions['group_' . $acceso] => $resultPermissions['group_' . $acceso]
					),
                    array('OR' =>
                        array('AND' => array(
                            '(' . $Model->name . '.group_id) & ' . $grupos . ' >' => $Model->name . '.group_id',
                            '(' . $Model->name . '.permissions) & ' . $this->__simplifiedPermissions['group_' . $acceso] => $resultPermissions['group_' . $acceso]
                        ), $Model->name . '.group_id' => 0),
                    )
				));

            /** Check for others permissions */
			$seguridad['OR'][] =
				array(
					'(' . $Model->name . '.permissions) & ' . $this->__simplifiedPermissions['other_' . $acceso] => $resultPermissions['other_' . $acceso]
				);
		}

		/**
		* Cuando hago un delete, con los permisos solo del dueno es suficiente, por lo que los del grupo los quito.
		*/
		if ($acceso === 'delete') {
			unset($seguridad['OR'][0]['AND'][1]);
		}
		//return array();
        //d($seguridad);
		return $seguridad;
	}


/**
 * After save callback
 * Dejo un log en la auditoria del registro creado o modificado.
 *
 * @param boolen $created Indica si se trata de un nuevo registro (add) o una modificacion (update).
 * @return void.
 * @access public.
 */    
	function xafterSave(&$model, $created) {
		/**
		* Evito que entre en loop infinito.
		*/
		if ($model->name !== 'Auditoria') {
			//App::import('model', 'Auditoria');
			//$Auditoria = new Auditoria();
			$Auditoria = ClassRegistry::init('Auditoria');
			$save['model'] = $model->name;
			$save['data'] = $model->data;
			if ($created) {
				$save['tipo'] = 'Alta';
			} else {
				$save['tipo'] = 'Modificacion';
			}
			$Auditoria->auditar($save);
		}
		return true;
	}


/**
 * After delete callback
 * Dejo un log en la auditoria del registro eliminado.
 *
 * @return void.
 * @access public.
 */
	function xafterDelete(&$model) {
		//App::import('model', 'Auditoria');
		//$Auditoria = new Auditoria();
		$Auditoria = ClassRegistry::init('Auditoria');
		$save['model'] = $model->name;
		$save['data'] = array($model->name => array($model->primaryKey => $model->id));
		$save['tipo'] = 'Baja';
		$Auditoria->auditar($save);
	}
	
}
?>