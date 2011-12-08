<?php
/**
 * Controller de la aplicacion.
 *
 * Todos los controllers heredan desde esta clase, por lo que defino metodos que usare en todos los controllers aca.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1359 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-11 13:41:30 -0300 (vie 11 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula lo logica de negocios comun a todo la aplicacion.
 *
 * @package     pragtico
 * @subpackage  app
 */
class AppController extends Controller {

	/**
	 * Los helpers que usara toda la aplicacion.
	 *
	 * @var array
	 * @access public
	 */
    var $helpers = array('Formato', 'AppForm', 'Paginador');

	/**
	 * Los components que usara toda la aplicacion.
	 *
	 * @var array
	 * @access public
	 */
	var $components = array('Session', 'Paginador', 'RequestHandler', 'History', 'Util');


/**
 * Me genera un array listo para cargar options de un control (combo, radio, checnbox).
 * Cuando en las options de un control uso listable, genera el array que usara para mostrar los datos al usuario.
 * Soporta todas las opciones del metodo find (conditions, fields, order, etc).
 *
 * @return array Array con los datos de la forma $key => $value.
 * @access public
 */
	function listable() {
		if ($this->RequestHandler->isAjax()) {
			$this->RequestHandler->renderAs($this, 'ajax');
		}
		
		/**
		* Puedo tener cualquier condicion de las que soporta el metodo find.
		*/
		$opcionesValidas = array('displayField', 'groupField', 'conditions', 'fields', 'order', 'limit', 'recursive', 'group', 'contain', 'model');
		$opcionesValidasArray = array('displayField', 'groupField', 'conditions', 'fields', 'order', 'contain');
		foreach ($opcionesValidas as $opcionValida) {
			if (!empty($this->params['named'][$opcionValida])) {
				if (in_array($opcionValida, $opcionesValidasArray)) {
					$condiciones[$opcionValida] = unserialize($this->params['named'][$opcionValida]);
				} else {
					$condiciones[$opcionValida] = $this->params['named'][$opcionValida];
				}
			}
		}
		if (!empty($condiciones['model'])) {
			$model = ClassRegistry::init($condiciones['model']);
			unset($condiciones['model']);
		} else {
			$model = $this->{$this->modelClass};
		}
		
		if (empty($condiciones['displayField'])) {
			$displayFields = array($model->displayField);
		} else {
			$displayFields = $condiciones['displayField'];
			unset($condiciones['displayField']);
		}

		if (!empty($condiciones['groupField'][0])) {
			$group = $condiciones['groupField'][0];
			unset($condiciones['groupField']);
		}

		foreach ($displayFields as $displayField) {
			$display[] = '{n}.' . $displayField;
			$exp[] = '%s';
		}
		array_unshift($display, implode(' - ', $exp));
		$data = $model->find('all', $condiciones);
		if (isset($group)) {
			$data = Set::combine($data, '{n}.' . $model->name . '.' . $model->primaryKey, $display, '{n}.' . $group);
		} else {
			$data = Set::combine($data, '{n}.' . $model->name . '.' . $model->primaryKey, $display);
		}
		return $data;
	}


/**
 * Index.
 */    
	function index() {
        
        $this->paginate = array_merge($this->paginate, array('limit' => 15));

		/**
         * TODO:Revisar si esto es necesario realmente
		 * Me aseguro de no perder ningun parametro que venga como un post dentro edl Formulario.
		 * Lo en vio nuevamente como un parametro.
		 */
		if (!empty($this->data['Formulario'])) {
			foreach ($this->data['Formulario'] as $k=>$v) {
				$this->params['named'][$k] = $v;
			}
		}

		/**
		* Seccion propia del index
		*/
		
		/**
		* Puede haber un modificador al comportamiento estandar setaeado en el model.
		*/
		if (isset($this->{$this->modelClass}->modificadores[$this->action]['contain'])) {
			$this->{$this->modelClass}->contain($this->{$this->modelClass}->modificadores[$this->action]['contain']);
		}

        $this->set('registros', $this->Paginador->paginar());
	}


/**
 * Add.
 */
	function add() {
		
		
		/**
		* Puede haber un modificador al comportamiento estandar setaeado en el model.
		* En este caso se refiere a establecer los valores por defecto.
		* En caso de ser funciones, por seguridad, deben validarse con la expresion regular ya que se ejecutan
		* mediante eval.
		*/
		if (isset($this->{$this->modelClass}->modificadores[$this->action]['valoresDefault'])) {
			foreach ($this->{$this->modelClass}->modificadores[$this->action]['valoresDefault'] as $campo => $valoresDefault) {
				if (is_array($valoresDefault)) {
					if (isset($valoresDefault['date'])) {
						$this->data[$this->modelClass][$campo] = date($valoresDefault['date']);
					}
				} else {
					$this->data[$this->modelClass][$campo] = $valoresDefault;
				}
			}
		}
		
		/**
		* Si hay parametros, me esta indicando que debo cargar un campo pasado como parametro.
		*/
		if (!empty($this->passedArgs)) {
			foreach ($this->passedArgs as $k => $v) {

				if (strpos($k, '.') === false) {
					continue;
				}

				list($model, $field) = explode('.', $k);
				$this->data[$model][$field] = $v;
				if (substr($field, -3) === '_id') {
					$modelAsociado = Inflector::classify(str_replace('_id', '', $field));
					
					/**
					* Doy tratamiento al tipo especial de relacion con sigo mismo.
					*/
					if ($modelAsociado === 'Parent') {
						$resultado = $this->{$model}->find('first', array('conditions' => array($model . "." . $this->{$model}->primaryKey => $v)));
					} else {
						$resultado = $this->{$model}->{$modelAsociado}->find('first', array(
							'conditions' 	=> array(
								$modelAsociado . '.' . $this->{$model}->{$modelAsociado}->primaryKey => $v
							)
						));
					}
					$this->data[$modelAsociado] = $resultado;
				} else {
					$this->data[$model][$field] = $v;
				}
			}
		}
	}


/**
 * Edit.
 * Si viene el parametro id, se refiere a un unico registro,
 * si viene seteado seleccion multiple, recupera multiple registros.
 *
 * @param integer $id El identificador unico de registro.
 * @return void.
 * @access public
 */
	function edit($id = null) {
        $this->{$this->modelClass}->Behaviors->attach('Crumbable');
                
		if (!empty($id)) {
			$ids[] = $id;
		} else {
			$ids = $this->Util->extraerIds($this->data['seleccionMultiple']);
		}

        
		if (!empty($ids)) {
			/**
			 * Puede haber un modificador al comportamiento estandar setaeado en el model.
			 */
			if (isset($this->{$this->modelClass}->modificadores[$this->action]['contain'])) {
				$this->{$this->modelClass}->contain($this->{$this->modelClass}->modificadores[$this->action]['contain']);
			}

            //$this->{$this->modelClass}->setSecurityAccess('write');
			$this->data = $this->{$this->modelClass}->find('all',
                    array('conditions' => array($this->modelClass . '.' . $this->{$this->modelClass}->primaryKey => $ids)));

            if (!empty($this->data)) {
                $this->render('add');
            } else {
                $this->Session->setFlash('El/los registro/s que desea ver/modificar no se encuentran o usted no tiene acceso a el/ellos', 'error');
                $this->History->goBack();
            }
		}
	}


/**
 * Saves data.
 *
 * @param array $data. When not empty, $data will be saved. Otherwise, $this->data will be saved.
 */		
    function save($data = array()) {

		if (!empty($data) && is_array($data)) {
			if (!empty($this->data['Form'])) {
				$this->data = array_merge($data, array('Form' => $this->data['Form']));
			} else {
				$this->data = $data;
			}
		}

        $back = 1;
        if (isset($this->data['Form']['volverAInsertar'])) {
            $this->action = 'add';
            if (!empty($this->data['Form']['volverAInsertar'])) {
                $back = 0;
            }
        } else {
            $this->action = 'edit';
        }

        if (!empty($this->data['Form']['accion'])) {
            if ($this->data['Form']['accion'] == 'duplicar') {
                unset($this->data[$this->modelClass][$this->{$this->modelClass}->primaryKey]);
                $this->data['Form']['accion'] = 'grabar';
            }

            if (substr($this->data['Form']['accion'], 0, 6) == 'grabar') {

				$params = array();
				if (substr($this->data['Form']['accion'], 0, 16) == 'grabar_continuar') {
					$params = array();
					foreach (explode('|', str_replace('grabar_continuar|', '', $this->data['Form']['accion'])) as $tmpUrl) {
						$tmp = explode(':', $tmpUrl);
						$params[$tmp[0]] = $tmp[1];
					}
				}

                $c = 0;
                /**
                * Saco lo que no tengo que grabar.
                * En form, tengo informacion que mande desde la vista.
                * En Bar es informacion temporal que necesita el control relacionado.
                */
                unset($this->data['Form']);
                unset($this->data['Bar']);


                /**
                * En base al/los errores que pueden haber determino que mensaje mostrar.
                */
                if ($this->{$this->modelClass}->appSave($this->data)) {
                    if ($this->{$this->modelClass}->savedDataLog['totalRecordsSaved'] === 1) {
                        $message = __('The record has been saved', true);
                    } else {
                        $message = sprintf(__('%s of %s records have been saved', true), $this->{$this->modelClass}->savedDataLog['totalRecordsSaved'], $this->{$this->modelClass}->savedDataLog['totalRecords']);
                    }

					if ($this->afterSave($params)) {
                    	$this->Session->setFlash($message, "ok", array("warnings"=>$this->{$this->modelClass}->getWarning()));
                    	$this->History->goBack($back);
					}
                } else {

                    /**
                     * Debo recuperar nuevamente los datos porque los necesito en los controler relacionados (Lov, relacionado).
                     * Los que ya tengo, los dejo como estaban, porque se debe a que no validaron.
                     */
                    $ids = Set::extract('/' . $this->modelClass . '/' . $this->{$this->modelClass}->primaryKey, $this->data);
                    if (!empty($ids)) {
						$data = null;
						if (!isset($this->data[0])) {
                        	$data[] = $this->data;
						} else {
							$data = $this->data;
						}
						$this->data = null;
                        
                        /**
                         * Puede haber un modificador al comportamiento estandar setaeado en el model.
                         */
                        if (isset($this->{$this->modelClass}->modificadores[$this->action]['contain'])) {
                            $this->{$this->modelClass}->contain($this->{$this->modelClass}->modificadores[$this->action]['contain']);
                        }
                        
						$this->{$this->modelClass}->Behaviors->attach('Crumbable');
                        $this->{$this->modelClass}->setSecurityAccess('write');
                        $this->data = $this->{$this->modelClass}->find('all',
                                array('conditions'=> array($this->modelClass . '.' . $this->{$this->modelClass}->primaryKey => $ids)));

                        if ($this->action === 'edit') {
                            foreach ($data as $k => $v) {
                                foreach ($v as $model => $datos) {
                                    if (isset($datos[0])) {
                                        foreach ($datos as $kDetail => $vDatail) {
                                            $this->data[$k][$model][$kDetail] = array_merge($this->data[$k][$model][$kDetail], $vDatail);
                                        }
                                    } else {
										//if (!isset($this->data[$k][$model])) {
										//	$this->data[0][$model] = array();
										//}
                                        $this->data[$k][$model] = array_merge($this->data[$k][$model], $datos);
                                    }
                                }
                            }
                        } else {
                            $this->data = $data;
                        }
                        //$this->Session->setFlash(__('The record could not be saved. Please verify errors and try again.', true), "error", array("errores"=>$dbError));
						$this->Session->setFlash(__('The record could not be saved. Please verify errors and try again.', true), 'error');
                    } else {
                        //$this->Session->setFlash(__('The record could not be saved. Please verify errors and try again.', true), "error", array("errores"=>$dbError));
						$this->Session->setFlash(__('The record could not be saved. Please verify errors and try again.', true), 'error');
                    }
                }
            } elseif ($this->data['Form']['accion'] === 'cancelar') {
                $this->History->goBack(1);
            }
        }
        $this->render('add');
    }


/**
 * Callback executed after a save successful operation. 
 */
	function afterSave($params = array()) {
		return true;
	}

/**
 * Delete.
 *
 * @param integer $id The record id to delete.
 * @param integer $goBack How many steps back shouls return.
 * @return void.
 * @access public
 */
   	function delete($id = null, $goBack = 0) {
        if (!empty($id) && is_numeric($id)) {
			$ids[] = $id;
		} else {
			$ids = $this->Util->extraerIds($this->data['seleccionMultiple']);
		}

        $this->{$this->modelClass}->access = 'delete';
		if ($this->{$this->modelClass}->deleteAll(array($this->modelClass . '.' . $this->{$this->modelClass}->primaryKey => $ids), true, true)) {
			$cantidad = count($ids);
			if ($cantidad === 1) {
				$mensaje = __('Record deleted', true);
			} else {
				$mensaje = sprintf(__('%s records deleted', true), $cantidad);
			}
			$this->Session->setFlash($mensaje, 'ok', array('warnings' => $this->{$this->modelClass}->getWarning()));
		} else {
            $this->Session->setFlash(__('The record could not be deleted', true), 'error');
		}
		$this->History->goBack($goBack);
	}


/**
 * Muestra via desglose los permisos de un registro y permite via ajax la modificacion de los mismos.
 *
 * @param integer $id El identificador unico del registro del que se mostraran los permisos.
 * @return void.
 * @access public 
 */
	function permisos($id) {
		$this->{$this->modelClass}->recursive = -1;
		$registro = $this->{$this->modelClass}->findById($id);
		
		if (!empty($this->params['named']['quitarGrupo'])) {
			$save[$this->modelClass]['group_id'] = (int)$registro[$this->modelClass]['group_id'] - (int)$this->params['named']['quitarGrupo'];
		}
		elseif (!empty($this->params['named']['agregarGrupo'])) {
			$save[$this->modelClass]['group_id'] = (int)$registro[$this->modelClass]['group_id'] + (int)$this->params['named']['agregarGrupo'];
		}
		elseif (!empty($this->params['named']['quitarRol'])) {
			$save[$this->modelClass]['role_id'] = (int)$registro[$this->modelClass]['role_id'] - (int)$this->params['named']['quitarRol'];
		}
		elseif (!empty($this->params['named']['agregarRol'])) {
			$save[$this->modelClass]['role_id'] = (int)$registro[$this->modelClass]['role_id'] + (int)$this->params['named']['agregarRol'];
		}
		elseif (!empty($this->params['named']['accion'])) {
			switch($this->params['named']['accion']) {
				case "pt": //permitir todo.
					$save[$this->modelClass]['permissions'] = 511;
					break;
				case "dt": //denegar todo.
					$save[$this->modelClass]['permissions'] = 0;
					break;
				case "plt": //permitir leer todo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 292;
					break;
				case "dlt": //denegar leer todo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~292;
					break;
				case "pet": //permitir escribir todo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 146;
					break;
				case "det": //denegar escribir todo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~146;
					break;
				case "pdt": //permitir eliminar todo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 73;
					break;
				case "ddt": //denegar eliminar todo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~73;
					break;
				case "ptd": //permitir todo dueno.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 448;
					break;
				case "dtd": //denegar todo dueno.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~448;
					break;
				case "ptg": //permitir todo grupo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 56;
					break;
				case "dtg": //denegar todo grupo.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~56;
					break;
				case "pto": //permitir todo otros.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 7;
					break;
				case "dto": //denegar todo otros.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~7;
					break;
				case "pdl": //permitir dueno lectura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 256;
					break;
				case "ddl": //denegar dueno lectura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~256;
					break;
				case "pde": //permitir dueno escritura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 128;
					break;
				case "dde": //denegar dueno escritura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~128;
					break;
				case "pdd": //permitir dueno eliminar.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 64;
					break;
				case "ddd": //denegar dueno eliminar.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~64;
					break;
				case "pgl": //permitir grupo lectura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 32;
					break;
				case "dgl": //denegar grupo lectura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~32;
					break;
				case "pge": //permitir grupo escritura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 16;
					break;
				case "dge": //denegar grupo escritura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~16;
					break;
				case "pgd": //permitir grupo eliminar.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 8;
					break;
				case "dgd": //denegar grupo eliminar.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~8;
					break;
				case "pol": //permitir otros lectura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 4;
					break;
				case "dol": //denegar otros lectura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~4;
					break;
				case "poe": //permitir otros escritura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 2;
					break;
				case "doe": //denegar otros escritura.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~2;
					break;
				case "pod": //permitir otros eliminar.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] | 1;
					break;
				case "dod": //denegar otros eliminar.
					$save[$this->modelClass]['permissions'] = $registro[$this->modelClass]['permissions'] & ~1;
					break;
			}
		}
		
		if (!empty($save)) {
			$save[$this->modelClass][$this->{$this->modelClass}->primaryKey] = $id;
			/**
			* Si pudo grabar con exito, los permisos de este registro cambiaron, entonces lo cambio.
			*/
			if ($this->{$this->modelClass}->save($save, false)) {
				if (isset($save[$this->modelClass]['group_id'])) {
					$registro[$this->modelClass]['group_id'] = $save[$this->modelClass]['group_id'];
				}
				if (isset($save[$this->modelClass]['role_id'])) {
					$registro[$this->modelClass]['role_id'] = $save[$this->modelClass]['role_id'];
				}
				if (isset($save[$this->modelClass]['permissions'])) {
					$registro[$this->modelClass]['permissions'] = $save[$this->modelClass]['permissions'];
				}
			}
		}

		
		/**
		* Busco el usuario, grupo/s y rol/es a los que pertenece el registro.
		*/
		//App::import("model", "Usuario");
		//$modelUsuario = new Usuario();
		$modelUsuario = ClassRegistry::init('Usuario');
		$modelUsuario->recursive = -1;
		$usuario = $modelUsuario->find("first", array("checkSecurity"=>false, "conditions"=>array("Usuario.id"=>$registro[$this->modelClass]['user_id'])));
		$grupos = $modelUsuario->Grupo->find("all", array(	"checkSecurity"	=> false,
															"recursive"		=> -1,
															"conditions"	=> array(
																"(Grupo.id) & " . $registro[$this->modelClass]['group_id'] . " >" => 0)));
		$roles = $modelUsuario->Rol->find("all", array(	"checkSecurity"	=> false,
															"recursive"		=> -1,
															"conditions"	=> array(
																"(Rol.id) & " . $registro[$this->modelClass]['role_id'] . " >" => 0)));

		/**
		* Busco los datos del usuario que tengo en la session (el que esta actualmente logueado).
		*/
		$usuarioSession = $this->Session->read("__Usuario");

		/**
		* Solo el root, el dueno o alguien con el rol administradores del grupo dueno del registro
		* podra cambiar los permisos.
		*/
		$registro['puedeCambiarPermisos'] = false;
		if ((int)$usuarioSession['Usuario']['id'] === 1
			|| $registro[$this->modelClass]['user_id'] === $usuarioSession['Usuario']['id']
			|| ((int)$usuarioSession['Usuario']['roles'] & 1 === 1
				&& (int)$registro[$this->modelClass]['group_id'] & $usuarioSession['Usuario']['grupos'] > 0)) {
			$registro['puedeCambiarPermisos'] = true;
			
			/**
			* A los grupos del registro, agrego tambien los del usuario, asi puede agregarlos si este lo desea.
			* siempre y cuando pueda cambiar los permisos.
			*/
			$gruposId = Set::extract("/Grupo/id", $grupos);
			foreach ($usuarioSession['Grupo'] as $v) {
				if (!in_array($v['id'], $gruposId)) {
					$grupos[]['Grupo'] = $v;
				}
			}
			foreach ($grupos as $k=>$grupo) {
				if ($registro['puedeCambiarPermisos'] === true) {
					if (((int)$registro[$this->modelClass]['group_id'] & (int)$grupo['Grupo']['id']) > 0) {
						$grupos[$k]['Grupo']['posible_accion'] = "quitar";
					}
					else {
						$grupos[$k]['Grupo']['posible_accion'] = "agregar";
					}
				}
			}

			/**
			* A los roles del registro, agrego tambien los del usuario, asi puede agregarlos si este lo desea.
			* siempre y cuando pueda cambiar los permisos.
			*/
			$rolesId = Set::extract("/Rol/id", $roles);
			foreach ($usuarioSession['Rol'] as $v) {
				if (!in_array($v['id'], $rolesId)) {
					$roles[]['Rol'] = $v;
				}
			}
			foreach ($roles as $k=>$rol) {
				if ($registro['puedeCambiarPermisos'] === true) {
					if (((int)$registro[$this->modelClass]['role_id'] & (int)$rol['Rol']['id']) > 0) {
						$roles[$k]['Rol']['posible_accion'] = "quitar";
					}
					else {
						$roles[$k]['Rol']['posible_accion'] = "agregar";
					}
				}
			}
		}
		
		$permisos = str_split(str_pad(base_convert($registro[$this->modelClass]['permissions'], 10, 2), 9, "0", STR_PAD_LEFT));
		foreach ($permisos as $k=>$v) {
			switch($k) {
				case 0:
					$pd['leer'] = $v;
					break;
				case 1:
					$pd['escribir'] = $v;
					break;
				case 2:
					$pd['eliminar'] = $v;
					break;
				case 3:
					$pg['leer'] = $v;
					break;
				case 4:
					$pg['escribir'] = $v;
					break;
				case 5:
					$pg['eliminar'] = $v;
					break;
				case 6:
					$po['leer'] = $v;
					break;
				case 7:
					$po['escribir'] = $v;
					break;
				case 8:
					$po['eliminar'] = $v;
					break;
			}
		}
		$registro['Grupo'] = $grupos;
		$registro['Rol'] = $roles;
		$registro['Usuarios'] = $usuario['Usuario'];
		$registro['Usuarios']['permisos'] = $pd;
		$registro['Grupos']['permisos'] = $pg;
		$registro['Otros']['permisos'] = $po;

		$this->set("registro", $registro);
		$this->render(".." . DS . "elements" . DS . "desgloses" . DS . "permisos");
		
	}


/**
 * Antes de ejecutar una action controlo seguridad.
 * Si la session caduco, lo redirijo nuevamente al login.
 *
 * @return void
 * @access public
 * TODO:
 * Utilizar el auth component de cakePHP
 */
    function beforeFilter() {

		/** Save selected menu (actualMenu) in a cookie */
		if (isset($this->passedArgs['am'])) {
            setcookie('menu_cookie', $this->passedArgs['am'], 0, '/');
		}

        $accionesWhiteList = array(
            'Usuarios.login',
            'Usuarios.logout',
            'Service.call',
            'Service.wsdl');

        if (in_array($this->name . '.' . $this->action, $accionesWhiteList)) {
            return true;
        } else {

            App::import('Model', 'User');
            if (User::store($this->Session->read('__Usuario'))) {

                /** Agrego soporte para que retorne json.*/
                //$this->RequestHandler->setContent('json', 'text/x-json');
                return true;
            } else {
				/** Check if it is installed */
				if (!file_exists(APP . 'config' . DS . 'database.php')) {
                	$this->redirect(array('controller'    => 'install'));
				} else {
                	$this->redirect(array(
						'controller'    => 'usuarios',
                        'action'        => 'login'));
				}
            }
        }
    }


/**
 * Mediante un request ajax desde javascript borro de la session TODOS los filtros que esten seteados.
 * Luego, con el mismo js recargo la pagina y dara el efecto de limpiar las busquedas.
 * 
 * @return void.
 * @access public
 */
	function limpiar_busquedas() {
		$this->Session->delete('filtros');
		$this->autoRender = false;
	}
	
	
}
?>