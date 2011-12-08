<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las sucursales de los bancos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1351 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-07 13:42:14 -0300 (lun 07 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las sucursales de los bancos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Sucursal extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'codigo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el codigo de la sucursal del banco.'),
			array(
				'rule'		=> VALID_NUMBER, 
				'message'	=> 'El codigo de la sucursal del banco debe ser un numerico.')
	    ),
        'direccion' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la direccion de la sucursal del banco.'),
		)
	);

	var $belongsTo = array('Banco', 'Provincia');

}
?>