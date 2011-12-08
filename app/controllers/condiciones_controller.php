<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las condiciones (AFIP).
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyrights2007-2008,sPragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 804 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-30 21:34:38 -0300 (jue 30 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las condiciones.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class CondicionesController extends AppController {

    var $paginate = array(
        'order' => array(
            'Condicion.nombre' => 'asc'
        )
    );

}	
?>