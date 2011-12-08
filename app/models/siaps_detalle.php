<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada al detalle de cada version de Siap.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 811 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-31 15:56:04 -0300 (vie 31 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada al detalle de cada version de Siap.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class SiapsDetalle extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $belongsTo = array('Siap');

    var $opciones = array(
        'tipo'      => array(
            'text'      => 'Texto',
            'decimal'   => 'Decimal',
            'integer'   => 'Entero'));    

}
?>