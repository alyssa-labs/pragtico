<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la relacion entre un rubro y un empleador.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 812 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-31 16:14:15 -0300 (vie 31 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a la relacion entre un rubro y un empleador.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class EmpleadoresRubro extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	var $belongsTo = array(	'Empleador' =>
                        array('className'    => 'Empleador',
                              'foreignKey'   => 'empleador_id'),
							'Rubro' =>
                        array('className'    => 'Rubro',
                              'foreignKey'   => 'rubro_id'));
    var $breadCrumb = array('format'    => '%s para %s',
                            'fields'    => array('Rubro.nombre', 'Empleador.nombre'));

}
?>