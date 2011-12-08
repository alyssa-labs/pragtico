<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las horas de una relacion laboral.
 * Las horas puedenser horas extras, horas de enfermedad, etc.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 895 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-08-30 22:38:14 -0300 (dom 30 de ago de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las horas de una relacion laboral.
 * Las horas puedenser horas extras, horas de enfermedad, etc.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class HorasController extends AppController {

    var $paginate = array(
        'order' => array(
            'Hora.periodo' => 'desc'
        )
    );


    function afterPaginate($results) {
        if (!empty($results)) {
            $this->set('cantidad', $this->Hora->getTotal($this->Paginador->getCondition()));
        } else {
            $this->set('cantidad', 0);
        }
    }
    
}

?>