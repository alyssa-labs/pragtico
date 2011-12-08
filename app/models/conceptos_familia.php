<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los conceptos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1345 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-04 16:17:50 -0300 (Fri, 04 Jun 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los conceptos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class ConceptosFamilia extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $breadCrumb = array('format' 	=> '%s',
							'fields' 	=> array('ConceptosFamilia.nombre'));
	
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre de la familia del concepto.')
        )
	);

	var $hasMany = array('Concepto' => array('foreignKey' => 'conceptos_familia_id'));


}
?>