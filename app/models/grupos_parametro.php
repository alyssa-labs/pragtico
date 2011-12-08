<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los parametros de los grupos.
 *
 * Los parametro son datos relacionados a los grupos. Se refiere a cualquier dato adicinal de un grupo
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
 * La clase encapsula la logica de acceso a datos asociada a los parametros de los grupos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class GruposParametro extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $modificadores = array('edit' => array('contain' => array(
											  	'Grupo',
												'Parametro')));
	
			var $validate = array(
        'grupo_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el grupo.')
        ),
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del parametro.')
        ),
        'valor' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el valor del parametro.')
        )
	);

	var $belongsTo = array(	'Grupo', 'Parametro');

}
?>