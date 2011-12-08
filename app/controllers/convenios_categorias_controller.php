<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las categorias de los convenios colectivos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 806 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-30 22:19:59 -0300 (jue 30 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las categorias.
 *
 * Son las categorias (puestos) de los convenios colectivos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ConveniosCategoriasController extends AppController {

    var $paginate = array(
        'order' => array(
            'Convenio.nombre'           => 'asc',
            'ConveniosCategoria.nombre' => 'asc'
        )
    );

/**
 * historicos.
 * Muestra via desglose los historicos.
 */
	function historicos($id) {
		$this->ConveniosCategoria->contain(array("ConveniosCategoriasHistorico", "Convenio"));
		$this->data = $this->ConveniosCategoria->read(null, $id);
	}

}
?>