<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las provincias.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 750 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-27 09:28:15 -0300 (lun 27 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las provincias.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ProvinciasController extends AppController {

    var $paginate = array(
        'order' => array(
            'Provincia.nombre' => 'asc'
        )
    );

/**
 * localidades.
 * Muestra via desglose las localidades relacionadas a esta provincia.
 */
	function localidades($id) {
		$this->Provincia->contain(array("Localidad"));
		$this->data = $this->Provincia->read(null, $id);
	}
}
?>