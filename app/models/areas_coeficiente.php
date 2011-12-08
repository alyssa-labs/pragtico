<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los coeficientes
 * de las Areas.
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
 * @lastmodified    $Date: 2008-12-30 16:36:44 -0200 (mar, 30 dic 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los coeficientes
 * de las Areas.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class AreasCoeficiente extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	var $modificadores = array(	'index' => array('contain' => array('Area', 'Coeficiente')),
							  	'add' => array('contain' => array()));
	
	var $validate = array(
        'area_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el Area.')
        ),
        'coeficiente_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el coeficiente.')
        ),
        'porcentaje' => array(
			array(
				'rule'		=> VALID_NUMBER, 
				'message'	=> 'Debe especificar el porcentaje del coeficiente.')
        )        
	);

	var $belongsTo = array('Area', 'Coeficiente');
}
?>