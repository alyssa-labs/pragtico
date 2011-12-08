<?php
/**
 * Behavior que contiene las validaciones.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models.behaviors
 * @since			Practico v 1.0.0
 * @version         $Revision: 1454 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-06-30 19:21:59 -0300 (jue 30 de jun de 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Especifico todos los metodos de validacion que requiera.
 * Es importante que cada metodo que agregue tenga el prefijo 'valid', ej:
 *		validRule(&$model, $rule, $ruleParams)
 * Esto para no ser confundido con algun otro metodo de algun otro behavior.
 *
 * @package     pragtico
 * @subpackage  app.models.behaviors
 */
class ValidacionesBehavior extends ModelBehavior {

/**
 * Valida que un campo sea cargado siempre y cuando no lo sean los demas.
 *
 * @param object $model Model que esta siendo validado.
 * @param array $rule La regla que se debe validar.
 * @param array $ruleParams Parametros adicionales que puedo pasarle a la regla de validacion.
 * @return boolean True si los demas campos estan vacios, false en caso contrario.
 * @access public
 *
 * Ej:
 * Esto indica que el campo dias, validara siempre y cuando el campo1 y el campo2 hayan quedado vacios.
 *
 *       'dias' => array(
 *			array(
 *				'rule'	=> 'validExcluyente',
 *				'otrosCampos'=> array('campo1', 'campo2'),
 *				'message'	=> 'Debe especificar el numero de dias y dejar vacios los campos: campo1 y campo2.')
 *       ),
 */
	function validExcluyente(&$model, $rule, $ruleParams) {
		$value = $this->__getValue($rule);

		/**
		* Si es un campo float (decimal) o integer y su valor es 0, lo considero vacio.
		*/
		$tipoDato = $model->schema($this->__getField($rule));
		$campoNumericos = array('integer', 'float');
		if (in_array($tipoDato['type'], $campoNumericos) && $value == 0) {
			return true;
		}
		elseif (!empty($value)) {
			if (!empty($ruleParams['opciones']['otrosCampos'])) {
				if (is_string($ruleParams['opciones']['otrosCampos'])) {
					$ruleParams['opciones']['otrosCampos'] = array($ruleParams['opciones']['otrosCampos']);
				}
				foreach ($ruleParams['opciones']['otrosCampos'] as $campo) {
					$tipoDato = $model->schema($campo);
					if (in_array($tipoDato['type'], $campoNumericos) && $model->data[$model->name][$campo] == 0) {
						continue;
					}
					elseif (!empty($model->data[$model->name][$campo])) {
						return false;
					}
				}
			}
		}
		return true;
	}


    function validFormulaParenthesis(&$model, $rule, $ruleParams) {
        $value = $this->__getValue($rule);
        if (!empty($value) &&
            count(explode('(', $value)) !== count(explode(')', $value))) {
            return false;
        }
        return true;
    }

    function validFormulaBrackets(&$model, $rule, $ruleParams) {
        $value = $this->__getValue($rule);
        if (!empty($value) &&
            count(explode('[', $value)) !== count(explode(']', $value))) {
            return false;
        }
        return true;
    }

    function validFormulaConcepts(&$model, $rule, $ruleParams) {
        $value = $this->__getValue($rule);

		/** Search for vars and concepts */
		preg_match_all('/@([0-9a-z_]+)/', $value, $matchesA);

		if (empty($matchesA[0])) {
			return true;
		} else {
			$concepts = array_unique($matchesA[1]);
			$Concepto = ClassRegistry::init('Concepto');
			$Concepto->Behaviors->detach('Permisos');
			$count = ClassRegistry::init('Concepto')->find('count',
				array(
					'recursive'		=> -1,
					'conditions' 	=>
					array('Concepto.codigo' => $concepts)
				)
			);
			if (count($concepts) == $count) {
				return true;
			} else {
				return false;
			}
		}
	}

