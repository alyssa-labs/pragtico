<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las preferencias del sistema.
 * Las preferencias son las opciones del sistema que puede personalizar el usuario.
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
 * La clase encapsula la logica de negocio asociada a las preferencias del sistema.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class PreferenciasController extends AppController {

    var $paginate = array(
        'order' => array(
            'Preferencia.nombre' => 'asc'
        )
    );

/**
 * valores.
 * Muestra via desglose los valores que puede asumir la preferencia.
 */
	function valores($id) {
		$this->data = $this->Preferencia->read(null, $id);
	}


}
?>