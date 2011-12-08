<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las preferencias.
 * Es la definicion de las preferencias admitidas por el sistema.
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
 * La clase encapsula la logica de acceso a datos asociada a las preferencias.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Preferencia extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

	var $validate = array(
        'id' => array(
			array(
				'rule'		=> '__validarPreferenciasValor',
				'message'	=> '')
		),	
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre de la preferencia.')
        ),
        'valor' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el valor por defecto de la preferencia.')
        ),
        'valores_posibles' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar los posibles valores que ouede tomar la preferencia.')
        )        
	);
	
	
	var $hasMany = array(	'PreferenciasUsuario' =>
                        array('className'    => 'PreferenciasUsuario',
                              'foreignKey'   => 'preferencia_id'),
                            'PreferenciasValor' =>
                        array('className'    => 'PreferenciasValor',
                              'foreignKey'   => 'preferencia_id'));


/**
* Verifica que haya seleccionado uno y solo un valor predeterminado para la referencia.
*/
	function __validarPreferenciasValor($value, $params = array()) {
		/**
		* Verifica que haya seleccionado por lo menos un valor asociado a la preferencia.
		*/
		if (count($this->data['PreferenciasValor']) == 0) {
			$this->dbError['errorDescripcion'] = 'Debe ingresar al menos un valor para la preferencia.';
			return false;
		}
		
		$c=0;
		foreach ($this->data['PreferenciasValor'] as $v) {
			if ($v['predeterminado'] == 'Si') {
				$c++;;
			}
		}
		/**
		* Verifica que haya seleccionado solo un valor de la preferencia como predeterminado.
		*/
		if ($c > 1) {
			$this->dbError['errorDescripcion'] = 'Debe seleccionar solo un valor como predeterminado para la preferencia.';
			return false;
		}
		/**
		* Verifica que haya seleccionado por lo menos un valor de la preferencia como predeterminado.
		*/
		elseif ($c==0) {
			$this->dbError['errorDescripcion'] = 'Debe ingresar por lo menos un valor como prederminado para la preferencia.';
			return false;
		}
		
		return true;
	}

/**
 * Busca las preferencias del sistema con su correspondiente valor por defecto.
 * Si el usuario ha sobreescrito este valor, retorna este valor en lugar del valor por defecto.
 */
	function findPreferencias($usuario_id) {
		$valores = array();
		$preferencias = $this->find('all', 
				array('contain'	=> array(	
					  'PreferenciasUsuario', 'PreferenciasValor' => 
						array('conditions'	=> array('PreferenciasValor.predeterminado' => 'Si'))), 
							  'checkSecurity'=>false));
		//d($preferencias);
		foreach ($preferencias as $preferencia) {
			if (!empty($preferencia['PreferenciasUsuario'][0]['PreferenciasValor']['valor'])) {
				$valor = $preferencia['PreferenciasUsuario'][0]['PreferenciasValor']['valor'];
			}
			else {
				$valor = $preferencia['PreferenciasValor'][0]['valor'];
			}
			$valores[$preferencia['Preferencia']['nombre']] = $valor;
		}
		return $valores;
	}

}
?>
