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
 * @version         $Revision: 1379 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-25 13:50:36 -0300 (vie 25 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los conceptos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Concepto extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');
    
	var $breadCrumb = array('format' 	=> '(%s) %s',
							'fields' 	=> array('Concepto.codigo', 'Concepto.nombre'));
	
	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el nombre del concepto.')
        ),
        'codigo' => array(
			array(
				'rule'		=> '/^[a-z,0-9,_]+$/',
				'message'	=> 'El codigo del concepto solo puede contener letras minusculas y numeros.'),
			array(
				'rule'		=> array('minLength', 3),
				'message'	=> 'El codigo del concepto debe tener al menos 4 caracteres.'),
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe especificar el codigo del concepto.')
        ),
        'tipo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe seleccionar el tipo de concepto.')
        ),
        'coeficiente_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY, 
				'message'	=> 'Debe seleccionar un coeficiente.')
        ),
        'desde' => array(
			array(
				'rule'		=> VALID_DATE,
				'message'	=> 'La fecha no es valida.'),
			array(
				'rule'		=> '__validarRango',
				'message'	=> 'La vigencia desde debe ser inferior o igual a la vigencia hasta.')
        ),
        'hasta' => array(
			array(
				'rule'		=> VALID_DATE,
				'message'	=> 'La fecha no es valida.'),
			array(
				'rule'		=> '__validarRango',
				'message'	=> 'La vigencia hasta debe ser superior o igual a la vigencia desde.')
        ),
        'formula' => array(
            array(
                'rule'      => 'validFormulaStrings',
                'message'   => 'La formula utiliza valores de texto no encerrados entre comillas simples (\') y que tampoco han sido marcados como variable (#) o como concepto (@).'),
            array(
                'rule'      => 'validFormulaConcepts',
                'message'   => 'La formula utiliza conceptos que no existen en el sistema.'),
            array(
                'rule'      => 'validFormulaParenthesis',
                'message'   => 'La formula no abre y cierra la misma cantidad de parentesis.'),
            array(
                'rule'      => 'validFormulaBrackets',
                'message'   => 'La formula no abre y cierra la misma cantidad de corchetes.')
        )
	);

	var $belongsTo = array('Coeficiente', 'ConceptosFamilia');

	var $hasAndBelongsToMany = array(	'Convenio' =>
								array('with' => 'ConveniosConcepto'),
										'Empleador' =>
								array('with' => 'EmpleadoresConcepto'),
										'Relacion' =>
								array('with' => 'RelacionesConcepto'));

    var $opciones = array(
        'liquidacion_tipo'  => array(
            '1'     => 'Normal',
            '2'     => 'Sac',
            '4'     => 'Vacaciones',
            '8'     => 'Final',
            '16'    => 'Especial'),
        'remuneracion'      => array(
            '1'     => 'Remuneracion 1',
            '2'     => 'Remuneracion 2',
            '4'     => 'Remuneracion 3',
            '8'     => 'Remuneracion 4',
            '16'    => 'Remuneracion 5',
            '32'    => 'Remuneracion 6',
            '64'    => 'Remuneracion 7',
            '128'   => 'Remuneracion 8',
			'256'   => 'Remuneracion 9'),
        'compone'           => array(
            'Sueldo'                    => 'Sueldo',
            'Adicionales'               => 'Adicionales',
            'Premios'                   => 'Premios',
            'Importe Horas Extras'      => 'Importe Horas Extras',
            'SAC'                       => 'SAC',
            'Vacaciones'                => 'Vacaciones',
            'Plus Zona Desfavorable'    => 'Plus Zona Desfavorable'));

