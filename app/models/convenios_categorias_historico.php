<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada al historico de las categorias de los convenios.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1458 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-07-04 23:24:24 -0300 (lun 04 de jul de 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada al historico de las categorias de los convenios.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class ConveniosCategoriasHistorico extends AppModel {

	var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
	
	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array('index' 	=> array('contain' => array('ConveniosCategoria.Convenio')),
							   'edit' 	=> array('contain' => array('ConveniosCategoria.Convenio')));

	var $validate = array(
        'desde' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar la vigencia de la categoria.'),
			array(
				'rule'		=> VALID_DATE,
				'message'	=> 'La fecha no es valida.'),
        ),
        'costo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el costo de la categoria.')
        ),
	);

	
	var $breadCrumb = array('format' 	=> '%s (%s)',
							'fields' 	=> array('ConveniosCategoria.nombre', 'ConveniosCategoria.Convenio.nombre'));

	var $belongsTo = array(	'ConveniosCategoria' =>
                        array('className'    => 'ConveniosCategoria',
                              'foreignKey'   => 'convenios_categoria_id'));
}

?>