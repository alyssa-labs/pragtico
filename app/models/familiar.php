<?php
/**
 * Este archivo contiene toda la logica de acceso a los familiares de los trabajadores.
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
 * La clase encapsula la logica de acceso a los familiares de los trabajadores.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Familiar extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

    var $validate = array(
        'nombre' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar el nombre del familiar.')
        ),
        'apellido' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar el apellido del familiar.')
        ),
        'parentezco' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar el parentezco del familiar.')
        ),
        'trabajador_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar un Trabajador.')
        ),
        'sexo' => array(
            array(
                'rule'  => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar el sexo del familiar.')
        ),
        'email' => array(
            array(
                'rule'  => VALID_MAIL,
                'message'   => 'El email no es valido.')
        )
    );

    var $opciones = array('parentezco' => array(
        'padre'     => 'Padre',
        'madre'     => 'Madre',
        'tio'       => 'Tio',
        'cunado'    => 'Cuñado',
        'hermano'   => 'Hermano',
        'hijo'      => 'Hijo',
        'sobrino'   => 'Sobrino',
        'otro'      => 'Otro'));
    
    var $belongsTo = array('Trabajador');

    var $breadCrumb = array('format'    => '%s, %s',
                            'fields'    => array('Familiar.nombre', 'Familiar.apellido'));
    
}
?>