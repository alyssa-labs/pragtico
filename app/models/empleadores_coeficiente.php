<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los coeficientes
 * de los empleadores.
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
 * La clase encapsula la logica de acceso a datos asociada a los coeficientes
 * de los empleadores.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class EmpleadoresCoeficiente extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	var $modificadores = array(	'index' => array('contain' => array('Empleador', 'Coeficiente')),
							  	'add' => array('contain' => array()));
	
	var $validate = array(
        'emplador_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el empleador.')
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

    var $breadCrumb = array('format'    => '%s para %s',
                            'fields'    => array('Coeficiente.nombre', 'Empleador.nombre'));
    
	var $belongsTo = array('Empleador', 'Coeficiente');


}
?>