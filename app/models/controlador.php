<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los controladores.
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
 * La clase encapsula la logica de acceso a datos asociada a los controladores.
 *
 * Se refiere a los controllers del framework cakephp.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Controlador extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre del controlador.')
        )
	);

	var $hasMany = array(	'Accion' =>
                        array('className'    => 'Accion',
							  'dependent'	 => true,
                              'foreignKey'   => 'controlador_id'));

}
?>