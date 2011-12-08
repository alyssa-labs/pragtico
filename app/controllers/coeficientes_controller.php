<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al manejo de los coefientes.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
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
 * La clase encapsula la logica de negocio asociada al manejo de los coefientes.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class CoeficientesController extends AppController {


    var $paginate = array(
        'order' => array(
            'Coeficiente.tipo' => 'desc'
        )
    );    

}
?>