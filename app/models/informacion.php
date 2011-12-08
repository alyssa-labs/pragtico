<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la informacion adicional de los convenios.
 * La informacion adicional puede ser usada en las formulas como variables.
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
 * La clase encapsula la logica de acceso a datos asociada a la informacion adicional de los convenios.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Informacion extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'nombre' => array(
			array(
				'rule'	=> '/^#[a-z,0-9,_]+$/',
				'message'	=> 'El nombre de la informacion debe comenzar con el signo # y solo puede contener letras minusculas y guion/es bajo/s (_).')
        )
	);


}
?>