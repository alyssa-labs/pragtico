<?php
/**
 * Paginador Component.
 * Se encarga de la paginacion en las grillas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers.components
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1301 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-17 03:30:30 -0300 (lun 17 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de la paginacion.
 * Se encarga de la paginacion, de armar las condiciones de busqueda y mantenerlas en la session.
 *
 * @package     pragtico
 * @subpackage  app.controllers.components
 */
class PaginadorComponent extends Object {

/**
 * Controller associate to the component.
 *
 * @var array
 * @access private
 */
    private $__controller;


/**
 * $whiteListFields are fields that should be saved in session but should not be used at filters.
 * Bar model's fields will automatically be whiteListed.
 *
 * @var array
 * @access private
 */
    private $__whiteListFields = array();


/**
 * $conditions that should be applied to current filter and saved to session.
 *
 * @var array
 * @access private
 */
    private $__conditions = array();


/**
 * $conditions that should be remove from filter and session.
 *
 * @var array
 * @access private
 */
    private $__conditionsToRemove = array();


/**
 * Inicializa el Component para usar en el controller.
 *
 * @param object $controller Una referencia al controller que esta instanciando el component.
 * @return void
 * @access public
 */
    function startup($controller) {
        $this->__controller = $controller;
    }


/**
 * Genera las condiciones para el paginador a partir de los datos que vengan cargados en
 * $this->data['Condicion']. Si este esta vacio, intenta leerlos desde la sesion si esta existe
 * en caso de que se haya paginado.
 *
 * @param boolean $useSession. If true, session data for the controller will be merged with controller->data
 *								to create conditions.
 *								When false, just controller->data will be use to create conditions.
 *
 * @return array Un array con las condiciones de la forma que exije el framework para el metodo find.
 * @access public
 */
    function generarCondicion($useSession = true) {

        /** Delete filters */
        if (isset($this->__controller->data['Formulario']['accion']) && $this->__controller->data['Formulario']['accion'] === 'limpiar') {
            $this->__controller->Session->delete('filtros.' . $this->__controller->name . '.' . $this->__controller->action);
            unset($this->__controller->data['Condicion']);
            $useSession = false;
        }


        /** Get session data */
        $conditions = $this->getCondition();
        $valoresLov = array();
        if ($useSession === true) {
            $filter = $this->__controller->Session->read('filtros.' . $this->__controller->name . '.' . $this->__controller->action);
            if (!empty($filter)) {
                $conditions = array_merge($filter['condiciones'], $conditions);
                $valoresLov = $filter['valoresLov'];
            }
        }

        if (!empty($this->__controller->data['Condicion'])) {
            foreach ($this->__controller->data['Condicion'] as $k => $v) {

                list($model, $field) = explode('-', $k);
                $modelField = $model . '.' . $field;

                if ($model === 'Bar') {
                    $this->setWhiteList($k);
                }

                /** Ignore empty values and removed then from sessions */
                if (empty($v)) {
                    unset($conditions[$modelField]);
                    continue;
                }

                /** Ignore on lov descriptive data */
                if (substr($field, -2) === '__' || in_array($k, $this->getWhiteList())) {
                    $valoresLov[$k] = $v;
                    continue;
                }


                /** Replace range conditions
                $modelField = str_replace('__desde', ' >=', $modelField);
                $modelField = str_replace('__hasta', ' <=', $modelField);
                 */
                $conditions = array_merge($conditions, $this->__reemplazos($modelField, $v));
            }
        }

        if (!empty($this->__conditionsToRemove)) {
            foreach ($this->__conditionsToRemove as $k) {
                unset($conditions[$k]);
            }
        }

        if (!empty($conditions) || !empty($valoresLov)) {
            $this->__controller->Session->write('filtros.' . $this->__controller->name . '.' . $this->__controller->action, array('condiciones' => $conditions, 'valoresLov' => $valoresLov));
        }

        /** Save currently used conditions */
        $this->__conditions = $conditions;
        return $conditions;
    }


/**
 * Genera el array para $this->data a partir de las condiciones para que el helper pinte nuevamente
 * los valores en la vista.
 *
 * @access public
 * @return void
 */
    function generarData() {
		$condiciones = $this->__controller->Session->read('filtros.' . $this->__controller->name . '.' . $this->__controller->action);
		if (!empty($condiciones)) {
        	/**
        	* Restauro los valores 'que se ven de una lov, para no perderlos.
        	* Estos no estan con las condiciones porque no se usaron en los filtros, aunque si deben mostrarse
        	* en el control lov.
        	*/
            $lovFields = array();
        	if (!empty($condiciones['valoresLov']) && is_array($condiciones['valoresLov'])) {
				foreach ($condiciones['valoresLov'] as $k => $v) {
					$this->__controller->data['Condicion'][$k] = $v;
                    if (substr($k, -2) === '__') {
                        $lovFields[] = str_replace('__', '', $k);
                    }
				}
			}
			/**
			* A partir del array de condiciones, vuelvo a generar el array data para que el helper lo entienda,
			* y restaure los valores.
			*/
			foreach ($condiciones['condiciones'] as $k => $v) {
				$condicionMultiple = null;
				$sufix = substr(trim($k), -2);
				$k = str_replace('.', '-', $this->__removerReemplazos($k));
				if ($sufix === '>=') {
                    $k .= '__desde';
				} elseif ($sufix === '<=') {
                    $k .= '__hasta';
                } elseif (in_array($k, $lovFields)) {
                    $v = implode("**||**", (array)$v);
                }
                $this->__controller->data['Condicion'][$k] = $this->__removerReemplazos($v);
			}
        }
	}


/**
 * Set whiteListed Fields.
 *
 * @param array|string $whiteListFields.
 */
    function setWhiteList($whiteListFields) {
        $this->__whiteListFields = array_merge($this->__whiteListFields, (array)$whiteListFields);
    }


/**
 * Get whiteListed Fields.
 *
 * @return array
 */
    function getWhiteList() {
        return $this->__whiteListFields;
    }
    

/**
 * Set conditions.
 *
 * @param array|string $conditions.
 * @param boolean $reset When true, previous conditions will be reseted.
 */
    function setCondition($conditions, $reset = false) {
        if ($reset === false) {
            $this->__conditions = array_merge($this->__conditions, (array)$conditions);
        } else {
            $this->__controller->Session->delete('filtros.' . $this->__controller->name . '.' . $this->__controller->action);
            $this->__controller->data = array();
            $this->__conditions = (array)$conditions;
        }
    }


/**
 * Get conditions.
 *
 * @return array
 */
    function getCondition() {
        return $this->__conditions;
    }


/**
 * Remove conditions.
 *
 * @param array|string $conditions.
 */
    function removeCondition($conditions) {
        $this->__conditionsToRemove = array_merge($this->__conditionsToRemove, (array)$conditions);
    }


/**
 * Establece las condiciones, realiza las consultas a la base y deja el array $this->data['Condicion']
 * de manera que el helper pueda cargar los valores de las busquedas.
 *
 * @param array $condicion Condiciones que se sumaran a las que hay en la sesion.
 * @param array $whiteList Campos que no deben ser inlcuidos en los filtros pero si guardados en la session.
 * @param boolean $useSession. If true, session data for the controller will be merged with controller->data
 *								to create conditions.
 *								When false, just controller->data will be use to create conditions.
 *
 * @return array Resultados de la paginacion.
 * @access public
 */
    function paginar($options = array()) {

        $defaults = array(  'whiteListFields'   => $this->__whiteListFields,
                            'extraConditions'   => array(),
                            'mergeConditions'   => false,
                            'useSession'        => true);

        $options = array_merge($defaults, $options);
        
        if ($defaults['useSession'] === true) {
            $conditions = array_merge($this->generarCondicion($options['useSession'], $options['whiteListFields']), $options['extraConditions']);
        } else {
            $conditions = $condicion;
        }

        if (!empty($this->__controller->{$this->__controller->modelClass}->modificadores[$this->__controller->action]['contain'])) {
            $this->__controller->paginate['contain'] = $this->__controller->{$this->__controller->modelClass}->modificadores[$this->__controller->action]['contain'];
        }
        
        if (!empty($this->__controller->paginate['conditions'])) {
            $this->__controller->paginate['conditions'] = array_merge($this->__controller->paginate['conditions'], $conditions);
        } else {
            $this->__controller->paginate['conditions'] = $conditions;
        }

        $this->generarData();
        
        if (method_exists($this->__controller, 'afterPaginate')) {
            $results = $this->__controller->paginate();
            $this->__controller->afterPaginate($results);
            return $results;
        } else {
            return $this->__controller->paginate();
        }
    }



/**
 * Realiza los reemplazos necesarios en funcion del tipo de campo para ser entendidos por un query SQL.
 *
 * @param string $modelCampo El nombre del model y del campo en la forma Model.Campo.
 * @param array	 $v El valor que tiene el campo.
 * @return string Valor del campo ya reemplazado en funcion de su tipo.
 * @access private
 */
	function __reemplazos($key, $value) {
        
        list($model, $field) = explode('.', $key);

        if (substr($field, strlen($field) - 7) == '__desde') {
            $field = str_replace('__desde', '', $field);
            $extra = 'desde';
        } elseif (substr($field, strlen($field) - 7) == '__hasta') {
            $field = str_replace('__hasta', '', $field);
            $extra = 'hasta';
        }

        if (isset($this->__controller->{$model}) && is_object($this->__controller->{$model})) {
            $tipoDato = $this->__controller->{$model}->getColumnType($field);
        }
        /**
        * Para el caso de una busqueda por un model asociado, veo si lo encuentro.
        */
        elseif (isset($this->__controller->{$this->__controller->modelClass}->{$model}) && is_object($this->__controller->{$this->__controller->modelClass}->$model)) {
            $tipoDato = $this->__controller->{$this->__controller->modelClass}->{$model}->getColumnType($field);
        }

        if (is_string($value)) {
            if (strpos($value, '**||**') === false) {
                if (!empty($tipoDato)) {
                    switch ($tipoDato) {
                        case 'text':
                        case 'string':
                            $value = '%' . $value . '%';
                            $field .= ' like';
                            break;
                        case 'date':
                        case 'datetime':
                            if (isset($extra)) {
                                if ($extra == 'desde') {
                                    $field .= ' >=';
                                } elseif ($extra == 'hasta') {
                                    $field .= ' <=';
                                }
                            }
                            break;
                    }
                }
            } else {
                $value = explode('**||**', $value);
            }
        }

		return array($model . '.' . $field => $value);
	}


/**
 * Quita los reemplazos realizados por el metodo '__reemplazos' de manera de volver el valor del campo a su estado
 * original.
 *
 * @param string $valor Un valor con caracteres agregados por el metodo reemplazos.
 * @return string El Valor del campo sin los reemplazos.
 * @access private
 */
	function __removerReemplazos($valor) {
		if (is_string($valor)) {
			return trim(str_replace(array('!=', 'like', '%', '>=', '<='), '', $valor));
		} else {
			return $valor;
		}
	}
}
?>