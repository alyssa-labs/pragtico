<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los descuentos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1311 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-19 06:34:49 -0300 (mié 19 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los descuentos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Descuento extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array(	'index'	=>
			array('contain'	=> array('Relacion' => array('Empleador', 'Trabajador'))),
								'edit'	=>
			array('contain'	=> array('Relacion'	=> array('Empleador', 'Trabajador'))),
								'add' 	=>
			array('valoresDefault'	=> array('alta'		=> array('date' => 'Y-m-d'),
											 'desde'	=> array('date' => 'Y-m-d'))));

	var $opciones = array('descontar'=> array(	'1'=>	'Con Cada Liquidacion',
												'2'=>	'Primera Quincena',
												'4'=>	'Segunda Quincena',
												'8'=>	'Sac',
												'16'=>	'Vacaciones',
												'32'=>	'Liquidacion Final',
											 	'64'=>	'Especial'));
							
	var $validate = array(
        'alta' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe ingresar una fecha valida.'),
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe ingresar una fecha.'),
        ),
        'desde' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe ingresar una fecha valida.'),
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe ingresar una fecha.'),
        ),
        'tipo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el tipo de descuento.')
        ),
        'descripcion' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar la descripcion del descuento.')
        ),
        'relacion_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la relacion laboral a la cual realizar el descuento.')
        )
	);


	var $breadCrumb = array('format' => '%s %s (%s)', 
							'fields' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));


	var $belongsTo = array('Liquidacion', 'Relacion');
	
	var $hasMany = array(	'Pago', 'DescuentosDetalle' =>
					array('className'    => 'DescuentosDetalle',
						  'foreignKey'   => 'descuento_id'));



