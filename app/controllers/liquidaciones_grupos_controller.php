<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los grupos de liquidaciones.
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
 * @lastmodified    $Date: 2009-07-27 16:28:23 -0300 (Mon, 27 Jul 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los grupos de liquidaciones.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class LiquidacionesGruposController extends AppController {


    var $paginate = array(
        'order' => array(
            'LiquidacionesGrupo.id'       			=> 'DESC'
		)
	);


	function reprint() {

		$this->redirect(
			array(
				'controller'		=> 'liquidaciones',
				'action'			=> 'reporte_liquidaciones_confirmadas',
				$this->params['named']['id']
			)
		);
	}


	function liquidaciones($id) {
		$this->LiquidacionesGrupo->contain(array('Liquidacion' => array('Empleador', 'Trabajador')));
		$this->data = $this->LiquidacionesGrupo->findById($id);
	}

}	
?>