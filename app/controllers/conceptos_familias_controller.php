<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las familias de conceptos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1345 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-04 16:17:50 -0300 (Fri, 04 Jun 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las familias de conceptos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ConceptosFamiliasController extends AppController {

    var $paginate = array(
        'order' => array(
            'ConceptosFamilia.nombre' => 'asc'
        )
    );



/**
 * conceptos.
 * Muestra via desglose los conceptos relacionados a la familia.
 */
	function conceptos($id) {
		$this->ConceptosFamilia->contain('Concepto');
		$this->data = $this->ConceptosFamilia->read(null, $id);
	}

}
?>