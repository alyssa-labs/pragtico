<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la informacion adicional relacionada a un convenio.
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
 * La clase encapsula la logica de acceso a datos asociada a la informacion adicional relacionada a un convenio.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class ConveniosInformacion extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

    var $breadCrumb = array('format'    => '%s de %s',
                            'fields'    => array('Informacion.nombre', 'Convenio.nombre'));
    
	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array('index'=>array('contain'=>array('Convenio', 'Informacion')));
	
	var $validate = array(
        'valor' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar un valor para la variable.')
        )
	);
	
	var $belongsTo = array(	'Convenio' =>
                        array('className'    => 'Convenio',
                              'foreignKey'   => 'convenio_id'),
							'Informacion' =>
                        array('className'    => 'Informacion',
                              'foreignKey'   => 'informacion_id'));

}
?>