/**
 * descontar field is bitwise, must sum values then.
 */
    function beforeSave($options = array()) {
        if (isset($this->data['Concepto']['remuneracion']) && is_array($this->data['Concepto']['remuneracion'])) {
            $this->data['Concepto']['remuneracion'] = array_sum($this->data['Concepto']['remuneracion']);
        }
        if (isset($this->data['Concepto']['liquidacion_tipo']) && is_array($this->data['Concepto']['liquidacion_tipo'])) {
            $this->data['Concepto']['liquidacion_tipo'] = array_sum($this->data['Concepto']['liquidacion_tipo']);
        }
        return parent::beforeSave($options);
    }
/**
 * Valida que el extremo superior del rango sea mayor al inferior
 * en caso de que ambos esten seteados.
 */
    function __validarRango($value, $params = array()) {
		if (!empty($this->data[$this->name]['desde']) && !empty($this->data[$this->name]['hasta'])) {
			if ($this->data[$this->name]['desde'] > $this->data[$this->name]['hasta']) {
				return false;
			}
		}
        return true;
    }


/**
 * Encuentra los conceptos asociados a una relacion.
 * Retorna un array con todos los conceptos, teniendo en cuenta la estructura de jerarquias
 * que existen entre ellos, es decir, si un concepto es asociado a una relacion y su formula
 * es escrita en este nivel, esta prevalecera por sobre la formula del concepto asociada al
 * empleador, o al convenio colectivo, o al mismo concepto.
 * La jerarquia es: 	Relacion,
						Empleador,
						Convenio Colectivo,
						Concepto.
 */
	function findConceptos($tipo = 'Relacion', $opciones = array()) {
		
		$default['hasta'] = '2000-01-01';
		$default['desde'] = '2050-12-31';
		$default['condicionAdicional'] = ""; // de la forma string....
		$opciones = array_merge($default, $opciones);


		
		
		if (!empty($opciones['condicionAdicional'])) {
			$condicionAdicional = ' and ' . $opciones['condicionAdicional'] . ' ';
		} else {
			$condicionAdicional = '';
		}
		
		$fieldsRelaciones =				array(	'RelacionesConcepto.id',
												'RelacionesConcepto.relacion_id',
												'RelacionesConcepto.concepto_id',
												'RelacionesConcepto.desde',
												'RelacionesConcepto.hasta',
												"REPLACE(RelacionesConcepto.formula, '#valor_novedad:', '') AS formula");
		$fieldsEmpleadoresConcepto =	array(	'EmpleadoresConcepto.id',
												'EmpleadoresConcepto.empleador_id',
												'EmpleadoresConcepto.concepto_id',
												'EmpleadoresConcepto.desde',
												'EmpleadoresConcepto.hasta',
												'EmpleadoresConcepto.formula');
		$fieldsConveniosConcepto = 		array(	'ConveniosConcepto.id',
												'ConveniosConcepto.convenio_id',
												'ConveniosConcepto.concepto_id',
												'ConveniosConcepto.desde',
												'ConveniosConcepto.hasta',
												'ConveniosConcepto.formula');
		$fieldsConceptos = 				array(	'Concepto.id',
												'Concepto.codigo',
												'Concepto.nombre',
												'Concepto.nombre_formula',
												'Concepto.tipo',
												'Concepto.periodo',
												'Concepto.imprimir',
												'Concepto.cantidad',
                                                'Concepto.valor_unitario',
                                                'Concepto.compone',
                                                'Concepto.remuneracion',
												'Concepto.liquidacion_tipo',
												'Concepto.formula',
												'Concepto.desde',
												'Concepto.hasta',
												'Concepto.pago',
												'Concepto.orden',
                                                'Concepto.retencion_sindical');
		$fieldCoeficientes = 			array(	'Coeficiente.id',
												'Coeficiente.nombre',
												'Coeficiente.tipo',
												'Coeficiente.valor');
		$fieldEmpleadoresCoeficiente = 	array(	"EmpleadoresCoeficiente.porcentaje");
        $fieldAreasCoeficiente = array('Area.nombre', 'AreasCoefiente.porcentaje');
		$order 		= "CASE Concepto.tipo WHEN 'Remunerativo' THEN 0 WHEN 'No Remunerativo' THEN 1 WHEN 'Deduccion' THEN 2 END";

		if ($tipo === 'Relacion') {
			$fields = am($fieldsRelaciones, $fieldsEmpleadoresConcepto, $fieldsConveniosConcepto, $fieldsConceptos, $fieldCoeficientes, $fieldEmpleadoresCoeficiente, $fieldAreasCoeficiente);
			$table 	= 	'relaciones_conceptos';
			$joins	=	array(
                array(
                    'alias' => 'EmpleadoresConcepto',
                    'table' => 'empleadores_conceptos',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(
                            'RelacionesConcepto.concepto_id = EmpleadoresConcepto.concepto_id',
                            'EmpleadoresConcepto.empleador_id'  =>  $opciones['relacion']['Relacion']['empleador_id']),
                        array('OR'  => array(
                                'EmpleadoresConcepto.desde'     => '0000-00-00',
                                'EmpleadoresConcepto.desde <='  => $opciones['desde'])),
                        array('OR'  => array(
                                'EmpleadoresConcepto.hasta'     => '0000-00-00',
                                'EmpleadoresConcepto.hasta >='  => $opciones['hasta'])))
                ),
                array(
                    'alias' => 'ConveniosConcepto',
                    'table' => 'convenios_conceptos',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(
                            'RelacionesConcepto.concepto_id = ConveniosConcepto.concepto_id',
                            'ConveniosConcepto.convenio_id'   => $opciones['relacion']['ConveniosCategoria']['convenio_id']),
                        array('OR'  => array(
                                'ConveniosConcepto.desde'     => '0000-00-00',
                                'ConveniosConcepto.desde <='  => $opciones['desde'])),
                        array('OR'  => array(
                                'ConveniosConcepto.hasta'     => '0000-00-00',
                                'ConveniosConcepto.hasta >='  => $opciones['hasta'])))
                ),
                array(
                    'alias' => 'Concepto',
                    'table' => 'conceptos',
                    'type' 	=> 'INNER',
                    'conditions' => array(
                        array(	'RelacionesConcepto.concepto_id = Concepto.id'),
                        array('OR'  => array(
                                'Concepto.desde'     => '0000-00-00',
                                'Concepto.desde <='  => $opciones['desde'])),
                        array('OR'  => array(
                                'Concepto.hasta'     => '0000-00-00',
                                'Concepto.hasta >='  => $opciones['hasta'])))
                ),
                array(
                    'alias' => 'Coeficiente',
                    'table' => 'coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Concepto.coeficiente_id = Coeficiente.id'))
                ),
                array(
                    'alias' => 'EmpleadoresCoeficiente',
                    'table' => 'empleadores_coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Coeficiente.id = EmpleadoresCoeficiente.coeficiente_id',
                                'EmpleadoresCoeficiente.empleador_id'   => $opciones['relacion']['Relacion']['empleador_id']))
                ),
                array(
                    'alias' => 'Area',
                    'table' => 'areas',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Area.id' => $opciones['relacion']['Relacion']['area_id']))
                ),
                array(
                    'alias' => 'AreasCoefiente',
                    'table' => 'areas_coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Area.id = AreasCoefiente.area_id',
                                'Coeficiente.id = AreasCoefiente.coeficiente_id'))
                ));

			$conditions = array(
                'RelacionesConcepto.relacion_id' => $opciones['relacion']['Relacion']['id'],
                array('OR'	=> array(
                    'RelacionesConcepto.desde'      => '0000-00-00',
                    'RelacionesConcepto.desde <='   => $opciones['desde'])),
                array('OR'	=> array(
                    'RelacionesConcepto.hasta'      => '0000-00-00',
                    'RelacionesConcepto.hasta >='   => $opciones['hasta'])));

		} elseif ($tipo === 'Empleador') {
			$fields = am($fieldsEmpleadoresConcepto, $fieldsConveniosConcepto, $fieldsConceptos, $fieldCoeficientes, $fieldEmpleadoresCoeficiente);
			$table 	= 	'empleadores_conceptos';
			$joins 	=	array(
                array(
                    'alias' => 'ConveniosConcepto',
                    'table' => 'convenios_conceptos',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(
                            'EmpleadoresConcepto.concepto_id = ConveniosConcepto.concepto_id',
                            'ConveniosConcepto.convenio_id' => $opciones['relacion']['ConveniosCategoria']['convenio_id'],
                            array('OR'	=> array(
                                'ConveniosConcepto.desde'       => '0000-00-00',
                                'ConveniosConcepto.desde <='    => $opciones['desde'])),
                            array('OR'	=> array(
                                'ConveniosConcepto.hasta'       => '0000-00-00',
                                'ConveniosConcepto.hasta >='    => $opciones['hasta']))))
                ),
                array(
                    'alias' => 'Concepto',
                    'table' => 'conceptos',
                    'type' 	=> 'INNER',
                    'conditions' => array(
                        array(	'EmpleadoresConcepto.concepto_id = Concepto.id'))
                ),
                array(
                    'alias' => 'Coeficiente',
                    'table' => 'coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Concepto.coeficiente_id = Coeficiente.id'))
                ),
                array(
                    'alias' => 'EmpleadoresCoeficiente',
                    'table' => 'empleadores_coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Coeficiente.id = EmpleadoresCoeficiente.coeficiente_id',
                                'EmpleadoresCoeficiente.empleador_id' => $opciones['relacion']['Relacion']['empleador_id']))
                ));
			$conditions = array(
							'EmpleadoresConcepto.empleador_id' => $opciones['relacion']['Relacion']['empleador_id'],
							array('OR'	=> array(
                                'EmpleadoresConcepto.desde'     => '0000-00-00',
                                'EmpleadoresConcepto.desde <='  => $opciones['desde'])),
							array('OR'	=> array(
                                'EmpleadoresConcepto.hasta'     => '0000-00-00',
								'EmpleadoresConcepto.hasta >='  => $opciones['hasta']))
						);
		} elseif ($tipo === 'ConvenioColectivo') {
			$fields = am($fieldsConveniosConcepto, $fieldsConceptos, $fieldCoeficientes, $fieldEmpleadoresCoeficiente);
			$table 	= 	'convenios_conceptos';
			$joins 	=	array(
                array(
                    'alias' => 'Concepto',
                    'table' => 'conceptos',
                    'type' 	=> 'INNER',
                    'conditions' => array(
                        array(	'ConveniosConcepto.concepto_id = Concepto.id'))
                ),
                array(
                    'alias' => 'Coeficiente',
                    'table' => 'coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Concepto.coeficiente_id = Coeficiente.id'))
                ),
                array(
                    'alias' => 'EmpleadoresCoeficiente',
                    'table' => 'empleadores_coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Coeficiente.id = EmpleadoresCoeficiente.coeficiente_id',
                                'EmpleadoresCoeficiente.empleador_id'	=> $opciones['relacion']['Relacion']['empleador_id']))
                )
            );
            $conditions = array(
                            'ConveniosConcepto.convenio_id' => $opciones['relacion']['ConveniosCategoria']['convenio_id'],
                            array('OR'	=> array(
                                'ConveniosConcepto.desde'       => '0000-00-00',
                                'ConveniosConcepto.desde <='    => $opciones['desde'])),
                            array('OR'	=> array(
                                'ConveniosConcepto.hasta'       => '0000-00-00',
                                'ConveniosConcepto.hasta >='    => $opciones['hasta'])));
            
		} elseif ($tipo === 'ConceptoPuntual') {
			$fields = am($fieldsRelaciones, $fieldsEmpleadoresConcepto, $fieldsConveniosConcepto, $fieldsConceptos, $fieldCoeficientes, $fieldEmpleadoresCoeficiente, $fieldAreasCoeficiente);
			$table 	= 	'conceptos';
			$joins 	=	array(
                array(
                    'alias' => 'RelacionesConcepto',
                    'table' => 'relaciones_conceptos',
                    'type'  => 'LEFT',
                    'conditions' => array(
                        array(
                            'RelacionesConcepto.concepto_id = Concepto.id',
                            'RelacionesConcepto.relacion_id' => $opciones['relacion']['Relacion']['id']))
                ),
                array(
                    'alias' => 'ConveniosConcepto',
                    'table' => 'convenios_conceptos',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(
                            'Concepto.id = ConveniosConcepto.concepto_id'),
                            'ConveniosConcepto.convenio_id' => $opciones['relacion']['ConveniosCategoria']['convenio_id'])
                ),
                array(
                    'alias' => 'EmpleadoresConcepto',
                    'table' => 'empleadores_conceptos',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(
                            'Concepto.id = EmpleadoresConcepto.concepto_id',
                            'EmpleadoresConcepto.empleador_id' => $opciones['relacion']['Relacion']['empleador_id'] ))
                ),
                array(
                    'alias' => 'Coeficiente',
                    'table' => 'coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(	'Concepto.coeficiente_id = Coeficiente.id'))
                ),
                array(
                    'alias' => 'EmpleadoresCoeficiente',
                    'table' => 'empleadores_coeficientes',
                    'type' 	=> 'LEFT',
                    'conditions' => array(
                        array(
                            'Coeficiente.id = EmpleadoresCoeficiente.coeficiente_id',
                            'EmpleadoresCoeficiente.empleador_id'	=> $opciones['relacion']['Relacion']['empleador_id']))
                ),
                array(
                    'alias' => 'Area',
                    'table' => 'areas',
                    'type'  => 'LEFT',
                    'conditions' => array(
                        array(  'Area.id' => $opciones['relacion']['Relacion']['area_id']))
                ),
                array(
                    'alias' => 'AreasCoefiente',
                    'table' => 'areas_coeficientes',
                    'type'  => 'LEFT',
                    'conditions' => array(
                        array(  'Area.id = AreasCoefiente.area_id',
                                'Coeficiente.id = AreasCoefiente.coeficiente_id'))
                )
            );

			$conditions = array(
				'Concepto.codigo' => $opciones['codigoConcepto'],
				array('OR'	=> array(
					'Concepto.desde' => '0000-00-00',
					'Concepto.desde <=' => $opciones['desde'])
				),
				array('OR'	=> array(
					'Concepto.hasta' => '0000-00-00',
					'Concepto.hasta >=' => $opciones['hasta'])
				)
			);
		} elseif ($tipo === 'Todos') {

			$fields = $fieldsConceptos;
			$table	= 'conceptos';
			$conditions = array(
                array('OR'	=> array(
                        'Concepto.desde'      => '0000-00-00',
                        'Concepto.desde <=' => $opciones['desde'])),
                array('OR'	=> array(
                        'Concepto.hasta'      => '0000-00-00',
                        'Concepto.hasta >=' => $opciones['hasta'])));
			$order	= 'Concepto.nombre, Concepto.codigo';
		}

		$dbo = $this->getDataSource();
		$orderExpression = $dbo->expression($order);
		$sql = $dbo->buildStatement(array(
			'fields'		=> $fields,
			'table' 		=> $dbo->fullTableName($table),
			'alias' 		=> Inflector::classify($table),
			'conditions'	=> $conditions,
			'limit' 		=> null,
			'offset' 		=> null,
			'order' 		=> $orderExpression,
			'group' 		=> null,
			'joins' 		=> $joins), $this);
		$r = $this->query($sql);
