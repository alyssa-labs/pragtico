<?php
/**
 * Resolv formulas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.vendors.pragmatia
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 267 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-16 13:24:25 -0200 (lun, 16 feb 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
 set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes');
/** PHPExcel_Calculation */
 App::import('Vendor', 'Calculation', true, array(APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel'), 'Calculation.php');

class MyPHPExcel_Calculation extends PHPExcel_Calculation {

	protected function _raiseFormulaError($errorMessage) {
		$this->formulaError = $errorMessage;
	}

}


/**
 * Resolv formulas Class.
 *
 * @package     pragtico
 * @subpackage  app.vendors.pragmatia
 */
class Formulas {

	
/**
 * PHPExcel Calculation object.
 *
 * @var object
 * @access private
 */
	private $__PHPExcel_Calculation = null;
	
	
/**
 * Instance PHPExcel's object.
 *
 * @return void
 * @access public
 */
    function __construct() {
		$this->__PHPExcel_Calculation = new MyPHPExcel_Calculation();
	}


	function checkFormula($formula, $returnError = true) {

		$this->__PHPExcel_Calculation->formulaError = null;

		$formula = substr($this->__cleanUp($formula), 1);


		/** Search for vars and concepts */
		preg_match_all('/[#|@][0-9a-z_]+/', $formula, $matchesA);

		/** Search for strings */
		preg_match_all('/[\'\"]{1}[a-zA-Z0-9\s]+[\'\"]{1}/i', $formula, $matchesB);

		/** Search for functions (based on phpexcel calculation regexp to identify formulas) */
		preg_match_all('/@?([A-Z][A-Z0-9\.]*)[\s]*\(/i', $formula, $matchesC);

		/** Search for reserved words */
		preg_match_all('/Remunerativo|Deduccion|No\sRemunerativo+/', $formula, $matchesD);


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

		$replacedFormula = str_ireplace($searchs, '0', $formula);

		preg_match_all('/[a-z]+/', $replacedFormula, $matches);
		if (!empty($matches[0])) {
			return implode(' ', $matches[0]);
		}


		$this->__PHPExcel_Calculation->parseFormula($formula);
		if (empty($this->__PHPExcel_Calculation->formulaError)) {
			return true;
		} elseif ($returnError) {
			return $this->__PHPExcel_Calculation->formulaError;
		} else {
			return false;
		}
	}


/**
 * Remove spaces and "strange" characters from the formula.
 *
 * @param string $formula. The formula to be resolved.
 * @return string The ready to use formula.
 * @access private
 */
	function __cleanUp($formula) {
		/** Replace spaces in formulas to unify criterias.*/
		$formula = preg_replace('/\s*([=|<|>|,|\(|\)])\s*/', '$1', $formula);
		$formula = preg_replace('/(\d)\s{0,}\/\s{0,}(\d)/', '$1/$2', $formula);
		$formula = preg_replace('/[^[:print:]]/', '', $formula);
		if (substr($formula, 0, 1) !== '=') {
			$formula = '=' . $formula;
		} elseif (substr($formula, 0, 2) === '==') {
            $formula = substr($formula, 1);
        }

		$formula = str_replace('\'', '"', $formula);
        $formula = str_replace('#N/E', '0', $formula);
        $formula = str_replace('#VALUE!', '0', $formula);
        $formula = str_replace('#N/A', '0', $formula);
        $formula = str_replace('#NUM!', '0', $formula);
        $formula = str_replace('#DIV/0!', '0', $formula);

		return $formula;
	}


/**
 * Resolv the formula.
 *
 * @param string $formula. The formula to resolv.
 * @return mixed The result of the resolved formula.
 * @access public
 */
	function resolver($formula) {

		$this->__PHPExcel_Calculation->formulaError = null;
		$formula = $this->__cleanUp($formula);
        $formula = preg_replace("/isblank\(\'?0000\-00\-00\'?\)/", 'true', $formula);
        $formula = preg_replace("/isblank\(\'?\d\d\d\d\-\d\d\-\d\d\'?\)/", 'false', $formula);
        
		if (preg_match_all("/date\(\'?(\d\d\d\d)-(\d\d)-(\d\d)\'?\)/", $formula, $strings)) {
            foreach (array_unique($strings[0]) as $k => $string) {
                $formula = str_replace($string, sprintf('date(%s, %s, %s)', $strings[1][$k], $strings[2][$k], $strings[3][$k]), $formula);
            }
		} elseif (preg_match_all("/\'?(?!\")(\d\d\d\d)-(\d\d)-(\d\d)\'?(?!\")/", $formula, $strings)) {
            foreach (array_unique($strings[0]) as $k => $string) {
                $formula = str_replace($string, sprintf('date(%s,%s,%s)', $strings[1][$k], $strings[2][$k], $strings[3][$k]), $formula);
            }
		}

		return $this->__PHPExcel_Calculation->calculateFormula($formula);
	}

}