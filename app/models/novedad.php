<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las novedades.
 * Una novedad es un ingreso de datos al sistema no confirmado aun.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1329 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-27 14:11:26 -0300 (jue 27 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las novedades.
 * Una novedad es un ingreso de datos al sistema no confirmado aun.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Novedad extends AppModel {

    var $permissions = array('permissions' => 480, 'group' => 'default', 'role' => 'all');

/**
 * Los modificaciones al comportamiento estandar de app_controller.php
 *
 * @var array
 * @access public
*/
	var $modificadores = array(	'index'	=> 
			array('contain'	=> array('Relacion' => array('Empleador', 'Trabajador'))),
								'edit'	=>
			array('contain'	=> array('Concepto', 'Relacion'	=> array('Empleador', 'Trabajador'))));

/**
 * Las opciones validadas de formatos de planillas que se podran generar e importar.
 *
 * @var array
 * @access public
 */
	var $opciones = array('formato'=>array('Excel5' => 'Excel', 'Excel2007' => 'Excel 2007'));
	
	var $belongsTo = array('Relacion', 'Concepto', 'Liquidacion');

    var $breadCrumb = array('format' => '%s %s (%s)', 
                            'fields' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));    

/**
 * Based on novelty type, mark the existance of a previewsly informed novelty.
 */
	function afterFind($results, $primary = false) {
		if ($primary && isset($results[0]['Novedad'])) {
			foreach ($results as $k => $v) {
				$existe = false;
				if (isset($v['Novedad']['tipo'])) {
					if ($v['Novedad']['tipo'] === 'Concepto') {
						
						$conditions = null;
						$conditions['relacion_id'] = $results[$k]['Novedad']['relacion_id'];
						$conditions['tipo'] = $results[$k]['Novedad']['tipo'];
						$conditions['subtipo'] = $results[$k]['Novedad']['subtipo'];
						$conditions['periodo'] = $results[$k]['Novedad']['periodo'];
						$conditions['estado'] = array('Confirmada', 'Liquidada');
						if ($this->hasAny($conditions) > 1) {
							$existe = true;
						}
						
						$results[$k]['Novedad']['subtipo'] = array_pop(explode(':', $v['Novedad']['subtipo']));
					} elseif ($v['Novedad']['tipo'] === 'Horas') {
						$Hora = ClassRegistry::init('Hora');
						$find = null;
						$find['Hora.tipo'] = $v['Novedad']['subtipo'];
						$find['Hora.periodo'] = $v['Novedad']['periodo'];
						$find['Hora.relacion_id'] = $v['Novedad']['relacion_id'];
						$existe = $Hora->find('count', array(	'recursive' 	=> -1,
											 					'checkSecurity'	=> false,
											 					'conditions' 	=> $find));
					} elseif ($v['Novedad']['tipo'] === 'Ausencias') {
						$Ausencia = ClassRegistry::init('Ausencia');
						$find = null;
						$periodo = $this->format($v['Novedad']['periodo'], 'periodo');
						$find['Ausencia.desde >='] = $periodo['desde'];
						$find['Ausencia.desde <='] = $periodo['hasta'];
						$find['Ausencia.relacion_id'] = $v['Novedad']['relacion_id'];
						$existe = $Ausencia->find('first', array(	'recursive' 	=> -1, 
											 						'checkSecurity'	=> false,
											 						'conditions' 	=> $find));
                    } elseif ($v['Novedad']['tipo'] === 'Vacaciones') {
                        $Vacacion = ClassRegistry::init('Vacacion');
                        $find = null;
                        $find['Vacacion.periodo'] = $v['Novedad']['periodo'];
                        $find['Vacacion.relacion_id'] = $v['Novedad']['relacion_id'];
                        $existe = $Vacacion->find('first', array(   'recursive'     => -1,
                                                                    'checkSecurity' => false,
                                                                    'conditions'    => $find));
					} elseif ($v['Novedad']['tipo'] === 'Vales') {
						$Descuento = ClassRegistry::init('Descuento');
						$find = null;
						$periodo = $this->format($v['Novedad']['periodo'], 'periodo');
						$find['Descuento.tipo'] = 'Vale';
						$find['Descuento.desde >='] = $periodo['desde'];
						$find['Descuento.desde <='] = $periodo['hasta'];
						$find['Descuento.relacion_id'] = $v['Novedad']['relacion_id'];
						$existe = $Descuento->find('first', array(	'recursive' 	=> -1, 
											 						'checkSecurity'	=> false,
											 						'conditions' 	=> $find));
					}
				}
				$results[$k]['Novedad']['existe'] = false;
				if (!empty($existe)) {
					$results[$k]['Novedad']['existe'] = true;
				}
			}
		}
		return parent::afterFind($results, $primary);
	}
	
/**
 * Graba las novedades provenientes desde la planilla.
 * Maneja transacciones.
 *
 * @param array $datos Los datos a grabar.
 * @return boolean True si fue posible guardar las novedades ingresadas, false en otro caso
 * @access public 
 */
 	function grabar($datos) {

        App::import('Vendor', 'dates', 'pragmatia');
        
		$predefinidos = $this->getIngresosPosibles('predefinidos');
		foreach ($datos as $r => $data) {
			$tmp = explode('|', $r);
			$relacion_id = $tmp[0];
			$periodo = $tmp[1];
			$receiptType = $tmp[2];

			foreach ($data as $tipo => $registros) {
				foreach ($registros as $subTipo => $registro) {
				
					$save = null;
					$save['Novedad']['id'] = null;
					$save['Novedad']['periodo'] = $periodo;
                    $save['Novedad']['liquidacion_tipo'] = $receiptType;
					$save['Novedad']['alta'] = date('Y-m-d');
					$save['Novedad']['estado'] = 'Pendiente';
					$save['Novedad']['relacion_id'] = $relacion_id;
					
					if (!in_array($tipo, $predefinidos)) {
						/**
						* Busco el id del concepto correspondiente al nombre que importe desde la planilla.
						*/
						$this->Relacion->RelacionesConcepto->Concepto->recursive = -1;
						$concepto = $this->Relacion->RelacionesConcepto->Concepto->findByNombre($tipo);
						if (empty($concepto['Concepto']['id'])) {
							continue;
						}
						$save['Novedad']['data'] = $registro;
                        $save['Novedad']['concepto_id'] = $concepto['Concepto']['id'];
						$save['Novedad']['tipo'] = 'Concepto';
						$save['Novedad']['subtipo'] = $concepto['Concepto']['id'] . ':' . $concepto['Concepto']['codigo'];
					} else {
						$save['Novedad']['concepto_id'] = null;
						$save['Novedad']['tipo'] = $tipo;
						$save['Novedad']['subtipo'] = $subTipo;
						$save['Novedad']['data'] = $registro;
						
						/**
						* Le doy un tratamiento especial a las ausencias, para ya dejar el motivo
						* especificado.
						*/
						if ($tipo === 'Ausencias') {
							if ($subTipo === 'Dias') {
								$save['Novedad']['subtipo'] = '1:Justificada por Enfermedad';
								if (!empty($registros['Desde'])) {
									/** 25569 = Days between 1970-01-01 and 1900-01-01 */
									$save['Novedad']['data'] = $this->format(Dates::dateAdd('1970-01-01', $registros['Desde'] - 25569, 'd', array('fromInclusive' => false)), 'date') . '|' . $registros['Dias'];
								} else {
									$save['Novedad']['data'] = $registros['Dias'];
								}
								if (isset($datos[$r][$tipo]['Motivo'])) {
									$this->Relacion->Ausencia->AusenciasMotivo->recursive = -1;
									$motivo = $this->Relacion->Ausencia->AusenciasMotivo->findByMotivo($datos[$r][$tipo]['Motivo']);
									if (!empty($motivo)) {
										$save['Novedad']['subtipo'] = $motivo['AusenciasMotivo']['id'] . ':' . $datos[$r][$tipo]['Motivo'];
									}
								}
							} else {
								continue;
							}
                        } elseif ($tipo === 'Vacaciones') {
                            if ($subTipo === 'Dias'
								&& !empty($data[$tipo]['Inicio'])) {
                                $save['Novedad']['periodo'] = $data[$tipo]['Periodo'];
                                $save['Novedad']['data'] = $this->format(Dates::dateAdd('1970-01-01', $data[$tipo]['Inicio'] - 25569, 'd', array('fromInclusive' => false)), 'date') . '|' . $registro;
                            } else {
                                continue;
                            }
                        }
					}
					$saveAll[] = $save;
				}
			}
		}
		return $this->saveAll($saveAll);
	}



	function getNovedades($relacion, $periodo, $receiptType) {

		$conditions = array(
			'Novedad.estado'           => 'Confirmada',
			'Novedad.liquidacion_tipo' => $receiptType,
			'Novedad.tipo' 			   => 'Concepto',
			'Novedad.relacion_id'	   => $relacion['Relacion']['id']);

		if ($receiptType != 'final') {
			if ($periodo['periodo'] === 'M') {
				$period[] = $periodo['ano'] . $periodo['mes'] . 'M';
				$period[] = $periodo['ano'] . $periodo['mes'] . '1Q';
				$period[] = $periodo['ano'] . $periodo['mes'] . '2Q';
			} else {
				$period[] = $periodo['periodoCompleto'];
			}
			$conditions['Novedad.periodo'] = $period;
		}

		$novedades = $this->find('all', array(
			'conditions' 	=> $conditions,
			'recursive'		=> -1));

		$variables = $conceptos = $auxiliares = array();
		if (!empty($novedades)) {
			$Concepto = ClassRegistry::init('Concepto');
			foreach ($novedades as $novedad) {
				$conceptoCodigo = array_pop(explode(':', $novedad['Novedad']['subtipo']));
				$variables['#' . $conceptoCodigo] = $novedad['Novedad']['data'];
                $conceptos = array_merge($conceptos, $Concepto->findConceptos('ConceptoPuntual', array('relacion' => $relacion, 'codigoConcepto' => $conceptoCodigo)));

				$auxiliar = null;
				$auxiliar['id'] = $novedad['Novedad']['id'];
				$auxiliar['estado'] = 'Liquidada';
                $auxiliar['permissions'] = '288';
				$auxiliar['liquidacion_id'] = '##MACRO:liquidacion_id##';
				$auxiliares[] = array('save'=>serialize($auxiliar), 'model' => 'Novedad');
			}
		}
		return array('conceptos' => $conceptos, 'variables' => $variables, 'auxiliar' => $auxiliares);
	}

	
/**
 * Distribuye las novedades en las diferentes tablas (horas, ausencias, descuentos).
 *
 * @param array $ids Los ids de las novedades a distribuir en cada tabla.
 * @return mixed Cantidad de novedades distribuidas. False en caso de error o que no hayn podido confirmarse todos los ids.
 * @access public
 */
	function confirmar($ids) {
		$novedades = $this->find('all',
				array('conditions' 	=> array('Novedad.id' => $ids), 
					  'recursive'	=> -1));
		$c = $i = $ii = 0;

		$excludeIds = array();
		foreach ($novedades as $novedad) {
			$periodo = $this->format($novedad['Novedad']['periodo'], 'periodo');
			switch ($novedad['Novedad']['tipo']) {
                case 'Vacaciones':
                    $vacacion = ClassRegistry::init('Vacacion')->find('first', array(
                        'conditions' => array(
                            'Vacacion.relacion_id' => $novedad['Novedad']['relacion_id'],
                            'Vacacion.periodo'     => $novedad['Novedad']['periodo'])));
                    if (!empty($vacacion)) {
                        $tmp = explode('|', $novedad['Novedad']['data']);
                        $saves[$i]['VacacionesDetalle']['id'] = null;
                        $saves[$i]['VacacionesDetalle']['vacacion_id'] = $vacacion['Vacacion']['id'];
                        $saves[$i]['VacacionesDetalle']['desde'] = $tmp[0];
                        $saves[$i]['VacacionesDetalle']['dias'] = $tmp[1];
                        $saves[$i]['VacacionesDetalle']['estado'] = 'Confirmado';
                        $saves[$i]['VacacionesDetalle']['observacion'] = 'Ingresado desde planilla. Confirmado el ' . date('Y-m-d');
                    }
                break;
				case 'Horas':
					$saves[$i]['Hora']['id'] = null;
					$saves[$i]['Hora']['tipo'] = $novedad['Novedad']['subtipo'];
					$saves[$i]['Hora']['cantidad'] = $novedad['Novedad']['data'];
					$saves[$i]['Hora']['estado'] = 'Confirmada';
					$saves[$i]['Hora']['relacion_id'] = $novedad['Novedad']['relacion_id'];
					$saves[$i]['Hora']['periodo'] = $periodo['periodoCompleto'];
					$saves[$i]['Hora']['liquidacion_tipo'] = $novedad['Novedad']['liquidacion_tipo'];
					$saves[$i]['Hora']['observacion'] = 'Ingresado desde planilla. Confirmado el ' . date('Y-m-d');
				break;
				case 'Ausencias':
					$saves[$i]['Ausencia']['id'] = null;
					if (strpos($novedad['Novedad']['data'], '|') !== false) {
						$tmp = explode('|', $novedad['Novedad']['data']);
						$saves[$i]['Ausencia']['desde'] = $tmp[0];
						$novedad['Novedad']['data'] = $tmp[1];
					} else {
						$saves[$i]['Ausencia']['desde'] = $this->format($periodo['desde'], 'date');
					}
					$saves[$i]['Ausencia']['ausencia_motivo_id'] = array_shift(explode(':', $novedad['Novedad']['subtipo']));
					$saves[$i]['Ausencia']['relacion_id'] = $novedad['Novedad']['relacion_id'];
					$saves[$i]['AusenciasSeguimiento'][$ii]['dias'] = $novedad['Novedad']['data'];
					$saves[$i]['AusenciasSeguimiento'][$ii]['observacion'] = 'Ingresado desde planilla. Confirmado el ' . date('Y-m-d');
					$saves[$i]['AusenciasSeguimiento'][$ii]['estado'] = 'Confirmado';
					$ii++;
				break;
				case 'Vales':
					$saves[$i]['Descuento']['id'] = null;
					$saves[$i]['Descuento']['alta'] = $this->format($periodo['desde'], 'date');
					$saves[$i]['Descuento']['desde'] = $saves[$i]['Descuento']['alta'];
					$saves[$i]['Descuento']['relacion_id'] = $novedad['Novedad']['relacion_id'];
					$saves[$i]['Descuento']['monto'] = $novedad['Novedad']['data'];
					$saves[$i]['Descuento']['tipo'] = 'Vale';
					$saves[$i]['Descuento']['descontar'] = array('1');
					$saves[$i]['Descuento']['concurrencia'] = 'Permite superponer';
					$saves[$i]['Descuento']['estado'] = 'Activo';
					$saves[$i]['Descuento']['observacion'] = 'Ingresado desde planilla. Confirmado el ' . date('Y-m-d');
				break;
				case 'Concepto':
					$excludeIds[] = $novedad['Novedad']['id'];
					$saves[$i]['Novedad']['id'] = $novedad['Novedad']['id'];
					$saves[$i]['Novedad']['liquidacion_tipo'] = $novedad['Novedad']['liquidacion_tipo'];
					$saves[$i]['Novedad']['estado'] = 'Confirmada';
				break;
			}
			$i++;
		}

        if (!empty($saves)) {
            $db = ConnectionManager::getDataSource($this->useDbConfig);
            $db->begin($this);
            foreach ($saves as $save) {
                $keys = array_keys($save);
                if (($keys[0] == 'VacacionesDetalle' && $this->Relacion->Vacacion->{$keys[0]}->appSave($save)) || $this->Relacion->{$keys[0]}->appSave($save)) {
					$idByType[$keys[0]][] = $this->Relacion->{$keys[0]}->id;
                    $c++;
                }
            }

            if ($i === $c) {
				$diff = array_diff($ids, $excludeIds);
				if (!empty($diff)) {
                	$this->deleteAll(array('Novedad.id' => $diff), false, false, false);
				}
				$db->commit($this);
				return array('idByType' => $idByType, 'quantity' => $c);
            } else {
                $db->rollback($this);
                return false;
            }
        } else {
            return false;
        }
	}


/** When manually inserted, must create the subtipo field */
    function beforeSave($options = array()) {
        if (!empty($this->data['Novedad']['concepto_id__']) && empty($this->data['Novedad']['subtipo'])) {
            $this->data['Novedad']['subtipo'] = $this->data['Novedad']['concepto_id'] . ':' . array_shift(explode(' ', array_shift(explode('-', $this->data['Novedad']['concepto_id__']))));
        }
        return parent::beforeSave($options);
    }


/**
 * Obtiene un listado de los posibles campos que puedo ingresar por novedades.
 *
 * @return Array con los posibles campos que debo ingresar.
 * @access public.
 */
	function getIngresosPosibles($tipo = 'todos') {
		$predefinidos[] = 'Horas';
		$predefinidos[] = 'Ausencias';
		$predefinidos[] = 'Vales';
        $predefinidos[] = 'Vacaciones';
		if ($tipo === 'todos') {
			$Concepto = new Concepto();
			$tmpConceptos = $Concepto->find('all', array(
                'conditions'    => array('Concepto.novedad' => 'Si'),
                'order'         => array('Concepto.tipo', 'Concepto.nombre'),
                'recursive'     => -1));
            $conceptos = array();
            foreach ($tmpConceptos as $concepto) {
                if ($concepto['Concepto']['tipo'] == 'Remunerativo') {
                    $type = 'R';
                } elseif ($concepto['Concepto']['tipo'] == 'No Remunerativo') {
                    $type = 'NR';
                } else {
                    $type = 'D';
                }
                $conceptos[] = $concepto['Concepto']['nombre'] . ' (' . $type . ')';
            }
			return array_merge($predefinidos, $conceptos);
		} elseif ($tipo === 'predefinidos') {
			return $predefinidos;	
		}
	}

}
?>