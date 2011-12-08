<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al detalle de cada version de SIAP.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 783 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-28 23:50:06 -0300 (mar 28 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al detalle de cada version de SIAP.
 *
 * Se refiere a las versiones de SIAP.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class SiapsDetallesController extends AppController {
	
    var $paginate = array(
        'order' => array(
            'Siap.version'          => 'desc',
            'SiapsDetalle.desde'    => 'asc'
        )
    );

}
?>