<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los parametros del sistema.
 * Los parametros son las opciones del sistema para agregar valores a los grupos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 196 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-12-30 15:58:08 -0200 (mar, 30 dic 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los parametros del sistema.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ParametrosController extends AppController {


    var $paginate = array(
        'order' => array(
            'Parametro.nombre' => 'asc'
        )
    );


}
?>