    function validFormulaStrings(&$model, $rule, $ruleParams) {
        $value = $this->__getValue($rule);

		/** Search for vars and concepts */
		preg_match_all('/[#|@][0-9a-z_\/]+/', $value, $matchesA);

		/** Search for strings */
		preg_match_all('/[\'\"]{1}[a-zA-Z0-9\s]+[\'\"]{1}/i', $value, $matchesB);

		/** Search for functions (based on phpexcel calculation regexp to identify formulas) */
		preg_match_all('/@?([A-Z][A-Z0-9\.]*)[\s]*\(/i', $value, $matchesC);

		/** Search for reserved words */
		preg_match_all('/Remunerativo|Deduccion|No\sRemunerativo+/', $value, $matchesD);


		/** Replace all accepted string by numbers, if remaining string, means they are not accepted and are wrong */
		$tmpSearchs = array_unique(array_merge($matchesA[0], $matchesB[0], $matchesC[1], $matchesD[0]));
		$tmp = array();
		foreach ($tmpSearchs as $k => $search) {
			$tmp[strlen($search)][] = $search;
		}
		if (!empty($tmp)) {
			ksort($tmp, SORT_NUMERIC);
			foreach (array_reverse($tmp) as $v) {
				foreach ($v as $v1) {
					$searchs[] = $v1;
				}
			}
		} else {
			$searchs = $tmpSearchs;
		}

		$replacedFormula = str_ireplace($searchs, '0', $value);

		preg_match_all('/[a-zA-Z]+/', $replacedFormula, $matches);
		if (!empty($matches[0])) {
			return false;
		}

		return true;
    }


/**
 * Valida que el extremo superior del rango sea mayor al inferior en caso de que ambos esten seteados.
 *
 * @param object $model Model que esta siendo validado.
 * @param array $rule La regla que se debe validar.
 * @param array $ruleParams Parametros adicionales que puedo pasarle a la regla de validacion.
 * @return boolean True si se cumple la condicion de que el extremo superior del rango sea mayor al inferior,
 * false en caso contrario.
 * @access public
 */
    function validRango(&$model, $rule, $ruleParams) {
		$value = $this->__getValue($rule);
		if (empty($value)) {
    		return true;
    	}
    	
		if (empty($ruleParams['opciones']['condicion'])) {
			trigger_error('Debe especificar la condicion en el model ' . $model->name . ' para la validacion (validRango).', E_USER_WARNING);
			return false;
		}
		else {
			$posiblesCondiciones = array('>', '>=', '<', '<=');
			$condicion = $ruleParams['opciones']['condicion'];
			if (!in_array($condicion, $posiblesCondiciones)) {
				trigger_error('La condicion en el model ' . $model->name . ' para la validacion (validRango) solo puede ser ' . implode(', ', $posiblesCondiciones) . '.', E_USER_WARNING);
				return false;
			}
		}
		
    	if (!empty($ruleParams['opciones']['limiteInferior']) && !empty($model->data[$model->name][$ruleParams['opciones']['limiteInferior']])) {
    		$campoAComparar = $ruleParams['opciones']['limiteInferior'];
    	}
    	if (!empty($ruleParams['opciones']['limiteSuperior']) && !empty($model->data[$model->name][$ruleParams['opciones']['limiteSuperior']])) {
    		$campoAComparar = $ruleParams['opciones']['limiteSuperior'];
    	}

		$valorAComparar = $model->data[$model->name][$campoAComparar];
		$campo = $this->__getField($rule);

		$schema = $model->schema();
		$tipoCampo = $schema[$campo]['type'];
		$tipoCampoAComparar = $schema[$campoAComparar]['type'];

		if ($tipoCampo != $tipoCampoAComparar) {
			trigger_error('Debe especificar campos del mismo tipo en el model ' . $model->name . ' para la validacion (validRango).', E_USER_WARNING);
			return false;
		}

		if ($tipoCampo == 'date' || $tipoCampo == 'datetime') {
			$tmp = substr($value, 0, 10);
			if (preg_match(VALID_DATE, $tmp, $matches)) {
				$value = $matches[3] . '-' . $matches[2] . '-' . $matches[1] . ' ' . substr($value, 10);
			}
			$tmp = substr($valorAComparar, 0, 10);
			if (preg_match(VALID_DATE, $tmp, $matches)) {
				$valorAComparar = $matches[3] . '-' . $matches[2] . '-' . $matches[1] . ' ' . substr($valorAComparar, 10);
			}
		}
			
		switch($condicion) {
			case '>':
				if ($value > $valorAComparar) {
					return true;
				}
				break;
			case '>=':
				if ($value >= $valorAComparar) {
					return true;
				}
				break;
			case '<':
				if ($value < $valorAComparar) {
					return true;
				}
				break;
			case '<=':
				if ($value <= $valorAComparar) {
					return true;
				}
				break;
			default:
				return false;
		}
    }
    

/**
 * Valida que entre una serie de campos, por lo menos uno de ellos contenga algun valor y no esten todos vacios.
 *
 * @param object $model Model que esta siendo validado.
 * @param array $rule La regla que se debe validar.
 * @param array $ruleParams Parametros adicionales que puedo pasarle a la regla de validacion.
 * @return boolean True si por lo menos uno de los campos del conjunto tiene algun valor, false en caso contrario.
 * @access public
 *
 * Ej:
 * Esto indica que el campo dias, validara siempre y cuando el campo1 y el campo2 hayan quedado vacios.
 *
 *       'dias' => array(
 *			array(
 *				'rule'	=> 'validUnoPorLoMenos',
 *				'otrosCampos'=> array('campo1', 'campo2'),
 *				'message'	=> 'Debe especificar por lo menos algun valor para campo1, campo2 o dias.')
 *       ),
 */
    function validUnoPorLoMenos(&$model, $rule, $ruleParams) {
    	$value = $this->__getValue($rule);
		if (!empty($value)) {
			return true;
		}
		elseif (!empty($ruleParams['opciones']['otrosCampos'])) {
			if (is_string($ruleParams['opciones']['otrosCampos'])) {
				$ruleParams['opciones']['otrosCampos'] = array($ruleParams['opciones']['otrosCampos']);
			}
			foreach ($ruleParams['opciones']['otrosCampos'] as $campo) {
				if (!empty($model->data[$model->name][$campo])) {
					return true;
				}
			}
		}
		return false;
	}

	
/**
 * Valida que un numero de cuit o cuil sea valido.
 * Hago un port desde la funcion js de afip.
 *
 * @param object $model Model que esta siendo validado.
 * @param array $rule La regla que se debe validar.
 * @param array $ruleParams Parametros adicionales que puedo pasarle a la regla de validacion.
 * @return boolean True si el cuit/cuil es valido, false en caso contrario.
 * @access public
 */
	function validCuitCuil(&$model, $rule, $ruleParams) {

		$value = $this->__getValue($rule);
		/**
		* Si viene vacio lo valida como correcto, ya que la funcion valida si un cuit/cuil es valido o no. No valida
		* que este deba venir necesariamente. 
		*/
		if (empty($value)) {
			return true;
		}

		/**
		* Separo cualquier caracter que no tenga que ver con numeros.
		*/
		$value = preg_replace('/[^0-9]/','', $value);
		
		/**
		* Si no estan todos los digitos o no empieza con algun digito valido.
		*/
		If (strlen($value) <> 11 || !in_array(substr($value, 0, 2), array('20', '23', '24', '27', '30', '33', '34'))) {
			return false;
		}
		else {
			$coeficiente[0]=5;
			$coeficiente[1]=4;
			$coeficiente[2]=3;
			$coeficiente[3]=2;
			$coeficiente[4]=7;
			$coeficiente[5]=6;
			$coeficiente[6]=5;
			$coeficiente[7]=4;
			$coeficiente[8]=3;
			$coeficiente[9]=2;
			$coeficiente[10]=1;
			
			$suma = 0;
			foreach ($coeficiente as $k=>$v) {
				$suma += $value[$k] * $v;
			}

			If (($suma/11) ==  floor($suma/11)) {
				/**
				* Lo formateo para que se guarde formateado.
				*/
				$field = $this->__getField($rule);
				$model->data[$model->name][$field] = preg_replace('/(\d{2})(\d{8})(\d{1})/', '$1-$2-$3', $value);
				return true;
			}
			else {
				return false;
			}
		}
	}


/**
 * Valida que un Cbu sea valido.
 *
 * @param object $model Model que esta siendo validado.
 * @param array $rule La regla que se debe validar.
 * @param array $ruleParams Parametros adicionales que puedo pasarle a la regla de validacion.
 * @return boolean True si el cuit/cuil es valido, false en caso contrario.
 * @access public
 */
	function validCbu(&$model, $rule, $ruleParams) {
		$value = $this->__getValue($rule);
		/**
		* Si viene vacio lo valida como correcto, ya que la funcion valida si un Cbu es valido o no. No valida
		* que este deba necesariamente venir.
		*/
		if (empty($value)) {
			return true;
		}

		/**
		* Formato del CBU:
		*   EEESSSS-V TTTTTTTTTTTTT-V
		* Bloque 1:
		*   EEE - Número de entidad (3 posiciones)
		*   SSSS - Número de sucursal (4 posiciones)
		*   V - Dígito verificador de las primeras 7 posiciones
		* Bloque 2:
		*   TTTTTTTTTTTTT - Identificación de la cuenta individual
		*   V - Dígito verificador de las anteriores 13 posiciones
		*
		* Para el cálculo de los dígitos verificadores se
		* debe aplicar la clave 10 con el ponderador 9713
		*
		* Cbus validas
		* 2850381130000000040036
		* 3870081 9 0080160004003 2
		* 2650450202145056396676
		*/

		$value = str_replace('-', '', $value);
		if (strlen($value) == 22) {
			$parteA = substr($value, 0, 7);
			$digitoParteA = substr($value, 7, 1);
			$parteB = substr($value, 8, 13);
			$digitoParteB = substr($value, 21, 1);
			if ($this->__getDigitoVerificador($parteA) == $digitoParteA && $this->__getDigitoVerificador($parteB) == $digitoParteB) {
				return true;
			}
		}
		return false;
	}


/**
 * Calcula el digito verificador del cbu.
 *
 * @param string $lcBloque Bloque de numeros a los cuales calcular el digito verificador.
 * @return integer El digito verificador calculado.
 * @access private
 */	
	function __getDigitoVerificador($lcBloque) {
		$Pond = 9713;
		$lnSuma = 0;
		$lnLargo = strlen($lcBloque);
		$j=3;
		for($i=1;$i<=$lnLargo;$i++){
			$lnSuma = $lnSuma + (substr($lcBloque, $lnLargo - $i, 1)) * (substr($Pond, $j, 1));
			if ($j==0) {
				$j=3;
			}
			else {
				$j--;
			}
		}
		return ((10 - ($lnSuma%10))%10);
	}


/**
* Custom validation rule for uploaded files.
*
*  @param Array $data CakePHP File info.
*  @param Boolean $required Is this field required?
*  @return Boolean
*/
    function validateUploadedFile($data, $required = false) {
            // Remove first level of Array ($data['Artwork']['size'] becomes $data['size'])
            $upload_info = array_shift($data);

            // No file uploaded.
            if ($required && $upload_info[’size’] == 0) {
                    return false;
            }

            // Check for Basic PHP file errors.
            if ($upload_info[‘error’] !== 0) {
                    return false;
            }

            // Finally, use PHP’s own file validation method.
            return is_uploaded_file($upload_info[‘tmp_name’]);
    }

    
/**
 * Retorna el valor ingresado por el usuario.
 *
 * @param array $rule Un regla de validacion.
 * @return string El valor contenido dentro de la regla (lo que el usuario ingreso).
 * @access private
 */
	function __getValue($rule) {
		if (!empty($rule)) {
			if (is_string(key($rule))) {
				if (!empty($rule[key($rule)])) {
					return $rule[key($rule)];
				}
			}
		}
		return '';
	}


/**
 * Retorna el nombre del campo que contiene el valor ingresado por el usuario.
 *
 * @param array $rule Un regla de validacion.
 * @return string El nombre del campo contenido dentro de la regla.
 * @access private
 */
	function __getField($rule) {
		if (!empty($rule)) {
			if (is_string(key($rule))) {
				return key($rule);
			}
		}
		return '';
	}


/**
 * Before save callback
 *
 * @return boolean True if the operation should continue, false if it should abort
 */    
    function beforeSave(&$model) {
    	$this->setDBFieldValue($model);
    	return true;
	}


