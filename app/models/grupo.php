<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los grupos de usuarios.
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
 * La clase encapsula la logica de acceso a datos asociada a los grupos de usuarios.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Grupo extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del grupo.')
        )
	);

	var $hasMany = array(	'GruposParametro' =>
                        array('className'    => 'GruposParametro',
                              'foreignKey'   => 'grupo_id'));
	
	var $hasAndBelongsToMany = array(	'Usuario' =>
						array('with' => 'GruposUsuario'));


	function beforeValidate() {
		/**
		* Es un add.
		* Como uso matematica binaria, el proximo ID debe ser generado por mi como potencia de 2 del anterior.
		*/
		if (empty($this->data['Grupo']['id'])) {
			$this->Behaviors->detach('Permisos');
			$group = $this->find('first', array(
					'fields' 		=> array('MAX(Grupo.id) AS last'),
					'recursive' 	=> -1));
			$this->data['Grupo']['id'] = $group['Grupo']['last'] * 2;
			$this->Behaviors->attach('Permisos');
		}
		return parent::beforeValidate();
	}


/**
 * Finds parameters values.
 *
 * @param integer $groupId The group to find parameters to.
 * @param string $paramName If 'all, get all parameters even they're not set for the selected group.
 *					   If $paramName, only get a specific parameter for the selected group.
 * @return array key => value array.
 * @access public.
 */
	function getParams($groupId, $paramName = 'all') {
		$group = $this->find('first',
			array(	'conditions' 	=> array('Grupo.id' => $groupId),
					'contain'		=> array('GruposParametro.Parametro')));
		if (!empty($group['GruposParametro'])) {
			if ($paramName === 'all') {
				$params = Set::combine($this->GruposParametro->Parametro->find('all'), '{n}.Parametro.nombre', '');
                
                foreach ($group['GruposParametro'] as $groupParam) {
                    $params[$groupParam['Parametro']['nombre']] = str_replace("\r", '', $groupParam['valor']);
                }
			} else {
                $params = Set::combine(Set::extract('/GruposParametro/valor', $this->GruposParametro->find('all', array(
                    'conditions'    => array(   'Parametro.nombre'  => $paramName,
                                                'Grupo.id'          => $groupId)))), '{n}', '{n}');
			}
		} else {
			return array();
		}
		return $params;
	}

}
?>