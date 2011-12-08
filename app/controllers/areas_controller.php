<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las areas de los empleadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 749 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-27 00:18:07 -0300 (lun 27 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las areas de los empleadores.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class AreasController extends AppController {


    var $paginate = array(
        'order' => array(
            'Empleador.nombre'  => 'asc',
            'Area.nombre'       => 'asc'
        )
    );

/**
 * coeficientes.
 * Muestra via desglose los coeficientes de un area.
 */
	function coeficientes($id) {
		$this->Area->contain('Coeficiente');
		$this->data = $this->Area->read(null, $id);
	}

    function beforeRender() {
        if (in_array($this->action, array('add', 'edit'))) {
            $usuario = $this->Session->read('__Usuario');
            if (!empty($usuario['Usuario']['preferencias']['grupo_default_id'])) {
                $this->set('centrosDeCosto', ClassRegistry::init('Grupo')->getParams($usuario['Usuario']['preferencias']['grupo_default_id'], 'centro_de_costo'));
            }
        }
    }
	
}
?>