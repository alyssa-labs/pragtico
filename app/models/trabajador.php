<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los trabajadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link			http://www.pragmatia.org
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1139 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-11-13 12:28:43 -0300 (vie 13 de nov de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los trabajadores.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Trabajador extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array('edit'=>array('contain'=>array(	'Localidad',
																'Condicion',
																'Siniestrado',
																'ObrasSocial')),
								'add' =>array(								
										'valoresDefault'=>array('jubilacion'  => 'Reparto',
                                                                'condicion_id'  => '8',
                                                                'pais' => 'Argentina',
																'nacionalidad' => 'Argentina')));

	var $breadCrumb = array('format' 	=> '(%s) %s %s',
							'fields' 	=> array('Trabajador.numero_documento', 'Trabajador.nombre', 'Trabajador.apellido'));
	
	var $validate = array(
        'apellido' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el apellido del trabajador.')
        ),
        'localidad_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar la localidad del trabajador.')
        ),
        'condicion_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar la condicion del trabajador.')
        ),
        'siniestrado_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar el siniestrado del trabajador.')
        ),
        'obra_social_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar la obra social del trabajador.')
        ),
        'nombre' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el nombre del trabajador.')
        ),
        'sexo' => array(
            array(
                'rule'  => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar el sexo del trabajador.')
        ),
        'nacimiento' => array(
            array(
                'rule'  => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar la fecha de nacimiento del trabajador.')
        ),
        'direccion' => array(
            array(
                'rule'  => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar la direccion del trabajador.')
        ),
        'codigo_postal' => array(
            array(
                'rule'  => VALID_NOT_EMPTY,
                'message'   => 'Debe especificar el codigo postal del trabajador.')
        ),
        'cuil' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar el cuil del trabajador.'),
			array(
				'rule'	=> 'validCuitCuil',
				'message'	=> 'El numero de Cuil ingresado no es valido.')
				
        ),
        'jubilacion' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar un tipo de regimen jubilatorio.')
        ),
        'cbu' => array(
			array(
				'rule'	=> 'validCbu',
				'message'	=> 'El Cbu ingresado no es valido.')
        ),
        'ingreso' => array(
			array(
				'rule'	=> VALID_DATE,
				'message'	=> 'La fecha no es valida.'),
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar una fecha o seleccionarla desde el calendario.')
				
        ),
        'email' => array(
			array(
				'rule'	=> VALID_MAIL,
				'message'	=> 'El email no es valido.')
        ),
        'provincia_id' => array(
			array(
				'rule'	=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la provincia.')
        )
	);

    var $hasMany = array('Familiar', 'Relacion');
            
	var $belongsTo = array(
        'Localidad',
		'Siniestrado',
        'Condicion',
        'ObrasSocial' =>
            array(
            'className'    => 'ObrasSocial',
            'foreignKey'   => 'obra_social_id'));

	var $hasAndBelongsToMany = array('Empleador' =>
						array('with' => 'Relacion'));


/**
 * En caso de que tenga cargado el cbu, lo parseo y agrego el banco, la sucursal y la cuenta.
 *
 * @param array $results Los resultados que retorno alguna query.
 * @param boolean $primary Indica si este resultado viene de una query principal o de una query que
 *						   es generada por otra (recursive > 1)
 * @return array array $results Los mismos resultados que ingresaron con los campos que agregue.
 * @access public
 */	
	function afterFind($results, $primary = false) {
		/**
		* Si tengo la sucursal y la cuenta, puedo generar el CBU.
		* Solo lo necesito mostrar con un edit.
		*/
		if ($primary === true && !empty($results[0]['Trabajador']['cbu'])) {
            $Sucursal = ClassRegistry::init('Sucursal');
            $Sucursal->contain('Banco');
			foreach($results as $k => $result) {
				$pattern = '/(\d\d\d)(\d\d\d\d)\d(\d\d\d\d\d\d\d\d\d\d\d\d\d)\d/';
				if (preg_match($pattern, $result['Trabajador']['cbu'], $matches)) {
					$sucursal = $Sucursal->find('first', array('conditions' => array(
                        'Sucursal.codigo'   => $matches[2],
                        'Banco.codigo'      => $matches[1])));
					$results[$k]['Trabajador']['banco'] = $sucursal['Banco']['nombre'];
					$results[$k]['Trabajador']['sucursal'] = $sucursal['Sucursal']['direccion'];
					$results[$k]['Trabajador']['cuenta'] = $matches[3];
				}
			}
		}
		return parent::afterFind($results, $primary);
	}
	
	
/**
 * Antes de guardar, saco las propiedades del archivo y lo guardo como campo binary de la base.
 */

	function beforeSave() {
		$this->getFile();
		/** Si no cargo el documento, lo obtengo desde el cuit. */
		if (empty($this->data['Trabajador']['numero_documento']) && !empty($this->data['Trabajador']['cuil'])) {
			$this->data['Trabajador']['numero_documento'] = substr(str_replace('-', '', $this->data['Trabajador']['cuil']), 2, 8);
		}
		
		return parent::beforeSave();
	}
}
?>
