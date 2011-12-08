<?php 
class TestService extends AppModel
{
    var $name = 'TestService';
    var $useTable = false;

/**
 * hello worrld
 *
 * @param string $a Lo que entra.
 * @return string Lo que vuelve.
 */
	function hola($a) {
        return 'HOLA ' . $a;
    }


/**
 * Divide two numbers
 *
 * @param float $a
 * @param float $b
 * @return float
 */
	function divide($a, $b) {
        if ($b != 0) {
            return $a / $b;
        }
        return 0;
    }
}
?>