	/**
	* Si hay un campo fecha o fechahora, lo hago db compatible (yyyy-mm-dd).
	* Si hay un float o integer y no puede ser null, lo hago 0.
	* Si hay un campo que debe ser null y viene vacio, lo hago null.
	*/
	function __setDBFieldValue($fieldDescriptor, $value) {

        if (in_array($fieldDescriptor['type'], array('datetime', 'date'))) {
            if (empty($value)) {
                return '0000-00-00';
            }
            //return $this->__getMySqlDate($value);
            return $value;
        }
                        
		if (isset($fieldDescriptor['null']) && !$fieldDescriptor['null']) {
			if (!empty($fieldDescriptor['default']) && empty($value)) {
				return $fieldDescriptor['default'];
			} elseif (in_array($fieldDescriptor['type'], array('datetime', 'date'))) {
				if (empty($value)) {
					return '0000-00-00';
				}
				//return $this->__getMySqlDate($value);
                return $value;
			} elseif (in_array($fieldDescriptor['type'], array('float', 'integer', 'binary')) && empty($value)) {
				return 0;
			} elseif (in_array($fieldDescriptor['type'], array('string', 'text')) && strlen($value) === 0) {
				return '';
			}
		} else {
			if (empty($value)) {
				return null;
			}
		}
		return $value;
	}
	
/**
 *
 */	
    function setDBFieldValue(&$model, &$modelDetail = null, $field = null, $value = null, $returnValue = false) {
		if (!empty($modelDetail) && !empty($field)) {
			if ($returnValue === true) {
				$fieldDescriptor = $modelDetail->schema($field);
				return $this->__setDBFieldValue($fieldDescriptor, $value);
			}
		}
		else {
			foreach ($model->data as $k=>$v) {
				if ($model->name == $k) {
					foreach ($model->schema() as $field=>$fieldDescriptor) {
						if (in_array($field, array('created', 'modified', 'user_id', 'group_id', 'rol_id', 'permissions'))) {
							continue;
						}

						/**
						* Cuando es un edit (el campo id tiene valor), solo formateo los campos que se editaron.
						* Para un add, debo formatear todo.
						*/
						$value = null;
						if (isset($model->data[$model->name][$field])) {
							$value = $model->data[$model->name][$field];
						}
						if (empty($model->data[$model->name][$model->primaryKey])) {
							$model->data[$model->name][$field] = $this->__setDBFieldValue($fieldDescriptor, $value);
						} elseif (!is_null($value)){
							$model->data[$model->name][$field] = $this->__setDBFieldValue($fieldDescriptor, $value);
						}
					}
				} else {
					foreach ($model->data[$k] as $kDetail => $vDetail) {
						if (is_array($vDetail)) {
							foreach ($vDetail as $field=>$v) {
								if (in_array($field, array('created', 'modified', 'user_id', 'group_id', 'rol_id', 'permissions'))) {
									continue;
								}
								
								$value = null;
								if (isset($model->data[$k][$kDetail][$field])) {
									$value = $model->data[$k][$kDetail][$field];
								} if (empty($model->data[$k][$kDetail]['id'])) {
									$model->data[$k][$kDetail][$field] = $this->__setDBFieldValue($model->{$k}->schema($field), $value);
								} elseif (!is_null($value)){
									$model->data[$k][$kDetail][$field] = $this->__setDBFieldValue($model->{$k}->schema($field), $value);
								}
							}
						}
					}
				}
			}
		}
	}
	
/**
 * Convierte cualquier fecha a formato yyyy-mm-dd.
 *
 * @param object $model Model que esta siendo validado.
 * @param string $fecha La fecha en algun formato.
 * @return mixed La fecha en formato yyyy-mm-dd, false si no fue posible o no se trataba de una fecha valida.
 * @access private
 */
	function __getMySqlDate($fecha) {
		if (!empty($fecha) && (preg_match(VALID_DATETIME, $fecha, $matches) || preg_match(VALID_DATE, $fecha, $matches))) {
			return $matches[3] . '-' . $matches[2] . '-' . $matches[1] . substr($fecha, 10);
		}
		elseif (preg_match(VALID_DATE_MYSQL, $fecha)) {
			return $fecha;
		}
		return false;
	}

	
/**
 * Convierte cualquier fecha a formato yyyy-mm-dd.
 *
 * @param string $fecha La fecha en algun formato.
 * @return mixed La fecha en formato yyyy-mm-dd, false si no fue posible o no se trataba de una fecha valida.
 * @access public
 */
	function getMySqlDate(&$model, $fecha) {
		return $this->__getMySqlDate($fecha);
	}	
}
?>