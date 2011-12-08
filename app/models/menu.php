<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los menus.
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
 * La clase encapsula la logica de acceso a datos asociada a los menus.
 *
 * Se refiere a los menus que usa el sistema.
 * 
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Menu extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del menu.')
        ),
        'orden' => array(
			array(
				'rule'		=> '/^[0-9]+$|^$/',
				'message'	=> 'Debe especificar un numero entero para el orden o dejarlo en blanco.')
        )
	);

    var $breadCrumb = array('format'    => '%s (%s)',
                            'fields'    => array('Menu.etiqueta', 'Menu.nombre'));

	var $hasAndBelongsToMany = array(	'Rol' =>	array('with' => 'RolesMenu'));

	var $belongsTo = array( 'Parentmenu' 	=>
					array(	'className'  	=> 'Menu',
							'foreignKey' 	=> 'parent_id'));

	var $hasMany = array(   'Childmenu' 	=>
					array(	'className'    	=> 'Menu',
							'foreignKey'   	=> 'parent_id'));
	
/**
 * Sets default field values.
 */
	function beforeSave($options = array()) {
        
		/** If no label set, create one */
		if (empty($this->data['Menu']['etiqueta'])) {
			$this->data['Menu']['etiqueta'] = Inflector::humanize($this->data['Menu']['nombre']);
		}
		
		/**
		* Si no cargo nada en el controller, pongo el nombre como controller.
        * TODO: relacionar con los controllers (un combo o algo)
		*/
		if (empty($this->data['Menu']['controller'])) {
			$this->data['Menu']['controller'] = $this->data['Menu']['nombre'];
		}
		
		/** If no specific action set, set index as default action */
		if (empty($this->data['Menu']['action'])) {
			$this->data['Menu']['action'] = 'index';
		}
		
		return parent::beforeSave($options);
	}

}
?>