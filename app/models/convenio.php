<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los convenios.
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
 * @lastmodified    $Date: 2010-06-04 16:17:50 -0300 (vie 04 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los convenios.
 *
 * Se refiere a los convenios colectivos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Convenio extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
    
    var $breadCrumb = array('format'    => '%s',
                            'fields'    => array('Convenio.nombre'));
    
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre del convenio colectivo.')
        ),
        'actualizacion' => array(
			array(
				'rule'		=> VALID_DATE,
				'message'	=> 'La fecha no es valida.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la fecha de la ultima actualizacion del convenio.')
        ),
        'numero' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el numero del convenio colectivo.')
        )
	);


	var $hasMany = array(	'Recibo',
							'ConveniosCategoria' =>
                        array('className'    => 'ConveniosCategoria',
                              'foreignKey'   => 'convenio_id',
							  'dependent'	 => true),
							'ConveniosInformacion' =>
                        array('className'    => 'ConveniosInformacion',
                              'foreignKey'   => 'convenio_id',
							  'dependent'	 => true));

	var $hasAndBelongsToMany = array('Concepto' =>
						array('with' => 'ConveniosConcepto'));


/**
 * Antes de guardar, saco las propiedades del archivo y lo guardo como campo binary de la base.
 */
	function beforeSave() {
		if ($this->getFile()) {
			return parent::beforeSave();
		}
		else {
			return false;
		}
	}
	
	
/**
 * Obtiene los valores de las informaciones de los convenios.
 *
 * @param array $conveniosId Los ids de los convenios de los cuales se desea obtener la/s informacion/es.
 * @return array Las informaciones y sus valores por convenio.
 * @access public
 */	
	function getInformacion($conveniosId) {
		$return = array();
		$r = $this->ConveniosInformacion->find('all', 
			array(	'contain'		=> array('Informacion'),
					'conditions'	=> array('ConveniosInformacion.convenio_id'	=>	$conveniosId)));
		foreach ($r as $v) {
			$return[$v['ConveniosInformacion']['convenio_id']][$v['Informacion']['nombre']] = $v['ConveniosInformacion']['valor'];
		}
		return $return;
	}
}
?>