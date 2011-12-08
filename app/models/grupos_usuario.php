<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la relacion
 * entre grupos y usuarios.
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
 * La clase encapsula la logica de acceso a datos asociada a la relacion entre grupos y usuarios.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class GruposUsuario extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'grupo_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el grupo.')
        ),
        'usuario_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el usuario.')
        ),
        'tipo' => array(
			array(
				'rule'	=> '__soloUnGrupoPrimario', 
				'message'	=> 'Solo puede existir un grupo primario y este usuario ya lo tiene.')
        )
    );
        
        
	var $belongsTo = array(	'Grupo' =>
                        array('className'    => 'Grupo',
                              'foreignKey'   => 'grupo_id'),
							'Usuario' =>
                        array('className'    => 'Usuario',
                              'foreignKey'   => 'usuario_id'));

/**
 * Valida que solo pueda existir un grupo primario por usuario.
 */
	function __soloUnGrupoPrimario($value, $params = array()) {
		return true;
		if ($this->data['GruposUsuario']['tipo'] == 'Primario') {
			$find = array('Usuario.id'=>$this->data['GruposUsuario']['usuario_id'], 'GruposUsuario.tipo' => 'Primario');
			if ($this->findCount($find) > 0) {
				return false;
			}
		}
		return true;
	}


}
?>