/*
		debug(Debugger::trace());
		debug($sql);
		if (!empty($opciones['codigoConcepto']) && $opciones['codigoConcepto'] == 'acuerdo_comercio_abril_2008') {
			d($r);
		}
*/


		$conceptos = array();
		foreach ($r as $v) {

			/**
			* En principio tomo el concepto como verdad, luego puede estar sobreescrito.
			* La jerarquia es: 	Relacion,
								Empleador,
								Convenio Colectivo,
								Concepto.
			*/

			/**
			* Descarto por las fechas de vigencias.
			*/
			
			/**
			* De la relacion.
			*/
			if (!empty($v['RelacionesConcepto']['desde']) && $v['RelacionesConcepto']['desde'] != '0000-00-00' && $v['RelacionesConcepto']['desde'] > $opciones['desde']) {
				continue;
			}
			if (!empty($v['RelacionesConcepto']['hasta']) && $v['RelacionesConcepto']['hasta'] != '0000-00-00' && $v['RelacionesConcepto']['hasta'] < $opciones['hasta']) {
				continue;
			}
			
			/**
			* Del empleador.
			*/
			if (!empty($v['EmpleadoresConcepto']['desde']) && $v['EmpleadoresConcepto']['desde'] != '0000-00-00' && $v['EmpleadoresConcepto']['desde'] > $opciones['desde']) {
				continue;
			}
			if (!empty($v['EmpleadoresConcepto']['hasta']) && $v['EmpleadoresConcepto']['hasta'] != '0000-00-00' && $v['EmpleadoresConcepto']['hasta'] < $opciones['hasta']) {
				continue;
			}

			/**
			* Del convenio.
			*/
			if (!empty($v['ConveniosConcepto']['desde']) && $v['ConveniosConcepto']['desde'] != '0000-00-00' && $v['ConveniosConcepto']['desde'] > $opciones['desde']) {
				continue;
			}
			if (!empty($v['ConveniosConcepto']['hasta']) && $v['ConveniosConcepto']['hasta'] != '0000-00-00' && $v['ConveniosConcepto']['hasta'] < $opciones['hasta']) {
				continue;
			}
			
			/**
			* Del concepto.
			*/
			if (empty($v['Concepto']['id'])) {
				continue;
			}
			if (!empty($v['Concepto']['desde']) && $v['Concepto']['desde'] != '0000-00-00' && $v['Concepto']['desde'] > $opciones['desde']) {
				continue;
			}
			if (!empty($v['Concepto']['hasta']) && $v['Concepto']['hasta'] != '0000-00-00' && $v['Concepto']['hasta'] < $opciones['hasta']) {
				continue;
			}


			/**
			* Asigo como valido el concepto y su coeficiente,
			* luego, en base a la jerarquia, sobreescribo la formula si coresponde.
			if (isset($v['Coeficiente'])) {
				//$conceptos[$v['Concepto']['codigo']] = am($v['Concepto'], $v['Coeficiente']);
			}
			else {
				//$conceptos[$v['Concepto']['codigo']] = $v['Concepto'];
			}
			*/
			$conceptos[$v['Concepto']['codigo']] = $v['Concepto'];
			$conceptos[$v['Concepto']['codigo']]['concepto_id'] = $v['Concepto']['id'];

			/**
			* Verifico que el valor del coeficiente no haya sido sobreescrito por el empleador o por el area.
			*/
			$conceptos[$v['Concepto']['codigo']]['coeficiente_id'] = $v['Coeficiente']['id'];
			$conceptos[$v['Concepto']['codigo']]['coeficiente_nombre'] = $v['Coeficiente']['nombre'];
			$conceptos[$v['Concepto']['codigo']]['coeficiente_tipo'] = $v['Coeficiente']['tipo'];
			$coeficienteValor = $v['Coeficiente']['valor'];
			$conceptos[$v['Concepto']['codigo']]['coeficiente_valor'] = $coeficienteValor;
			if (!empty($v['EmpleadoresCoeficiente']['porcentaje'])) {
				$conceptos[$v['Concepto']['codigo']]['coeficiente_valor'] = $coeficienteValor + ($coeficienteValor * $v['EmpleadoresCoeficiente']['porcentaje'] / 100);
			}
			if (!empty($v['AreasCoefiente']['porcentaje'])) {
				$conceptos[$v['Concepto']['codigo']]['coeficiente_valor'] = $coeficienteValor + ($coeficienteValor * $v['AreasCoefiente']['porcentaje'] / 100);
			}

			/**
			* Sobreescribo Formulas.
			* Nota: El elemento 0 surge de la funcion replace de RelacionesConcepto.
			*/
			if (!empty($v['0']['formula'])) {
				$conceptos[$v['Concepto']['codigo']]['formula'] = $v['0']['formula'];
				$conceptos[$v['Concepto']['codigo']]['jerarquia'] = 'Relacion';
			} elseif (!empty($v['EmpleadoresConcepto']['formula'])) {
				$conceptos[$v['Concepto']['codigo']]['formula'] = $v['EmpleadoresConcepto']['formula'];
				$conceptos[$v['Concepto']['codigo']]['jerarquia'] = 'Empleador';
			} elseif (!empty($v['ConveniosConcepto']['formula'])) {
				$conceptos[$v['Concepto']['codigo']]['formula'] = $v['ConveniosConcepto']['formula'];
				$conceptos[$v['Concepto']['codigo']]['jerarquia'] = 'Convenio';
			} else {
				$conceptos[$v['Concepto']['codigo']]['jerarquia'] = 'Concepto';
			}
			
			/**
			* Sobreescribo ids.
			*/
			if (!empty($v['RelacionesConcepto']['id'])) {
				$conceptos[$v['Concepto']['codigo']]['id'] = $v['RelacionesConcepto']['id'];
			} elseif (!empty($v['EmpleadoresConcepto']['formula'])) {
				$conceptos[$v['Concepto']['codigo']]['id'] = $v['EmpleadoresConcepto']['id'];
			} elseif (!empty($v['ConveniosConcepto']['formula'])) {
				$conceptos[$v['Concepto']['codigo']]['id'] = $v['ConveniosConcepto']['id'];
			}
		}
		return $conceptos;
	}


