<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las preferencias de los usuarios.
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
 * La clase encapsula la logica de acceso a datos asociada a las preferencias de los usuarios
 *
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class PreferenciasUsuario extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array( 
        'usuario_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'xxxxxxxx.')
			),
        'preferencia_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la relacion laboral en la que se produjo la ausencia.')
        )        
	);

	var $belongsTo = array(	'Usuario' =>
                        array('className'    => 'Usuario',
                              'foreignKey'   => 'usuario_id'),
							'Preferencia' =>
                        array('className'    => 'Preferencia',
                              'foreignKey'   => 'preferencia_id'),
							'PreferenciasValor' =>
                        array('className'    => 'PreferenciasValor',
                              'foreignKey'   => 'preferencias_valor_id'));


/**
 * Me aseguro de que siempre se guarde el usuario logueado como dueno de la preferencia.
 */
	function beforeSave() {
		$session = &new SessionComponent();
		$usuario = $session->read('__Usuario');
		$this->data[$this->name]['usuario_id'] = $usuario['Usuario']['id'];
		return parent::beforeSave();
	}

	
/**
 * Me aseguro de actualizar en la sesion los cambios que pudo haber realizado el usuario cuando guarda.
 */
	function afterSave($created) {
		$this->__actualizarPreferenciasEnSesion();
		return parent::afterSave($created);
	}


/**
 * Me aseguro de actualizar en la sesion los cambios que pudo haber realizado el usuario cuando borra.
 */
	function afterDelete() {
		$this->__actualizarPreferenciasEnSesion();
		return parent::afterDelete();
	}


/**
 * Me aseguro de actualizar en la sesion los cambios que pudo haber realizado el usuario.
 */
	function __actualizarPreferenciasEnSesion() {
		$session = &new SessionComponent();
		$usuario = $session->read('__Usuario');
		$usuario['Usuario']['preferencias'] = $this->Preferencia->findPreferencias($usuario['Usuario']['id']);
		$session->write('__Usuario', $usuario);
	}
	
}
?>
