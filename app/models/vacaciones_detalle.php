<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los detalles de las vacaciones.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1025 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-28 13:10:26 -0300 (Mon, 28 Sep 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los detalles de las vacaciones.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class VacacionesDetalle extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

/**
 * Los modificaciones al comportamiento estandar de app_controller.php
 *
 * @var array
 * @access public
*/
    var $modificadores = array( 'add'  =>
            array('contain' => array('Vacacion' => array('Relacion' => array('Trabajador')))));
    
    var $belongsTo = array('Vacacion');
    
}
?>