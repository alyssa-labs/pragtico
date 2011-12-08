<?php
/**
 * Helper de la aplicacion.
 *
 * Todos los helpers heredan desde esta clase, por lo que defino metodos que usare en todos los helpers aca.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 199 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-12-30 16:03:16 -0200 (mar 30 de dic de 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula los metodos de los helpers que usare en todo la aplicacion.
 *
 * @package     pragtico
 * @subpackage  app
 */
class AppHelper extends Helper {
    
    function __construct(){
		$this->loadConfig();
        parent::__construct();
    }

/**
* Dada una preferencia retorna su valor
*/
	function traerPreferencia($preferencia) {
		$session = &new SessionComponent();
		if ($session->check("__Usuario")) {
			$usuario = $session->read("__Usuario");
			return $usuario['Usuario']['preferencias'][$preferencia];
		}
		else {
			return "";
		}
	}
    
}
?>