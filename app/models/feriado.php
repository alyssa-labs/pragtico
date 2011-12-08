<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los feriados.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 201 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-12-30 16:36:44 -0200 (Tue, 30 Dec 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los feriados.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Feriado extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

    var $validate = array(
        'nombre' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar el nombre de la accion.')
        ),
        'controlador_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar un controlador.')
        )
    );

}
?>