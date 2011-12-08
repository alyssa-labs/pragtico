<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la ropa que se le entrega
 * a cada trabajador de una relacion laboral.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1374 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-22 14:13:33 -0300 (mar 22 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a la ropa que se le entrega
 * a cada trabajador de una relacion laboral.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RopasController extends AppController {


/**
 * Prendas.
 * Muestra via desglose las prendas entregadas. 
 */
   	function prendas($id) {
		$this->Ropa->contain("RopasDetalle");
		$this->data = $this->Ropa->read(null, $id);
   	}

}
?>
