<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la ropa
 * que se le entrega a un trabajador de una relacion laboral.
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
 * La clase encapsula la logica de acceso a datos asociada a la ropa
 * que se le entrega a un trabajador de una relacion laboral.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Ropa extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array(	'index'	=>
			array('contain'	=> array('Relacion'	=> array('Trabajador', 'Empleador'))),
								'add' 	=>
			array('valoresDefault'	=> array('fecha' => array('date' => 'Y-m-d'))),
								'edit'	=>
			array('contain'	=> array('Relacion'	=> array('Trabajador', 'Empleador'), 'RopasDetalle')));
	
	var $validate = array(
        'relacion_id__' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la relacion laboral.')
        ),
        'fecha' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar una fecha.'),
			array(
				'rule'	=> VALID_DATE,
				'message'	=> 'Debe ingresar un fecha valida o seleccionarla del calendario.'),
        ));
	
	var $belongsTo = 'Relacion';

	var $hasMany = array(	'RopasDetalle' => 
								array('dependent'	=> true));

}
?>
