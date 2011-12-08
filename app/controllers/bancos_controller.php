<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los bancos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2007, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 763 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-27 17:23:46 -0300 (lun 27 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los bancos.
 *
 * Se refiere a las entidades bancarias.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class BancosController extends AppController {

    var $paginate = array(
        'order' => array(
            'Banco.nombre' => 'asc'
        )
    );


/**
 * sucursales.
 * Muestra via desglose las sucursales del banco.
 */
	function sucursales($id) {
		$this->Banco->contain(array("Sucursal"));
		$this->data = $this->Banco->read(null, $id);
	}
}	
?>