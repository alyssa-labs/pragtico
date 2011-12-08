<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los roles.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 761 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-27 16:28:23 -0300 (lun 27 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los roles.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RolesController extends AppController {


    var $paginate = array(
        'order' => array(
            'Rol.nombre' => 'asc'
        )
    );


/**
 * acciones.
 * Muestra via desglose acciones relacionadas a este rol.
 */
	function acciones($id) {
		$this->Rol->contain(array("Accion.Controlador"));
		$this->data = $this->Rol->read(null, $id);
	}

	
/**
 * usuarios.
 * Muestra via desglose usuarios pertenecientes a un rol.
 */
	function usuarios($id) {
		$this->Rol->contain(array("Usuario"));
		$this->data = $this->Rol->read(null, $id);
	}


/**
 * menus.
 * Muestra via desglose menus relacionados a un rol.
 */
	function menus($id) {
		$this->Rol->contain(array("Menu"));
		$this->data = $this->Rol->read(null, $id);
	}	
}	
?>