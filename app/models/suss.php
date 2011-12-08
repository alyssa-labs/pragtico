<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada al Suss.
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
 * La clase encapsula la logica de acceso a datos asociada al Suss.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Suss extends AppModel {

    var $permissions = array('permissions' => 480, 'group' => 'default', 'role' => 'higher');

	var $validate = array(
        'periodo' => array(
			array(
				'rule'	=> '/^(20\d\d)(0[1-9]|1[012])$/', 
				'message'	=> 'Debe especificar un periodo valido.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar una periodo.')
        ),
        'fecha' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe especificar una fecha valida.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar una fecha.')
        ),
        'banco_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el banco.')
        )        
	);

	var $belongsTo = array('Banco', 'Empleador');

}
?>