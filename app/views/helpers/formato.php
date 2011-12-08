<?php
/**
 * Helper que me facilita el formateo de string, numeros, etc.
 *
 * Dado un nombre predefinido, formateo cualquier cosa.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.views.helpers
 * @since           Pragtico v 1.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Clase que contiene el helper para el formateo.
 * Esta clase es muy importante, ya que si bien es un helper, el behavior Util y el component Util, utilizan metodos de esta clase.
 *
 * @package     pragtico
 * @subpackage  app.views.helpers
 */
class FormatoHelper extends AppHelper {

/**
 * Helpers I'll need.
 *
 * @var array
 * @access public.
 */
	var $helpers = array('Number', 'Time');

	
/**
 * Count iterations loops (used in replacement .{n}.).
 *
 * @var array
 * @access private.
 */
	var $__count = 0;
	
	
/**
 * Sets count var.
 */		
	function setCount($value) {
		$this->__count = $value;
	}
	
	
/**
 * Gets count var.
 */		
	function getCount() {
		return $this->__count;
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
	function getPatterns($text = '', $replacedPatterns = true, $patternStart = '#*', $patternEnd = '*#') {
		
		/**
		 * Ensure characters are scape before reg exp then.
		 */
		$patternStart = '\\' . implode('\\', str_split($patternStart));
		$patternEnd = '\\' . implode('\\', str_split($patternEnd));
			
		$search = sprintf('/%s(.+)%s/U', $patternStart, $patternEnd);
		
		preg_match_all($search, $text, $matches);
		if (!empty($matches)) {
			if ($replacedPatterns === true) {
				return $matches[1];
			} else {
				return $matches[0];
			}
		} else {
			return false;
		}
	}
	
	
/**
 * Search for an element of an array based on a given key.
 *
 * @param string $Key The key of the array. Ej: Model.Submodel.field
 * @param array $arrayData The array where to find the key.
 * @return mixed Array value based on given key if found. Key if not found.
 * @access private.
 */
	function __getTextFromArray($key, $arrayData) {

		if (strpos($key, '{n}') !== false) {
			$key = str_replace('{n}', $this->getCount(), $key);
		}

		$tmp = explode('.', $key);
		$cantidad = count($tmp);
		if ($cantidad === 2 && isset($arrayData[$tmp[0]][$tmp[1]])) {
			$return = $arrayData[$tmp[0]][$tmp[1]];
		} elseif ($cantidad === 3 && isset($arrayData[$tmp[0]][$tmp[1]][$tmp[2]])) {
			$return = $arrayData[$tmp[0]][$tmp[1]][$tmp[2]];
		} elseif ($cantidad === 4 && isset($arrayData[$tmp[0]][$tmp[1]][$tmp[2]][$tmp[3]])) {
			$return = $arrayData[$tmp[0]][$tmp[1]][$tmp[2]][$tmp[3]];
		} elseif ($cantidad === 5 && isset($arrayData[$tmp[0]][$tmp[1]][$tmp[2]][$tmp[3]][$tmp[4]])) {
			$return = $arrayData[$tmp[0]][$tmp[1]][$tmp[2]][$tmp[3]][$tmp[4]];
		} else {
			$return = $key;
		}
		return str_replace('\'', '', $return);
	}
	
/**
 * Search for patterns and replace by replaces into texts (string or text's array).
 *
 * @param array $patterns Patterns I'm searching for.
 *	if null, it'll be extracted from text with default options @see getPatterns.
 * @param array $replaces Replacements I'll made.
 *	if null, $patterns should be array(Pattern => Replacement).
 * @param mixed $texts The text (string o array) where to make replacements.
 * 
 * There can be expressed formats for replaced values:
 * 				Model[.SubModel1.][.SubModel2.].field:date
 * 				Model[.SubModel1.][.SubModel2.].field:date:default=>true;format=>d/m/Y
 *
 * Patterns can be expressed as numbers in the text:
 *				text = 'my name is #*1*#, I leave in #*2*#. I work in #*3*# and in #*3*#'
 * 				1:Model[.SubModel1.][.SubModel2.].field:date
 * 				2:Model[.SubModel1.][.SubModel2.].field:date:default=>true;format=>d/m/Y
 * 				3:Model.{n}.field
 * 
 * Iterations could be expressed:
 * 				Model.{n}.field
 *				Model[.SubModel1.].{n}.field
 *
 * Conditionals could be expressed:
 * 				if(Model.field>18,'older',younger')
 * 				if(Model.field='Argentina','cordoba','madrid')
 *
 * @return mixed Replaced text String or replaced array elements.
 * @access public.
 */
	function replace($patterns = null, $replaces = null, $texts = null, $nextRecord = true) {

		if (empty($texts)) {
			return false;
		}
		
		if (is_string($texts)) {
			$text = $texts;
		} else {

			foreach ($texts as $key => $text) {
				if (preg_match('/([A-Z]+)([0-9]+)/', $key, $colum)) {
					$tmps[$colum[1]][$colum[2]] = $text;
				}
			}

			if (!empty($tmps)) {
				$secondTmp = $tmps;
				$numbers = array_keys(array_pop($tmps));
				for ($i = 0; $i < count($numbers); $i++) {
					foreach ($secondTmp as $index => $value) {
						if (isset($value[$numbers[$i]])) {
							$ordered[$index . $numbers[$i]] = $value[$numbers[$i]];
							unset($texts[$index . $numbers[$i]]);
						}
					}
				}
				$texts = array_merge($texts, $ordered);
			}
			
			foreach ($texts as $key => $text) {
				$nextRecord = true;
				if (preg_match('/([A-Z]+)([0-9]+)/', $key, $colum)) {
					$letter = $colum[1];
					for ($letterIncrement = 0; $letterIncrement <= 26; $letterIncrement++) {
						$letter++;
						if (isset($texts[$letter . $colum[2]])) {
							$nextRecord = false;
							break;
						}
					}
				}
				$return[$key] = $this->replace($patterns, $replaces, $text, $nextRecord);
			}
			return $return;
		}
		
		if (empty($patterns)) {
			if (!($patterns = $this->getPatterns($text))) {
				return $text;
			}
		} elseif (empty($replaces)) {
			foreach ($patterns as $keys => $valor) {
				$tmp = explode('.', $keys);
				$cantidad = count($tmp);
				if ($cantidad === 2) {
					$replaces[$tmp[0]][$tmp[1]] = $valor;
				} elseif ($cantidad === 3) {
					$replaces[$tmp[0]][$tmp[1]][$tmp[2]] = $valor;
				} elseif ($cantidad === 4) {
					$replaces[$tmp[0]][$tmp[1]][$tmp[2]][$tmp[3]] = $valor;
				} elseif ($cantidad === 5) {
					$replaces[$tmp[0]][$tmp[1]][$tmp[2]][$tmp[3]][$tmp[4]] = $valor;
				} else {
					return $text;
				}
			}
			$patterns = array_keys($patterns);
		}
		$replaces = array_merge(array('Bar' => array('foo' => '')), (array)$replaces);
		

		foreach ($patterns as $pattern) {

			$key = null;
			$iterate = false;

			if (!isset($skip)) {
				$skip = 0;
			}
			
			if (preg_match('/^([0-9]+)\:(.+)$/', $pattern, $matches)) {
				$toReplace['#*' . $matches[1] . '*#'] = $this->__getTextFromArray(array_shift(explode('|', $matches[2])), $replaces);
				$toReplace['#*' . $pattern . '*#'] = '';
				$pattern = $matches[2];
				$key = $matches[1];
			}



			if (preg_match("/^if\((.*)([!=|<>|==|>=|<=]{2})(.*),(.*),(.*)\)$/", $pattern, $matches)
				|| preg_match("/^if\((.*)([>|<|=]{1})(.*),(.*),(.*)\)$/", $pattern, $matches)
				|| preg_match("/^if\((.*)([!=|<>|==|>=|<=]{2})(.*),(.*)\)$/", $pattern, $matches)
				|| preg_match("/^if\((.*)([>|<|=]{1})(.*),(.*)\)$/", $pattern, $matches)) {
				$condition = false;
				
				switch ($matches[2]) {
					case '==':
					case '=':
						if ($this->__getTextFromArray($matches[1], $replaces) == $this->__getTextFromArray($matches[3], $replaces)) {
							$condition = true;
						}
						break;
					case '>=':
						if ($this->__getTextFromArray($matches[1], $replaces) >= $this->__getTextFromArray($matches[3], $replaces)) {
							$condition = true;
						}
						break;
					case '<=':
						if ($this->__getTextFromArray($matches[1], $replaces) <= $this->__getTextFromArray($matches[3], $replaces)) {
							$condition = true;
						}
						break;
					case '>':
						if ($this->__getTextFromArray($matches[1], $replaces) > $this->__getTextFromArray($matches[3], $replaces)) {
							$condition = true;
						}
						break;
					case '<':
						if ($this->__getTextFromArray($matches[1], $replaces) < $this->__getTextFromArray($matches[3], $replaces)) {
							$condition = true;
						}
						break;
					case '!=':
					case '<>':
						if ($this->__getTextFromArray($matches[1], $replaces) != $this->__getTextFromArray($matches[3], $replaces)) {
							$condition = true;
						}
						break;
				}


				if ($condition) {
					$toReplace['#*' . $matches[0] . '*#'] = $this->__getTextFromArray($matches[4], $replaces);
				} else {
					if (isset($matches[5])) {
						$toReplace['#*' . $matches[0] . '*#'] = $this->__getTextFromArray($matches[5], $replaces);
					} else {
						$toReplace['#*' . $matches[0] . '*#'] = '';
					}
				}
				if (strpos($matches[0], '{n}') !== false) {
					if ($nextRecord === true) {
						$this->setCount($this->getCount() + 1);
					}
					foreach ($matches as $match) {
						if (strpos($match, '{n}') !== false) {
							$skip++;
						}
					}
				}
			}
			$skip--;
			
			
			/**
			* Search for specific formats.
			*/
			$firstTmp = explode('|', $pattern);
			$formato = null;
			if (!empty($firstTmp[1])) {
                $text = str_replace($pattern, $firstTmp[0], $text);
				$tmpFormatos = explode(':', $firstTmp[1]);
				$formato['type'] = $tmpFormatos[0];
				if (isset($tmpFormatos[0]) && isset($tmpFormatos[1])) {
					foreach (explode(';', $tmpFormatos[1]) as $options) {
						list($option, $value) = explode('=>', $options);
						if ($value === 'true') {
							$formato[$option] = true;
						} elseif ($value === 'false') {
							$formato[$option] = false;
						} else {
							$formato[$option] = $value;
						}
					}
				}
			}
			
			if ($key === null) {
				$key = $firstTmp[0];
			}
			
			$tmp = explode('.', $firstTmp[0]);
			$cantidad = count($tmp);

			if (strpos($pattern, '{n}') !== false && $skip <= 0) {
				$l = strlen($text);
				while ($pos = strpos($text, $key)) {
					$numericKey = str_replace('{n}', $this->getCount(), $firstTmp[0]);
					$keyLength = strlen($key);
					$text = substr($text, 0, $pos) . $numericKey . substr($text, $pos + $keyLength);
					if ($nextRecord === true) {
						$this->setCount($this->getCount() + 1);
					}
					$toReplace['#*' . $numericKey . '*#'] = $this->__getTextFromArray($numericKey, $replaces);
					if ($numericKey === $toReplace['#*' . $numericKey . '*#']) {
						$toReplace['#*' . $numericKey . '*#'] = '';
					}
				}
				unset($toReplace['#*' . $key . '*#']);
			} elseif (!isset($toReplace['#*' . $key . '*#'])) {
				$toReplace['#*' . $key . '*#'] = $this->__getTextFromArray($key, $replaces);
			}

			if (!empty($formato)) {
				$toReplace['#*' . $key . '*#'] = $this->format($toReplace['#*' . $key . '*#'], $formato);
			}
		}

		return str_replace(array_keys($toReplace), $toReplace, $text);
	}


/**
 * Formatea un valor de acuerdo a un formato.
 *
 * @param string $valor Un valor a formatear.
 * @param mixed 	array $options Opciones que contiene el tipo de formato y/o sus opciones.
 *					string El tipo de formato (sin opciones) que se desea.
 * @return mixed 	Un string o un array con el/los valor/es formateado/s de acuerdo a lo especificado.
 * @access public.
 */
	function format($valor, $options = array()) {
		if (is_string($options)) {
			$tmp = $options;
			$options = array();
			$options['type'] = $tmp;
		}

		$return = $valor;
		$options = array_merge(array('type'=>'numero'), $options);
		$type = $options['type'];
		unset($options['type']);
		
		switch($type) {
			case 'periodo':
                if (is_array($valor)) {
                    if (isset($valor['ano']) && isset($valor['mes'])) {
                        $return = $valor['ano'] . str_pad($valor['mes'], 2, '0', STR_PAD_LEFT);
                        if (isset($valor['periodo'])) {
                            $return .= strtoupper($valor['periodo']);
                        }
                    } else {
                        $return = false;
                    }
                } else {
                    $valor = strtoupper($valor);
                    if (!empty($valor) &&
                            (preg_match(VALID_PERIODO, $valor, $matches)
                            || preg_match('/^(20\d\d)(0[1-9]|1[012])$/', $valor, $matches)
                            || preg_match('/^(20\d\d)([A|F])$/', $valor, $matches))) {
                        $tmp = null;
                        $tmp['periodoCompleto'] = $matches[0];
                        $tmp['ano'] = $matches[1];
                        $tmp['mes'] = $matches[2];
                        $tmp['periodo'] = (!empty($matches[3]))?$matches[3]:array('M', '1Q', '2Q', 'F', '1S', '2S');
                        if (in_array($matches[2], array('A', 'F'))) {
                            $tmp['mes'] = '00';
                            $tmp['periodo'] = $matches[2];
                        }
                        $value = array(	'mes'	=> $tmp['mes'],
                                        'ano'	=> $tmp['ano']);

                        if ($tmp['periodo'] === '1Q') {
                            $value = array_merge($value, array('dia' => '01'));
                            $fechaDesde = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                            $value = array_merge($value, array('dia' => '15'));
                            $fechaHasta = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                        } elseif ($tmp['periodo'] === '2Q') {
                            $value = array_merge($value, array('dia' => '16'));
                            $fechaDesde = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                            $value = array_merge($value, array('dia' => $this->format($value, array('type' => 'ultimoDiaDelMes'))));
                            $fechaHasta = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                        } elseif ($tmp['periodo'] === '1S') {
                            $value = array_merge($value, array('dia' => '01', 'mes' => '01'));
                            $fechaDesde = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                            $value = array_merge($value, array('dia' => '30', 'mes' => '06'));
                            $fechaHasta = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                        } elseif ($tmp['periodo'] === '2S') {
                            $value = array_merge($value, array('dia' => '01', 'mes' => '07'));
                            $fechaDesde = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                            $value = array_merge($value, array('dia' => '31', 'mes' => '12'));
                            $fechaHasta = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                        } elseif ($tmp['periodo'] === 'M' || $tmp['periodo'] === 'F' || in_array('M', (array)$tmp['periodo'])) {
                            $value = array_merge($value, array('dia' => '01'));
                            $fechaDesde = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                            $value = array_merge($value, array('dia'=>$this->format($value, array('type' => 'ultimoDiaDelMes'))));
                            $fechaHasta = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                        } elseif ($tmp['periodo'] === 'A') {
                            $value = array_merge($value, array('dia' => '01', 'mes' => '01'));
                            $fechaDesde = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                            $value = array_merge($value, array('dia' => '31', 'mes' => '12'));
                            $fechaHasta = $this->format($value, array('type' => 'date', 'format' => 'Y-m-d'));
                        }
                        $tmp['desde'] = $fechaDesde;
                        $tmp['hasta'] = $fechaHasta;
                        $return = $tmp;

					} else if (is_numeric($valor) && $valor > 2000 && $valor < 2035) {

                        $return = array('desde' => $valor . '-01-01', 'hasta' => $valor . '-12-31');

                    } else {
						
                        $return = false;
                    }
                }
				break;		
			case 'date':
				if (is_array($valor) && !empty($valor['dia']) && !empty($valor['mes']) && !empty($valor['ano']) && is_numeric($valor['dia']) && is_numeric($valor['mes']) && is_numeric($valor['ano'])) {
					$tmp = null;
					$tmp = $valor['ano'] . '-' . str_pad($valor['mes'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($valor['dia'], 2, '0', STR_PAD_LEFT);
					$valor = null;
					$valor = $tmp;
				}
				$options = array_merge(array('default'=>true), $options);
				$fecha = trim(substr($valor, 0, 10));
				if (empty($fecha) && $options['default'] === true) {
					if (!isset($options['format'])) {
						$options['format'] = 'Y-m-d';
					}
					$fecha = date('Y-m-d');
				}

                if (!empty($fecha)) {
					if (preg_match(VALID_DATE_MYSQL, $fecha, $matches)) {
						if (!isset($options['format'])) {
                            $options['format'] = 'Y-m-d';
						}
						$return = $this->Time->format($options['format'], $fecha);
					} elseif ($fecha === '0000-00-00') {
						$return = '';
					}
				}
				break;
			case 'dateTime':
			case 'datetime':
				$fecha = substr($valor, 0, 10);
				$optionsTmp = $options;
				unset($optionsTmp['format']);
				$return = $this->format($fecha, array_merge($optionsTmp, array('type' => 'date')));
				if (!isset($options['format'])) {
					$options['format'] = 'H:i:s';
				}
				$hora = substr($valor, 10);
				if (empty($hora) && empty($return) && $options['default'] === false) {
					$return = '';
				} else {
					if (empty($hora)) {
						$hora = '00:00:00';
					}
					$return .= ' ' . $this->Time->format($options['format'], $hora);
				}
			break;
			case 'numero':
			case 'number':
				$options = array_merge(array('before' => '', 'thousands' => '', 'decimals' => ','), $options);
				$return = $this->Number->format($valor, $options);
				break;
			case 'moneda':
			case 'currency':
				$options['type'] = 'number';
				$return = $this->format($valor, array_merge(array('before' => '$ '), $options));
				break;
			case 'percentage':
				$options['type'] = 'number';
				$return = $this->format($valor, array_merge(array('after' => ' %'), $options));
				break;
			case 'ano':
			case 'mes':
			case 'dia':
				$valor = $this->format($valor, array_merge(array('type' => 'date', 'format' => 'Y-m-d'), $options));
				if (empty($valor)) {
					$return = $valor;
				} else {
					if ($type === 'dia') {
						$return = $this->Time->format('d', $valor);
					} elseif ($type === 'mes') {
						$return = $this->Time->format('m', $valor);
					} elseif ($type === 'ano') {
						$return = $this->Time->format('Y', $valor);
					}
				}
				break;
			case 'ultimoDiaDelMes':
				$return = $this->Time->format('d', mktime(0, 0, 0, ($this->format($valor, array('type' => 'mes')) + 1), 0, $this->format($valor, array('type' => 'ano'))));
				break;
			case 'diaAnterior':
				$return = $this->Time->format('d', mktime(0, 0, 0, $this->format($valor, array('type' => 'mes')), ($this->format($valor, array('type' => 'dia')) - 1), $this->format($valor, array('type' => 'ano'))));
				break;
			case 'mesAnterior':
				$return = $this->Time->format('m', mktime(0, 0, 0, $this->format($valor, array('type' => 'mes')), 0, $this->format($valor, array('type' => 'ano'))));
				break;
			case 'anoAnterior':
				$return = $this->format($valor, array('type' => 'ano')) - 1;
				break;
			case '1QAnterior':
				if ($this->format($valor, array('type' => 'dia')) <= 15) {
					$mes = $this->format($valor, array('type' => 'mesAnterior'));
					if ($mes == 12) {
						$ano = $this->format($valor, array('type' => 'anoAnterior'));
					} else {
						$ano = $this->format($valor, array('type' => 'ano'));
					}
				} else {
					$mes = $this->format($valor, array('type' => 'mes'));
					$ano = $this->format($valor, array('type' => 'ano'));
				}
				$return = $ano . $mes . '1Q';
				break;
			case '2QAnterior':
				$mes = $this->format($valor, array('type' => 'mesAnterior'));
				if ($mes == 12) {
					$ano = $this->format($valor, array('type' => 'anoAnterior'));
				} else {
					$ano = $this->format($valor, array('type' => 'ano'));
				}
				$return = $ano . $mes . '2Q';
				break;
			case '1SAnterior':
				if ($this->format($valor, 'mes') >= 7) {
					$return = $this->format($valor, 'ano') . '061S';
				} else {
					$return = $this->format($valor, array('type' => 'anoAnterior')) . '061S';
				}
				break;
			case '2SAnterior':
				if ($this->format($valor, 'mes') >= 7) {
					$return = $this->format($valor, 'ano') . '061S';
				} else {
					$return = $this->format($valor, array('type' => 'anoAnterior')) . '122S';
				}
				break;
				break;
			case 'mensualAnterior':
				$mes = $this->format($valor, array('type' => 'mesAnterior'));
				if ($mes == 12) {
					$ano = $this->format($valor, array('type' => 'anoAnterior'));
				} else {
					$ano = $this->format($valor, array('type' => 'ano'));
				}
				$return = $ano . $mes . 'M';
				break;
            case 'final':
                $mes = $this->format($valor, array('type' => 'mesAnterior'));
                if ($mes == 12) {
                    $ano = $this->format($valor, array('type' => 'anoAnterior'));
                } else {
                    $ano = $this->format($valor, array('type' => 'ano'));
                }
                $return = $ano . $mes . 'F';
                break;
			case 'periodoEnLetras':
				$options = array_merge(array('case' => 'lower'), $options);
				$beforeShort = '';
				if (preg_match(VALID_PERIODO, $valor, $matches)) {
					$before = '';
					if (substr($matches[3], 0, 1) == '1') {
						$before = 'Primera quincena de ';
						$beforeShort = $matches[3] . ' ';
					} elseif (substr($matches[3], 0, 1) == '2') {
						$before = 'Segunda quincena de ';
						$beforeShort = $matches[3] . ' ';
					}
					$mes = $this->__getMonths((int)$matches[2]);
					$ano = $matches[1];
				} elseif (preg_match('/(\d\d\d\d)(\d)S/', strtoupper($valor), $matches)) {
					$ano = $matches[1];
					$mes = '';
                    $beforeShort = $matches[2] . 's';
					if ($matches[2] == 1) {
						$before = $this->__getMonths(1) . ' a ' . $this->__getMonths(6);
					} else {
						$before = $this->__getMonths(7) . ' a ' . $this->__getMonths(12);
					}
				} elseif (strlen($valor) === 6 || strlen($valor) === 5) {
					$before = '';
					$ano = substr($valor, 0, 4);
					$mes = $this->__getMonths((int)substr($valor, 4, 2));
                } elseif (preg_match('/(\d\d\d\d)(\d\d)F/', strtoupper($valor), $matches)) {
                    $ano = $matches[1];
                    $mes = $this->__getMonths($matches[2]);
                    $beforeShort = '';
                    $before = 'final a ';
				}
				
				if (!empty($options['short'])) {
					$return = $beforeShort . substr($mes, 0, 3) . ' ' . substr($ano, -2);
				} else {
					$return = $before . $mes . ' de ' . $ano;
				}
				$return = $this->__case($return, $options['case']);
				break;
			case 'mesEnLetras':
				$options = array_merge(array('short' => false, 'case' => 'lower', 'keyStart' => 1), $options);
				$meses = $this->__getMonths(null, $options['keyStart']);
				if (strtolower($valor) === 'all') {
					$tmp = null;
					foreach ($meses as $k => $mes) {
						if (!empty($options['short'])) {
							$tmp[$k] = substr($this->__case($mes, $options['case']), 0, 3);
						} else {
							$tmp[$k] = $this->__case($mes, $options['case']);
						}
					}
					$return = $tmp;
				} else {
					$mes = (int)$this->format($valor, array('type' => 'mes'));
					if (!empty($options['short'])) {
						$return = substr($this->__case($meses[$mes], $options['case']), 0, 3);
					} else {
						$return = $this->__case($meses[$mes], $options['case']);
					}
				}
				break;
			case 'numeroEnLetras':
				$options = array_merge(array('places'=>2, 'case' => 'lower', 'decimals' => '.', 'option' => 'palabras', 'ceroCents'=>false), $options);
				unset($options['type']);
				$valor = $this->format($valor, $options);

				set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors');
				App::import('Vendor', 'Words', true, array(APP . 'vendors' . DS . 'Numbers'), 'Words.php');
				$nw = new Numbers_Words();
				if ($options['option'] == 'moneda') {
					$return = $nw->toCurrency($valor, 'es_AR');
				} else if ($options['option'] == 'palabras') {
					$return = $nw->toWords($valor, 'es_AR');
				}
				if ($options['ceroCents'] === false) {
					$return = str_replace(' con cero centavos', '', $return);
					$return = str_replace(' con cero', '', $return);
				}
				/**
				* Corrijo errores de la clase
				*/
				$return = str_replace(' con veintiuno centavos', ' con veintiun centavos', $return);
				$return = $this->__case($return, $options['case']);
				break;
		}
		return $return;
	}

	
/**
 * Convierte en texto en mayusculas, minusculas o titulo (ucfirst).
 *
 * @param $data mixed Array unidimensional o string con el texto a convertir.
 * @param $case string Especifica como convertir. Las posibilidades son:
 *				- ucfirst (default)
 *				- upper
 *				- lower
 * @return mixed Array convertido cuando el input haya sido un array, sino, un string.
 * @access private.
 */
	function __case($data, $case = 'ucfirst') {
		$esString = false;
		if (!is_array($data) && is_string($data)) {
			$data = array($data);
			$esString = true;
		}
		if ($case === 'upper') {
			foreach ($data as $k => $v) {
				$data[$k] = strtoupper($v);
			}
		} elseif ($case === 'lower') {
			foreach ($data as $k => $v) {
				$data[$k] = strtolower($v);
			}
		} elseif ($case === 'ucfirst') {
			foreach ($data as $k => $v) {
				$data[$k] = ucfirst($v);
			}
		}
	
		if ($esString) {
			return $data[0];
		}
		return $data;
	}
	

/**
 * Genera un array (key=>value) con los meses.
 *
 * @param $mes Integer Opcional que indica el numero mes que se pretende retorne.
 * @return mixed 	array (key=>value) La key contine el numero del mes y el value el nombre del mes.
 *					string El nombre del mes solicitado.
 * @access private.
 */
	function __getMonths($mes = null, $keyStart = 1) {

        
		$meses[$keyStart++] = __('enero', true);
		$meses[$keyStart++] = __('febrero', true);
		$meses[$keyStart++] = __('marzo', true);
		$meses[$keyStart++] = __('abril', true);
		$meses[$keyStart++] = __('mayo', true);
		$meses[$keyStart++] = __('junio', true);
		$meses[$keyStart++] = __('julio', true);
		$meses[$keyStart++] = __('agosto', true);
		$meses[$keyStart++] = __('septiembre', true);
		$meses[$keyStart++] = __('octubre', true);
		$meses[$keyStart++] = __('noviembre', true);
		$meses[$keyStart] = __('diciembre', true);
        
        /*
        $meses[$keyStart++] = __('january', true);
        $meses[$keyStart++] = __('february', true);
        $meses[$keyStart++] = __('march', true);
        $meses[$keyStart++] = __('april', true);
        $meses[$keyStart++] = __('may', true);
        $meses[$keyStart++] = __('june', true);
        $meses[$keyStart++] = __('july', true);
        $meses[$keyStart++] = __('august', true);
        $meses[$keyStart++] = __('september', true);
        $meses[$keyStart++] = __('october', true);
        $meses[$keyStart++] = __('november', true);
        $meses[$keyStart] = __('december', true);
        
		$meses['1'] = 'enero';
		$meses['2'] = 'febrero';
		$meses['3'] = 'marzo';
		$meses['4'] = 'abril';
		$meses['5'] = 'mayo';
		$meses['6'] = 'junio';
		$meses['7'] = 'julio';
		$meses['8'] = 'agosto';
		$meses['9'] = 'setiembre';
		$meses['10'] = 'octubre';
		$meses['11'] = 'noviembre';
		$meses['12'] = 'diciembre';
		*/
		if (is_numeric($mes)) {
            $mes = (int)$mes;
			if (isset($meses[$mes])) {
				return $meses[$mes];
			} else {
				return '';
			}
		}
		return $meses;
	}
}
?>