/**
 * TODO: VERIFICAR de dar un mensaje mas especifico de los errores. Revisar logica, anda, pero se puede mejorar.
 * Agrega o Quita un concepto o varios conceptos a una o varias relaciones.
 *
 * @param array $relaciones Los id de las relaciones a las cuales se les agregaran o quitaran los conceptos.
 * @param array $conceptos Los id de los conceptos que se agregaran o quitaran.
 * @param array $opciones Los id de los conceptos que se agregaran o quitaran.
 * @return boolean
 * @param array $opciones Los id de los conceptos que se agregaran o quitaran.
 */
	function agregarQuitarConcepto($relaciones = array(), $conceptos = array(), $opciones) {
		$c = 0;
		if (isset($opciones['accion']) && in_array($opciones['accion'], array('quitar', 'agregar'))) {
			$error = false;
			$this->begin();
			foreach ($relaciones as $relacion_id) {
				foreach ($conceptos as $concepto_id) {
					$save['relacion_id'] = $relacion_id;
					$save['concepto_id'] = $concepto_id;
					$this->RelacionesConcepto->recursive = -1;
					$existe = $this->RelacionesConcepto->find($save);
					if (empty($existe) && $opciones['accion'] === 'agregar') {
						$this->RelacionesConcepto->create();
						if ($this->RelacionesConcepto->save($save)) {
							$c++;
						} else {
							$error = true;
							break 2;
						}
					} elseif (!empty($existe) && $opciones['accion'] === 'quitar') {
						if ($this->RelacionesConcepto->delete($existe['RelacionesConcepto']['id'])) {
							$c++;
						} else {
							$error = true;
							break 2;
						}
					}
				}
			}
			if ($error) {
				$this->rollback();
			} else {
				$this->commit();
			}
		}
		return $c;
	}
}
?>