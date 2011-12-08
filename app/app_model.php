<?php
/**
 * Model de la aplicacion.
 *
 * Todos los model heredan desde esta clase, por lo que defino metodos que usare en todos los models aca.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1380 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-27 21:27:32 -0300 (dom 27 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos de todo la aplicacion.
 *
 * @package     pragtico
 * @subpackage  app
 */
class AppModel extends Model {


/**
 * Format GroupBy queries to a normal cakePHP $results array.
 *
 */
	
	function afterFind($results, $primary = false) {
		if ($primary === true) {
			if (Set::check($results, '0.0')) {
				$fieldNames = array_keys($results[0][0]);
				foreach ($results as $key => $value) {
                    foreach ($fieldNames as $fieldName) {
					   $results[$key][$this->alias][$fieldName] = $value[0][$fieldName];
                    }
					unset($results[$key][0]);
				}
			}
		}
		return $results;
	}
	
	
    /**
    * TODO:
    * Deberia asegurarme que todo sea recursive = -1, y cuando lo necesite algun level mas de recursive,
    * logralo con el behavior contain().
    * var $recursive = -1;
    */

/**
 * Los behaviors que uso en todos los models.
 *
 * @var array
 * @access public
 */
    //var $actsAs = array("Containable", "Util", "Permisos", "Validaciones", "Crumbable");
    var $actsAs = array("Containable", "Util", "Permisos", "Validaciones");
	//var $actsAs = array("Containable", "Permisos", "Validaciones");
    //var $actsAs = array("Util", "Permisos", "Validaciones");


/**
 * Los permisos con los que se guardaran los datos por defecto.
 *
 * @var integer
 * @access protected
 */
    protected $__permissions = '496';


/**
 * Mantiene informacion de errores que se generen al intentar ejecutar consultas SQL.
 *
 * @var array
 * @access public
 */
    var $dbError;


/**
 * Mantiene informacion de warnings que se generen al intentar ejecutar consultas SQL.
 *
 * @var array
 * @access public
 */
    var $dbWarning;


/**
 * Extends cakePHP's saveAll method.
 * Improvements:
 *      - Takes care of multiple related records saving.
 *      - Validates all data before saving.
 *      - Deletes records when are not present in current data and relation property unique is set to true.
 *
 * @access public
 * @return boolean. True if all records where saved. False in case that one or more records could
 * not be saved, even thought it saves validad records.
 * TODO: ver $dbError = $this->{$this->modelClass}->getError();    
 */
    function appSave($data = array(), $options = array()) {

        //$options = array_merge(array('validate' => 'first', 'atomic' => false), $options);
		//$options = array_merge(array('validate' => 'first', 'callbacks' => 'after'), $options);
		$options = array_merge(array('validate' => 'first'), $options);
        
        /**
         * Must be alway an array.
         */
        if (!isset($data[0])) {
            $data = array($data);
        }

        $dataCount = $c = 0;
        $validationErrors = null;
        foreach ($data as $k => $v) {

            $dataCount++;
            $this->data = $v;

			/*
			$result = $this->Behaviors->trigger($this, 'beforeSave', array($options), array(
				'break' => true, 'breakOn' => false
			));
			if (!$result || !$this->beforeSave($options)) {
				$this->whitelist = $_whitelist;
				return false;
			}
			*/
			
            if (!$this->beforeSave()) {
                continue;
            }
			if (!empty($this->data)) {
				$data[$k] = array_merge($data[$k], $this->data);
			}

            /**
             * Must verify if all elements in the array belongs to the same model.
             * Maybe there're elements of a related model.
             */
            $tmp = $this->data;
            unset($tmp[$this->name]);
            $sameModel = empty($tmp);
            if ($sameModel === true) {
                $this->create($this->data);
                if ($this->saveAll($this->data[$this->name], $options)) {
                    $c += count($this->data);
                } elseif (!empty($this->validationErrors)) {
					$validationErrors[$k] = $this->validationErrors;
				}
                continue;
            }

            $find = array();
            $relatedData = $this->data;
            unset($relatedData[$this->name]);
            if (!empty($this->data[$this->name][$this->primaryKey])) {
                $findBy = 'findBy' . Inflector::camelize($this->primaryKey);
                $this->contain(array_keys($relatedData));
                $find = $this->{$findBy}($this->data[$this->name][$this->primaryKey]);
            }

            $associations = $this->getAssociated();
            foreach ($relatedData as $detailKey => $detailValue) {
                
                if (isset($associations[$detailKey])) {
                    /** Make sure to trigger behaviors for related models */
                    if ($associations[$detailKey] === 'hasMany') {
                        foreach ($detailValue as $hasManyKey => $hasManyValue) {
                            $this->{$detailKey}->data = array($detailKey => $hasManyValue);
                            $this->{$detailKey}->create($this->{$detailKey}->data[$detailKey]);
                            if (!$this->Behaviors->trigger($this->{$detailKey}, 'beforeSave', array($options), array(
                                'break' => true, 'breakOn' => false))) {
                                continue 3;
                            }
                            $this->data[$detailKey][$hasManyKey] = array_merge($this->data[$detailKey][$hasManyKey], $this->{$detailKey}->data[$detailKey]);
                        }
                    } elseif ($associations[$detailKey] === 'hasAndBelongsToMany') {
                        if (!empty($this->data[$detailKey])
                            && !empty($this->data[$detailKey]['id'])
                            && count($this->data[$detailKey]) === 1) {
                            $options['validate'] = false;
                            $ids = null;
                            foreach ($this->data[$detailKey]['id'] as $id) {
                                $ids[][$this->hasAndBelongsToMany[$detailKey]['associationForeignKey']] = $id;
                            }
                            unset($this->data[$detailKey]);
                            $this->data[$this->hasAndBelongsToMany[$detailKey]['with']] = $ids;
                            $this->bindModel(array('hasMany' => array($this->hasAndBelongsToMany[$detailKey]['with'])));
                        }
                    }


                    if (!empty($this->{$associations[$detailKey]}[$detailKey]['unique'])) {

                        if ($associations[$detailKey] !== 'hasAndBelongsToMany') {
                            $originalDetailsId = Set::extract('/' . $this->{$detailKey}->primaryKey, $find[$detailKey]);

                            $postedDetailsId = array();
                            foreach ($this->data[$detailKey] as $tv) {
                                if (!empty($tv[$this->{$detailKey}->primaryKey])) {
                                    $postedDetailsId[] = $tv[$this->{$detailKey}->primaryKey];
                                }
                            }

                            foreach (array_diff($originalDetailsId, $postedDetailsId) as $idToDelete) {
                                if (!$this->{$detailKey}->del($idToDelete)) {
                                    $errorsDeletingDetails = true;
                                }
                            }
                        }
                    }
                }
            }

            $this->create($this->data);
            if ($this->saveAll($this->data, $options) === true) {
                $c++;
            } else {
                /**
                 * If same model, cakePHP does'nt add modelClass to validationErrors array.
                 */
                if (Set::countDim($this->validationErrors) === 1) {
                    $validationErrors[$k][$this->name] = $this->validationErrors;

                    /**
                     * If parent model doesn't validate, cakePHP ends the job, but I prefer
                     * to olso validate details, so user can correct all errors at once.
                     */
                    foreach ($relatedData as $relatedModel => $relatedModelDataToValidate) {
                        if ($this->{$relatedModel}->saveAll($relatedModelDataToValidate, array('validate' => 'only')) === false) {
                            $validationErrors[$k][$relatedModel] = $this->{$relatedModel}->validationErrors;
                        }
                    }
                } else {
                    $validationErrors[$k] = $this->validationErrors;
                }
            }
        }


        $this->savedDataLog = array(
            'totalRecords'      => $dataCount,
            'totalRecordsSaved' => $c,
        );

        if ($c === $dataCount) {
            return true;
        } else {
			$this->validationErrors = $validationErrors;
            return false;
        }
    }


