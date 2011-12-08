<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al historial de las relaciones laborales.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           pragtico v 1.0.0
 * @version         $Revision: 1060 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-10-07 14:15:28 -0300 (Wed, 07 Oct 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al historial de las relaciones laborales.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RelacionesHistorialesController extends AppController {


    function confirmar($id) {
        $this->RelacionesHistorial->save(array('RelacionesHistorial' => array(
            'id'        => $id,
            'estado'    => 'Confirmado')), array('callbacks' => false, 'validate' => false));
        $this->redirect(array('controller' => 'relaciones'));
    }
}

?>