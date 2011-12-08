<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los motivos de egreso.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 196 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-12-30 15:58:08 -0200 (Tue, 30 Dec 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los motivos de egreso.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class EgresosMotivo extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

    var $validate = array(
        'motivo' => array(
            array(
                'rule'      => VALID_NOT_EMPTY, 
                'message'   => 'Debe especificar el motivo del egreso.')
        )        
    );

}
?>