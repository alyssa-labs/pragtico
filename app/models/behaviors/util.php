<?php
/**
 * Behavior que contiene utilidades varias para ser usadas en los models.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models.behaviors
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1320 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-26 08:56:19 -0300 (mié 26 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 *Intento concentrar en esta clase todo los comportamientos reusables a nivel de models.
 *
 *
 * @package     pragtico
 * @subpackage  app.models.behaviors
 */
//class UtilBehavior extends ModelBehavior {
//App::import('Behavior', 'Containable');
class UtilBehavior extends ContainableBehavior {	

	

/**
 * TODO: Must remove this methis and behavior as soon as possible.
 * Must move file to fs. Db files get more problems that benefits
 *
 * De un archivo subido, lo parseo y lo deja disponible para cargarlo en la base de datos.
 *
 * @param object $model Model que usa este behavior.
 * @param array $opciones. Ej:
 *			- array("validTypes" => array("text/rtf", "application/msword"))
 * @return true si fue posible parsear el archivo subido, false, en cualquier otro caso.
 * @access public
 */
	function getFile(&$model, $opciones = array()) {
		if (!empty($model->data[$model->name]['archivo']['name'])) {
			if (isset($model->data[$model->name]['archivo']['error']) && $model->data[$model->name]['archivo']['error'] === 0) {
				if (!empty($opciones['validTypes'])) {
					if (!in_array($model->data[$model->name]['archivo']['type'], $opciones['validTypes'])) {
						$model->dbError['errorDescripcion'] = "El archivo No corresponde al tipo esperado (" . implode(", ", $opciones['validTypes']) . ")";
						return false;
					}
				}
				$contenido = fread(fopen($model->data[$model->name]['archivo']['tmp_name'], "r"), $model->data[$model->name]['archivo']['size']);
				$model->data[$model->name]['file_size'] = $model->data[$model->name]['archivo']['size'];
				$model->data[$model->name]['file_type'] = $model->data[$model->name]['archivo']['type'];
				$model->data[$model->name]['file_data'] = $contenido;
				unset($model->data[$model->name]['archivo']);
				return true;
			}
			else {
				$model->dbError['errorDescripcion'] = "El archivo no se subio correctamente. Intentelo nuevamente.";
				return false;
			}
		}
		return true;
	}

	
/**
 * A partir de un periodo expresado en formato string, retorna un array de ano, mes y periodo.
 *
 * @param object $model Model que usa este behavior.
 * @param string $periodo El periodo que sea convertir en array.
 * @return mixed Array con los datos ya separados de la forma:
 *			$return['ano'] = "2007";
 *			$return['mes'] = "12";
 *			$return['periodo'] = "M";
 *			$return['primerDia'] = "1";
 *			$return['ultimoDia'] = "31";
 *			$return['fechaInicio'] = "2007-12-01";
 *			$return['fechaFin'] = "2007-12-31";
 * false, en cualquier otro caso.		
 * @access public
 */
	function getPeriodo(&$model, $periodo) {
		return $this->traerPeriodo(&$model, $periodo);
	}

/**
 * TODO:
 * deprecar este metodo.
 */	 
	function traerPeriodo(&$model, $periodo) {
		$periodo = strtoupper($periodo);
		if (!empty($periodo) && preg_match(VALID_PERIODO, $periodo, $matches)) {

			$return['periodoCompleto'] = $matches[0];
			if ($matches[3] == "M") {
				$return['tipo'] = "Mensual";
			}
			else {
				$return['tipo'] = "Quincenal";
			}
			$return['ano'] = $matches[1];
			$return['mes'] = $matches[2];
			$return['periodo'] = $matches[3];
			if ($matches[3] === "M" || $matches[3] === "1Q") {
				$return['primerDia'] = "1";
				$return['fechaInicio'] = $return['ano'] . "-" . $return['mes'] . "-01";
 			}
 			else {
				$return['primerDia'] = "16";
				$return['fechaInicio'] = $return['ano'] . "-" . $return['mes'] . "-16";
 			}
			if ($matches[3] === "M" || $matches[3] === "2Q") {
				$return['ultimoDia'] = $this->format($return['fechaInicio'], array("type" => "ultimoDiaDelMes"));
				$return['fechaFin'] = $return['ano'] . "-" . $return['mes'] . "-" . $return['ultimoDia'];
 			}
 			else {
				$return['ultimoDia'] = "15";
				$return['fechaFin'] = $return['ano'] . "-" . $return['mes'] . "-15";
 			}
			return $return;
		}
		return false;
	}


/**
 * Formatea un valor de acuerdo a un formato.
 *
 * @param object $model Model que usa este behavior.
 * @param string $valor Un valor a formatear.
 * @param array $options Array que contiene el tipo de formato y/o sus opciones.
 * @return string Un string con el valor formateado de acuerdo a lo especificado.
 * @access public
 */
	function format(&$Model, $valor, $options = array()) {
		App::import("Helper", array("Number", "Time", "Formato"));
		$formato = new FormatoHelper();
		$formato->Time = new TimeHelper();
		$formato->Number = new NumberHelper();
		return $formato->format($valor, $options);
	}


/**
 * Gets patterns out of text.
 *
 * @param string $text The text from where to get the patterns.
 * @param boolean $replacedPatterns True if should return replaced patterns, 
 *									False if should return exact pattern match.
 * @param string $patternStart The begining of the pattern. Default: #*
 * @param string $patternEnd The ending of the pattern. Default: *#
 *
 * @return mixed Array with matched patterns. False in other case.
 * @access public.
 */
	function getPatterns(&$Model, $text = '', $replacedPatterns = true, $patternStart = '#*', $patternEnd = '*#') {
		App::import('Helper', array('Formato'));
		$formato = new FormatoHelper();
		return $formato->getPatterns($text, $replacedPatterns, $patternStart, $patternEnd);
	}


/**
 * Genera una query.
 * En debug level > 0, tambien la formatea para una mejor visualizacion.
 *
 * @param object $model Model que usa este behavior.
 * @param array $data Los datos para generar una query.
 * @param object $modelPreferido Model que usa deberia utilizar en lugar del model que usa el behavior para armar la query.
 * @return string Una query lista para ser ejecuta en la DB.
 * @access public
 */
	function generarSql_deprecated(&$model, $data, &$modelPreferido = null) {

		if (empty($modelPreferido)) {
			$modelPreferido = $model;
		}
		
		$db =& ConnectionManager::getDataSource($modelPreferido->useDbConfig);
		$default = array(
			"table"		=> Inflector::tableize($modelPreferido->name),
			"limit"		=> null,
			"offset" 	=> null,
			"fields" 	=> array(),
			"conditions"=> null,
			"order"		=> null,
			"joins"		=> array(),
			"group" 	=> null);

		$queryData = array_merge($default, $data);
		$queryData['alias'] = $db->name(Inflector::classify($queryData['table']));
		
		if (empty($queryData['fields'])) {
			$queryData['fields'] = $db->fields($modelPreferido);
		}
		else {
			$queryData['fields'] = $db->fields($modelPreferido, null, $queryData['fields']);
		}
		foreach ($queryData['joins'] as $k=>$v) {
			$queryData['joins'][$k]['table'] = $db->name($v['table']);
			if (empty($queryData['joins'][$k]['alias'])) {
				$queryData['joins'][$k]['alias'] = $db->name(Inflector::classify($v['table']));
			}
			if (empty($v['conditions'])) {
				$queryData['joins'][$k]['conditions'][] = $queryData['alias'] . ".id = " . $queryData['joins'][$k]['alias'] . "." . strtolower($queryData['alias']) . "_id";
			}
		}
		
		$sql = $db->buildStatement($queryData, $modelPreferido);

		/**
		* Parseo la query sql muy simple y rapidamente, de modo de poder ver la query mas facilmente cuando debugeo.
		* Hay mucho para mejorar en este parseo....
		*/
		if (Configure::read("debug") > 0) {
			$sql = preg_replace("/(^SELECT)/", "\n$1\t\t", $sql);
			$sql = str_replace(",", ",\n\t\t", $sql);
			$sql = str_replace("FROM", "\nFROM\t\t", $sql);
			$sql = str_replace("LEFT JOIN", "\n\t\tLEFT JOIN", $sql);
			$sql = str_replace("INNER JOIN", "\n\t\tINNER JOIN", $sql);
			$sql = preg_replace("/(LEFT JOIN.*)(ON.*)/", "$1\n\t\t\t$2", $sql);
			$sql = preg_replace("/(INNER JOIN.*)(ON.*)/", "$1\n\t\t\t$2", $sql);
			$sql = preg_replace("/(WHERE)(.*)/", "\n$1\t\t$2", $sql);
			$tmp = explode("WHERE", $sql);
			if (isset($tmp[0]) && isset($tmp[1])) {
				$tmp[0] = preg_replace("/(ON.*)(AND.*)/", "$1\n\t\t\t\t$2", $tmp[0]);
				$tmp[1] = implode("\n\t\t\tIN", explode("IN", $tmp[1]));
				$tmp[1] = str_replace(",\n\t\t", ",", $tmp[1]);
				$sql = $tmp[0] . "WHERE" . implode("\nAND\t\t", explode("AND", $tmp[1]));
			}
			$sql = str_replace("ORDER BY", "\nORDER BY\t", $sql);
			$sql = str_replace("GROUP BY", "\nGROUP BY\t", $sql);
			$tmp = explode("ORDER", $sql);
			if (isset($tmp[0]) && isset($tmp[1])) {
				$tmp[1] = str_replace(",", ",\n\t\t", $tmp[1]);
				$sql = $tmp[0] . "ORDER" . $tmp[1];
			}
			$tmp = explode("GROUP", $sql);
			if (isset($tmp[0]) && isset($tmp[1])) {
				$tmp[1] = str_replace(",", ",\n\t\t", $tmp[1]);
				$sql = $tmp[0] . "GROUP" . $tmp[1];
			}
			$sql = str_replace("\t ", "\t", $sql);
		}
		return $sql;
	}



}
?>