<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los tipos de los empleadores (segun SIAP).
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 367 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-03-05 13:49:57 -0200 (Thu, 05 Mar 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los tipos de los empleadores (segun SIAP).
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class EmployersType extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'name' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del tipo de empleador.')
        )
	);

}
?>