<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al SIAP.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 804 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-30 21:34:38 -0300 (jue 30 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al SIAP.
 *
 * Se refiere a las versiones de SIAP.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class SiapsController extends AppController {
	
    var $paginate = array(
        'order' => array(
            'Siap.version' => 'desc'
        )
    );

/**
 * detalles.
 * Muestra via desglose los detalles de esta version de SIAP.
 */
	function detalles($id) {
		$this->data = $this->Siap->read(null, $id);
	}

}
?>