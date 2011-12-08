<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los coeficientes.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1028 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-28 18:54:13 -0300 (lun 28 de sep de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los coeficientes.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Coeficiente extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
    
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre del coeficiente.')
        ),
        'tipo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el tipo del coeficiente.')
        ),
        'valor' => array(
			array(
				'rule'		=> VALID_NUMBER, 
				'message'	=> 'Debe especificar el valor del coeficiente.')
        )        
	);

    
    var $hasAndBelongsToMany = array('Coeficiente' => array('with' => 'EmpleadoresCoeficiente'));
    
}
?>