    function del($id = null, $cascade = true) {
        
        $this->order = null;
        //$this->setSecurityAccess('delete');
        
        /**
         * Asuming dependent related models, need to be deleted as a transaction.
         */
        $this->begin();
        
        if (parent::del($id, $cascade)) {
            $this->commit();
            return true;
        } else {
            $this->rollback();
            return false;
        }
    }
    

/**
 * I must overwrite default cakePHP deleteAll method because it's not performant when there're many 
 * relations and many records.
 * I also add transaccional behavior and a better error check.
 * TODO:
 *      when the relation has a dependant relation, this method will not delete that relation.
 */ 
    function deleteAll($conditions, $cascade = true, $callbacks = false, $fkSave = false) {

       if ($fkSave === true) {

            $ids = Set::extract(
                $this->find('all', array_merge(array(
                                'fields'    => $this->alias . '.' . $this->primaryKey,
                                'recursive' => -1), compact('conditions'))),
                '{n}.' . $this->alias . '.' . $this->primaryKey
            );

            if (!empty($ids)) {
                $db = ConnectionManager::getDataSource($this->useDbConfig);
                $c = 0;
                $db->begin($this);
                foreach ($this->hasMany as $assoc => $data) {
                    $table = $db->name(Inflector::tableize($assoc));
                    $sql = sprintf('DELETE FROM %s %s', $table, $db->conditions(array($data['foreignKey'] => $ids)));

                    $db->query($sql);

                    if (empty($db->error)) {
                        $c++;
                    }
                }

                if (count($this->hasMany) === $c) {
					$tableName = $db->name($this->useTable);
                    $sql = sprintf('DELETE FROM %s%s', $tableName, $db->conditions(array($tableName . '.' . $this->primaryKey => $ids)));
                    $db->query($sql);
					$errors = $this->getError();
                    if (empty($errors)) {
                        $db->commit($this);
                        return true;
                    } else {
                        $db->rollback($this);
                        return false;
                    }
                } else {
                    $db->rollback($this);
                    return false;
                }
            } else {
                return true;
            }
       } else {
           return parent::deleteAll($conditions, $cascade, $callbacks);
       }
    }


/**
 *
 *
 */
    function getError() {
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		return $db->error;
    }


/**
 * Retorna la variable $this->dbError con los warnings que puedan haber surgido de alguna query.
 *
 */
    function getWarning() {
        return $this->dbWarning;
    }

    
/**
 * Carga la variable (array) $this->dbWarning de la clase con los warnings.
 * Si un mensaje especifico para el motor no ha sido definido, retorna el mensaje de warning que genero la DB.
 *
 * @access private
 * @return void.
*/
    function __getWarnings() {
        //$warnings = $this->query("SHOW WARNINGS UNION SHOW ERRORS");
        //d("X");
        d($warnings);
        if (!empty($warnings)) {
            $c = 0;
            $quitar = array();
            foreach ($warnings as $v) {
                $w[$c]['warningRdbms'] = $v[0];
                $w[$c]['warningRdbmsNumero'] = $v[0]['Code'];
                $w[$c]['warningRdbmsDescripcion'] = $v[0]['Message'];
                switch($w[$c]['warningRdbmsNumero']) {
                    case "1265":
                        $tmp = str_replace("Data truncated for column '", "", $w[$c]['warningRdbmsDescripcion']);
                        $tmp = preg_replace("/' at row [0-9]+$/", "", $tmp);
                        $tableInfo = $this->schema();
                        /**
                        * Evita que me diga que trunco una fecha cuando esto no tiene importancia.
                        */
                        if (!empty($tableInfo[$tmp]['type']) && $tableInfo[$tmp]['type'] == "date") {
                            $quitar[] = $c;
                        }
                        else {
                            $w[$c]['warningDescripcion'] = "El campo " . inflector::humanize($tmp) . " quedo sin un valor asiganado.";
                        }
                        break;
                    default:
                        $w[$c]['warningDescripcion'] = $w[$c]['warningRdbmsDescripcion'];
                        break;
                }
                $c++;
            }
            
            foreach ($quitar as $v) {
                unset($w[$v]);
            }
            if (!empty($w)) {
                $this->dbWarning[] = $w;
            }
        }
    }


/**
 * Carga la variable (array) $this->dbError de la clase con los errores.
 * Si un mensaje especifico para el motor no ha sido definido, retorna el mensaje de error que genero la DB.
 *
 * @access private
 * @return void.
*/
    function __getDbLog($type = 'warning') {
        if ($type === 'warning') {
            $query = 'SHOW WARNINGS';
        } elseif ($type == 'error') {
            $query = 'SHOW ERRORS';
        }
        
        $logs = $return = array();
        foreach ($this->query($query) as $log) {
            $skip = false;
            $return['code'] = $log[0]['Code'];
            $return['message'] = $log[0]['Message'];
            switch($return['code']) {
                case 1364:
                case 1048:
                    if (preg_match('/\'([a-zA-Z0-9_]+)\'/', $return['message'], $matches)) {
                        $return['message'] = sprintf(__(str_replace($matches[1], '%s', $return['message']), true), $matches[1]);
                    }
                break;
                case 1105:
                    /** Unknown error */
                    $skip = true;
                break;
            }
            
            if ($skip === false) {
                $logs[] = $return;
            }
        }
        return $logs;

        
        if (!empty($error)) {
            $this->dbError['errorRdbms'] = array_pop(array_pop($error));
        }
        
        if (!empty($this->dbError['errorRdbms'])) {
            /**
             * Mensajes faciles de entender para el usuario.
             * La clave es el numero de error de MySQL.
             */
            $dbError = array (  "1062" => "El registro ya existe.",
                                "1064" => "Error de sintaxis en la instruccion SQL.",
                                "1054" => "Columna desconocida.",
                                "1048" => "La columna no puede contener un valor nulo.",
                                "1452" => "No es posible agregar/modificar el registro porque posee un registro relacionado.",
                                "1451" => "No es posible borrar/modificar el registro porque posee un registro relacionado.",
                                "1217" => "El registro esta siendo usado en otra tabla.");
                
            $this->dbError['model'] = $this->name;
            $this->dbError['errorRdbmsNumero'] = $this->dbError['errorRdbms']['Code'];
            $this->dbError['errorRdbmsDescripcion'] = $this->dbError['errorRdbms']['Message'];
            
            if (isset($dbError[$this->dbError['errorRdbmsNumero']])) {
                $this->dbError['errorDescripcion'] = $dbError[$this->dbError['errorRdbmsNumero']];
            }
            else {
                $this->dbError['errorDescripcion'] = $this->dbError['errorRdbmsDescripcion'];
            }
            /**
            * Intento buscar una descripcion adicional para mensaje de error, siempre y cuando el rdbms me de la opcion.
            */
            switch($this->dbError['errorRdbmsNumero']) {
                case 1064:
                    $this->dbError['errorRdbms']['Message'] = str_replace("  ", " ", str_replace("  ", " ", str_replace("\t", " ", str_replace("\n", "", $this->dbError['errorRdbms']['Message']))));
                    preg_match("/.+near (.+) at line.+/", $this->dbError['errorRdbms']['Message'], $matches);
                    $this->dbError['errorDescripcion'] = "El error puede provenir de " . $matches[1];
                    break;
                case 1048:
                    $this->dbError['errorDescripcion'] = "Ha intentado grabar un valor nulo en el campo " . up(preg_replace("/.+'([a-z]+)'.+/", "$1", $this->dbError['errorRdbmsDescripcion']));
                    break;
                case 1452:
                    preg_match("/REFERENCES `(.+)` \(/", $this->dbError['errorRdbms']['Message'], $matches);
                    $this->dbError['errorDescripcion'] = "Ha intentado agregar/modificar un registro que necesariamente necesita un registro relacionado de la tabla " . $matches['1'] . ".";
                    break;
                case 1451:
                    preg_match("/[a-z]+\/([a-z,_]+)`\,/", $this->dbError['errorRdbms']['Message'], $matches);
                    $this->dbError['errorDescripcion'] = "Ha intentado borrar/modificar un registro que esta relacionado con otro de la tabla " . $matches['1'] . ".";
                    break;
                case 1062:
                    $key = array_pop(explode(" for key ", $this->dbError['errorRdbmsDescripcion'])) - 1;
                    if (is_numeric($key)) {
                        /**
                        * Para el caso de mySql, las keys (constrains), el numero que indica cuando hay un error un una key,
                        * corresponde al numero de key devuelta por la query, asi, si indica, por ejemplo, el error
                        * SQL Error: 1062: Duplicate entry '45' for key 2, el key "2" corresponde al segundo registro devuelto
                        * por la query "SHOW KEYS FROM ..."
                        */
                        $keysTmp = $this->query("SHOW KEYS FROM " . $this->useTable);
                        foreach ($keysTmp as $v) {
                            $keys[$v['STATISTICS']['Key_name']][] = up($v['STATISTICS']['Column_name']);
                        }
                        $i=0;
                        foreach ($keys as $v) {
                            if ($i == $key) {
                                $campos = implode(", ", $v);
                                if (count($v) > 1) {
                                    $this->dbError['errorDescripcion'] = "Ha intentado grabar un valor que ya existe para la combinacion de campos " . $campos . " de la tabla " . up($this->useTable) . ".";
                                }
                                else {
                                    $this->dbError['errorDescripcion'] = "Ha intentado grabar un valor que ya existe para el campo " . $campos . " de la tabla " . up($this->useTable) . ".";
                                }
                                break;
                            }
                            $i++;
                        }
                    }
                break;
            }
        }
    }


	
	function getCrumb($data) {

		$breadCrumb = false;
		if (empty($this->breadCrumb)) {
			$breadCrumb['fields'][] = $this->name . '.' . $this->primaryKey;
		} else {
			if (is_string($this->breadCrumb)) {
				$breadCrumb['fields'][] = $this->breadCrumb;
			} else {
				$breadCrumb = $this->breadCrumb;
			}
		}
		
		if (!empty($data) && $breadCrumb !== false) {
			
			$texts = null;
			foreach ($breadCrumb['fields'] as $contents) {
				$c = explode('.', $contents);
				if (count($c) === 3) {
					$text = $data[$c[0]][$c[1]][$c[2]];
				} elseif (count($c) === 2) {
					$text = $data[$c[0]][$c[1]];
				} elseif (count($c) === 1) {
					$text = $data[$c[0]];
				}
				$texts[] = $text;
			}
			if (!empty($breadCrumb['format'])) {
				return vsprintf($breadCrumb['format'], $texts);
			} else {
				return implode(' ', $texts);
			}
		} else {
			return '';
		}
	}
	
}
?>