<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los menus.
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
 * La clase encapsula la logica de negocio asociada a los menus.
 *
 * Se refiere a los menus que usa el sistema.
 * 
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class MenusController extends AppController {


    var $paginate = array(
        'order' => array(
            'Menu.orden' => 'asc'
        )
    );


/**
 * roles.
 * Muestra via desglose roles asociados a este menu.
 */
	function roles($id) {
		$this->Menu->contain(array("Rol"));
		$this->data = $this->Menu->read(null, $id);
	}

}
?>