/**
 * getDescuentos
 * Dada un ralacion XXXXXXXXXX.
 * @return array vacio si no hay nada que descontar.
 */
    function getDescuentos($relacion, $opciones) {

        $conditions = array(array('OR'  => array(
            'Descuento.hasta'       => '0000-00-00',
            'Descuento.hasta >='    => $opciones['periodo']['hasta'])));

        switch ($opciones['tipo']) {
            case 'normal':
				if ($opciones['periodo']['periodo'] === '1Q') {
					$descontar = 3;
				} elseif ($opciones['periodo']['periodo'] === '2Q' || $opciones['periodo']['periodo'] === 'M') {
					$descontar = 5;
				}

				if (!empty($opciones['periodo']['desde'])) {
					$expression = $this->getDataSource()->expression('IF (`Descuento`.`tipo` = \'Vale\', `Descuento`.`desde` BETWEEN \'' . $opciones['periodo']['desde'] . '\' AND \'' . $opciones['periodo']['hasta'] . '\', 1=1)');
					$conditions[] = $expression;
				}
				break;
			case 'sac':
				$descontar = 9;
			break;
			case 'vacaciones':
				$descontar = 17;
			break;
			case 'final':
				$descontar = 33;
                $conditions = array();
			break;
			case 'especial':
				$descontar = 1;
			break;
		}

		$orderExpression = $this->getDataSource()->expression("case Descuento.tipo when 'Cuota Alimentaria' then 0 when 'Embargo' then 1 when 'Vale' then 2 when 'Prestamo' then 3 end, Descuento.alta");
		$r = $this->find('all',
			array(
				  	'contain'		=> 'DescuentosDetalle',
	   				'order'			=> $orderExpression,
				  	'checkSecurity'	=> false,
					'conditions' 	=> array_merge($conditions, array(
 				'(Descuento.descontar & ' . $descontar . ') >' 	   => 0,
                'Descuento.relacion_id'                            => $relacion['Relacion']['id'],
 				'Descuento.estado' 	                               => 'Activo'))
		));

		$conceptos = $variables = $auxiliares = array();
        $index['Vale'] = 'a';
        $index['Prestamo'] = 'a';
        $index['Embargo'] = 'a';
        $index['Cuota Alimentaria'] = 'a';

		if (!empty($r)) {
            $Concepto = ClassRegistry::init('Concepto');
			foreach ($r as $k => $v) {

                if ($index[$v['Descuento']['tipo']] != 'a' && $v['Descuento']['concurrencia'] == 'Solo uno a la vez') {
                    continue;
                }
                
                $tipos[] = $v['Descuento']['tipo'];
                $name = Inflector::underscore(str_replace(' ', '', $v['Descuento']['tipo'] . '_' . $index[$v['Descuento']['tipo']]));
                $index[$v['Descuento']['tipo']]++;
                
                $conceptos[$k] = $Concepto->findConceptos('ConceptoPuntual', array_merge(
                        array('relacion' => $relacion, 'codigoConcepto' => $name), $opciones));
                
                $variables['#total_descontado_' . $name] = 0;
                $variables['#cuotas_descontadas_' . $name] = 0;
                $variables['#monto_' . $name] = $v['Descuento']['monto'];
                $variables['#cuotas_' . $name] = $v['Descuento']['cuotas'];
                $variables['#porcentaje_' . $name] = $v['Descuento']['porcentaje'];
                $variables['#descripcion_' . $name] = $v['Descuento']['descripcion'];
                if (!empty($v['DescuentosDetalle'])) {
                    $variables['#total_descontado_' . $name] = array_sum(Set::extract('/DescuentosDetalle/monto', $v));
                    $variables['#cuotas_descontadas_' . $name] = count($v['DescuentosDetalle']);
                }


				/** Check for concurrency */
				if ($v['Descuento']['concurrencia'] === 'Solo uno a la vez') {
					if (empty($concurrencia[$v['Descuento']['tipo']])) {
						$concurrencia[$v['Descuento']['tipo']] = true;
					} else {
						continue;
					}
				}

				
				/** Creo un registro el la tabla auxiliar que debera ejecutarse en caso de que se confirme la pre-liquidacion. */
				$auxiliar = null;
				$auxiliar['concepto_id'] = $conceptos[$k][$name]['id'];
                $auxiliar['descuento_id'] = $v['Descuento']['id'];
				$auxiliar['fecha'] = '##MACRO:fecha_liquidacion##';
				$auxiliar['liquidacion_id'] = '##MACRO:liquidacion_id##';
                $auxiliar['permissions'] = '288';
				$auxiliar['monto'] = '##MACRO:concepto_valor##';
				$auxiliares[] = array('save' => serialize($auxiliar), 'model' => 'DescuentosDetalle');
                if ($v['Descuento']['tipo'] !== 'Cuota Alimentaria') {
                    $auxiliar = null;
                    $auxiliar['id'] = $v['Descuento']['id'];
                    $auxiliar['concepto_id'] = $conceptos[$k][$name]['id'];
                    $auxiliar['estado'] = 'Finalizado';
                    $auxiliar['permissions'] = '288';
                    $auxiliar['condition'] = '##MACRO:concepto_valor##' . ' + ' . $variables['#total_descontado_' . $name] . ' >= ' . $variables['#monto_' . $name];
                    $auxiliares[] = array('save' => serialize($auxiliar), 'model' => 'Descuento');
                }
			}
		}

        return array(
                    'conceptos'    => $conceptos,
                    'variables'    => $variables,
                    'auxiliar'     => $auxiliares);
	}


    function beforeValidate($options = array()) {
        if (empty($this->data['Descuento']['descontar'])) {
            $this->invalidate('descontar', 'Debe seleccionar con que liquidacion se debe descontar.');
            return false;
        }
        
        if (!empty($this->data['Descuento']['tipo'])) {
            if ($this->data['Descuento']['tipo'] === 'Cuota Alimentaria') {
                if (empty($this->data['Descuento']['porcentaje'])) {
                    $this->invalidate('porcentaje', 'Debe ingresar el porcentaje a descontar para la cuota alimentaria.');
                    return false;
                }
            } else {
                if (empty($this->data['Descuento']['monto'])) {
                    $this->invalidate('monto', 'Debe ingresar el monto a descontar.');
                    return false;
                }
            }
        }
        return parent::beforeValidate($options);
    }

/**
 * descontar field is bitwise, must sum values then.
 */
	function beforeSave($options = array()) {
        if (!empty($this->data['Descuento']['tipo'])) {
            if (isset($this->data['Descuento']['descontar']) && is_array($this->data['Descuento']['descontar'])) {
                $this->data['Descuento']['descontar'] = array_sum($this->data['Descuento']['descontar']);
            }
            if ($this->data['Descuento']['tipo'] === 'Vale') {
                $this->data['Descuento']['cuotas'] = 1;
            } elseif ($this->data['Descuento']['tipo'] === 'Cuota Alimentaria') {
                $this->data['Descuento']['monto'] = 0;
                $this->data['Descuento']['cuotas'] = 0;
            }

            /** Must create a pending peyment */
            if (empty($this->data['Descuento']['id']) && in_array($this->data['Descuento']['tipo'], array('Vale', 'Prestamo'))) {
                $this->data['Pago'][0]['fecha'] = $this->data['Descuento']['alta'];
                $this->data['Pago'][0]['relacion_id'] = $this->data['Descuento']['relacion_id'];
                $this->data['Pago'][0]['monto'] = $this->data['Descuento']['monto'];
                $this->data['Pago'][0]['moneda'] = 'Pesos';
                $this->data['Pago'][0]['estado'] = 'Pendiente';
            }
        }
		return parent::beforeSave($options);
	}

	
}
?>