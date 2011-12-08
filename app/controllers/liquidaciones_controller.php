<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la liquidacion de sueldos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1452 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-06-26 19:13:37 -0300 (dom 26 de jun de 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a la liquidacion de sueldos.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class LiquidacionesController extends AppController {

    var $paginate = array(
        'order' => array(
            'Liquidacion.ano'       				=> 'DESC',
            'Liquidacion.mes'       				=> 'DESC',
            'Liquidacion.periodo'   				=> 'DESC',
			'IF(Liquidacion.trabajador_cbu="",0,1)' => 'DESC',
            'Liquidacion.empleador_nombre',
            'Liquidacion.trabajador_apellido',
            'Liquidacion.trabajador_nombre'
        )
    );

	var $components = array('Formulador');
	var $helpers = array('Documento');



	function reporte_totales_zonas() {

		if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {


			$conditions['(Liquidacion.group_id & ' . array_sum($this->data['Condicion']['Bar-grupo_id']) . ') >'] = 0;

            $conditions['Liquidacion.estado'] = 'Confirmada';


            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Liquidacion.empleador_id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
            }


            if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
                $period = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
                $conditions['Liquidacion.ano'] = $period['ano'];
                $conditions['Liquidacion.mes'] = $period['mes'];
            }

			$this->Liquidacion->Behaviors->detach('Permisos');
			$r = $this->Liquidacion->find('all', array(
				'recursive'		=> -1,
				'fields'		=> array(
					'Zone.name,
					SUM(Liquidacion.remunerativo) AS total_remunerativo,
					SUM(Liquidacion.no_remunerativo) AS total_no_remunerativo,
					SUM(Liquidacion.deduccion) AS total_deduccion,
					SUM(Liquidacion.total) AS total'
				),
				'group'			=> array('Zone.name'),
				'conditions'	=> $conditions,
				'joins' 		=> array(
					array(
						'table' 		=> 'areas',
						'alias' 		=> 'Area',
						'type' 			=> 'INNER',
						'foreignKey' 	=> false,
						'conditions'	=> array('Liquidacion.relacion_area_id = Area.id')
					),
					array(
						'table' 		=> 'zones',
						'alias' 		=> 'Zone',
						'type' 			=> 'INNER',
						'foreignKey' 	=> false,
						'conditions'	=> array('Zone.id = Area.zone_id')
					)
				)
			));

            $this->set('data', $r);
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
		}
	}


	function reporte_liquidaciones_finales() {

        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

			$and = array();
			if (!empty($this->data['Condicion']['Bar-grupo_id'])) {
				$and[] = 'Relacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ' = ' . $this->data['Condicion']['Bar-grupo_id'];
			}
			if (!empty($this->data['Condicion']['Bar-desde'])) {
				$and[] = 'RelacionesHistorial.fin >= ' . $this->data['Condicion']['Bar-desde'];
			}
			if (!empty($this->data['Condicion']['Bar-hasta'])) {
				$and[] = 'RelacionesHistorial.fin <= ' . $this->data['Condicion']['Bar-hasta'];
			}

			$sql = "
				select	Trabajador.cuil,
						Trabajador.nombre,
						Trabajador.apellido,
						Empleador.nombre,
						Relacion.ingreso,
						RelacionesHistorial.fin,
						RelacionesHistorial.liquidacion_final
				FROM relaciones Relacion
					INNER JOIN trabajadores Trabajador on (Trabajador.id = Relacion.trabajador_id)
					INNER JOIN empleadores Empleador ON (Relacion.empleador_id = Empleador.id)
					INNER JOIN relaciones_historiales RelacionesHistorial ON (RelacionesHistorial.relacion_id = Relacion.id)
				WHERE 	RelacionesHistorial.estado = 'Confirmado'
				AND 	Relacion.estado = 'Activa'
				AND " . implode(' AND ', $and) . "
				AND 	RelacionesHistorial.liquidacion_final IN ('" . implode("', '", $this->data['Condicion']['Bar-liquidacion_final']) . "')
				ORDER BY RelacionesHistorial.fin DESC";

            $this->set('data', $this->Liquidacion->query($sql));
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
	}

    function reporte_liquidaciones() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            if (empty($this->data['Condicion']['Bar-grupo_id'])) {
                $this->Session->setFlash('Debe seleccionar por lo menos un grupo.', 'error');
                $this->History->goBack();
            }
            
            $conditions['(Liquidacion.group_id & ' . array_sum($this->data['Condicion']['Bar-grupo_id']) . ') >'] = 0;
			$conditions['(Area.group_id & ' . array_sum($this->data['Condicion']['Bar-grupo_id']) . ') >'] = 0;
            foreach ($this->data['Condicion']['Bar-grupo_id'] as $groupId) {
                $groupParams[$groupId] = User::getGroupParams($groupId);
            }

			if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
				$period = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
				$conditions['Liquidacion.ano'] = $period['ano'];
				$conditions['Liquidacion.mes'] = $period['mes'];
				$conditions['Liquidacion.periodo'] = $period['periodo'];
				$periodToShow = $this->data['Condicion']['Bar-periodo_largo'];
			}

			if (!empty($this->data['Condicion']['Bar-desde']) && !empty($this->data['Condicion']['Bar-hasta'])) {
				App::import('Vendor', 'dates', 'pragmatia');
				$periods = Dates::getPeriods($this->data['Condicion']['Bar-desde'], $this->data['Condicion']['Bar-hasta']);
				$periodToShow = implode(', ', $periods);
				foreach ($periods as $period) {
					$years[] = substr($period, 0, 4);
					$months[] = substr($period, 4, 2);
				}
				$conditions['Liquidacion.ano'] = array_unique($years);
				$conditions['Liquidacion.mes'] = array_unique($months);
			}

            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Liquidacion.empleador_id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
            }

            
            $conditions['Liquidacion.estado'] = 'Confirmada';
            
            $data = array();
            $this->Liquidacion->Behaviors->detach('Permisos');
            $this->Liquidacion->contain('Area');

            $sql = '
            SELECT			`Factura`.`id`,
							`Liquidacion`.`empleador_id`,
                            `Liquidacion`.`trabajador_id`,
                            `Liquidacion`.`empleador_cuit`,
                            `Liquidacion`.`empleador_nombre`,
                            `Liquidacion`.`relacion_area_id`,
                            `Area`.`id`,
                            `Area`.`nombre`,
                            `Area`.`group_id`,
                            `Area`.`identificador_centro_costo`,
                            SUM(`Liquidacion`.`remunerativo`) AS remunerativo,
                            SUM(`Liquidacion`.`no_remunerativo`) AS no_remunerativo,
                            `Factura`.`total` AS facturado
            FROM            `areas` AS `Area`, `liquidaciones` AS `Liquidacion`
            LEFT JOIN       `facturas` AS `Factura`
            ON              (`Factura`.`id` = `Liquidacion`.`factura_id` AND `Factura`.`estado` = \'Confirmada\')' . "\n" . 
			ConnectionManager::getDataSource('default')->conditions($conditions) . ' AND `Area`.`id` = `Liquidacion`.`relacion_area_id`
            GROUP BY		`Factura`.`id`,
							`Liquidacion`.`empleador_id`,
                            `Liquidacion`.`trabajador_id`,
                            `Liquidacion`.`empleador_cuit`,
                            `Liquidacion`.`empleador_nombre`,
                            `Liquidacion`.`relacion_area_id`,
                            `Area`.`id`,
                            `Area`.`nombre`,
                            `Area`.`group_id`,
                            `Area`.`identificador_centro_costo`
            ORDER BY        `Liquidacion`.`empleador_nombre`,
                            `Liquidacion`.`trabajador_apellido`,
                            `Liquidacion`.`trabajador_nombre`';

            $workers = array();
			$prevInvoiceId = null;
            foreach ($this->Liquidacion->query($sql) as $record) {

                $record['Area']['nombre'] .= '||' . $record['Area']['group_id'];

                if (empty($data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']])) {
                    $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['trabajadores'] = 0;
                    $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['remunerativo'] = 0;
                    $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['no_remunerativo'] = 0;
                    $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['facturado'] = 0;
                }

                if (empty($data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']][$record['Factura']['id']]['facturado'])) {
                    $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']][$record['Factura']['id']]['facturado'] = true;

					if ($prevInvoiceId != $record['Factura']['id']) {
                    	$data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['facturado'] += $record['Factura']['facturado'];
						$prevInvoiceId = $record['Factura']['id'];
					}
                }

                if (!in_array($record['Liquidacion']['empleador_id'] . '|' . $record['Liquidacion']['trabajador_id'], $workers)) {
                    $workers[] = $record['Liquidacion']['empleador_id'] . '|' . $record['Liquidacion']['trabajador_id'];
                    $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['trabajadores'] += 1;
                }
                $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['remunerativo'] += $record[0]['remunerativo'];
                $data[$record['Area']['identificador_centro_costo']][$record['Liquidacion']['empleador_cuit'] . ' ' . $record['Liquidacion']['empleador_nombre']][$record['Area']['nombre']]['no_remunerativo'] += $record[0]['no_remunerativo'];
            }

            $this->set('data', $data);
            $this->set('groupParams', $groupParams);
            $this->set('period', $periodToShow);
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }

    
    function reporte_sindicatos() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            $conditions['(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Liquidacion.empleador_id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
            }
            if (!empty($this->data['Condicion']['Bar-convenio_id'])) {
                $conditions['Liquidacion.convenio_categoria_convenio_id'] = $this->data['Condicion']['Bar-convenio_id'];
            }
            if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
                $period = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
                $conditions['Liquidacion.ano'] = $period['ano'];
                $conditions['Liquidacion.mes'] = $period['mes'];
            }
            $conditions['LiquidacionesDetalle.concepto_retencion_sindical'] = 'Si';
            $conditions['LiquidacionesDetalle.valor >'] = 0;
            
            $this->Liquidacion->LiquidacionesDetalle->Behaviors->detach('Permisos');
            $this->Liquidacion->LiquidacionesDetalle->contain(array('Liquidacion' => array(
				'Trabajador' => 'ObrasSocial', 'Area')));
            $this->set('data', $this->Liquidacion->LiquidacionesDetalle->find('all', array('conditions' => $conditions)));
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }


    function reporte_resumen() {

        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {
            if (empty($this->data['Condicion']['Bar-empleador_id'])
                && empty($this->data['Condicion']['Bar-grupo_id'])) {
                $this->Session->setFlash('Debe seleccionar por lo menos un Empleador y/o un Grupo.', 'error');
            } elseif (empty($this->data['Condicion']['Bar-periodo_largo_desde']) || $this->Util->format($this->data['Condicion']['Bar-periodo_largo_desde'], 'periodo') === false) {
                $this->Session->setFlash('Debe especificar un periodo valido.', 'error');
            } else {

				$periodFrom = $this->Util->format($this->data['Condicion']['Bar-periodo_largo_desde'], 'periodo');
				if (!empty($this->data['Condicion']['Bar-periodo_largo_hasta'])) {
					App::import('Vendor', 'dates', 'pragmatia');
					$periodTo = $this->Util->format($this->data['Condicion']['Bar-periodo_largo_hasta'], 'periodo');
					$conditions['CONCAT(Liquidacion.ano, LPAD(Liquidacion.mes, 2, \'0\'), Liquidacion.periodo)'] = Dates::getPeriods($periodFrom['desde'], $periodTo['hasta']);
				} else {
					$conditions = array('Liquidacion.periodo'       => $periodFrom['periodo'],
										'Liquidacion.ano'           => $periodFrom['ano'],
										'Liquidacion.mes'           => $periodFrom['mes']);
				}

				$conditions['Liquidacion.tipo'] = $this->data['Condicion']['Bar-tipo'];

                if (!empty($this->data['Condicion']['Bar-estado'])) {
                    $conditions['Liquidacion.estado'] = $this->data['Condicion']['Bar-estado'];
                }

                if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                    $conditions['Liquidacion.empleador_id'] = $this->data['Condicion']['Bar-empleador_id'];
                }

                if (!empty($this->data['Condicion']['Bar-trabajador_id'])) {
                    $conditions['Liquidacion.trabajador_id'] = $this->data['Condicion']['Bar-trabajador_id'];
                }

                if (!empty($this->data['Condicion']['Bar-area_id'])) {
                    $conditions['Liquidacion.relacion_area_id'] = explode('**||**', $this->data['Condicion']['Bar-area_id']);
                }

                if (!empty($this->data['Condicion']['Bar-grupo_id'])) {
                    $conditions['(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
                }

                if (!empty($this->data['Condicion']['Bar-agrupamiento'])) {
                    $group_option = $this->data['Condicion']['Bar-agrupamiento'];
                } else {
                    $group_option = 'coeficiente';
                }

                $this->Liquidacion->Behaviors->detach('Permisos');
                $workers = $this->Liquidacion->find('all', array(
                        'conditions'    => $conditions,
                        'fields'        => array('COUNT(DISTINCT Liquidacion.trabajador_id) AS cantidad'),
                        'recursive'     => -1));

                if (empty($workers[0]['Liquidacion']['cantidad'])) {
                    $this->Session->setFlash('No se han encontrado liquidaciones con los criterios especificados.', 'error');
                } else {

                    if (!empty($this->data['Condicion']['Bar-concepto_id'])) {
                        $conditions['LiquidacionesDetalle.concepto_id'] = explode('**||**', $this->data['Condicion']['Bar-concepto_id']);
                    }
                    
                    $this->Liquidacion->LiquidacionesDetalle->Behaviors->detach('Permisos');
                    $conditions['OR'] = array('LiquidacionesDetalle.concepto_imprimir' => 'Si', array('LiquidacionesDetalle.concepto_imprimir' => 'Solo con valor', 'ABS(LiquidacionesDetalle.valor) >' => 0));


					$data = array();
                    if ($group_option === 'trabajador') {

                        $r = $this->Liquidacion->LiquidacionesDetalle->find('all', array(
                                'conditions'    => $conditions,
                                'contain'       => 'Liquidacion',
                                'order'         => 'Liquidacion.relacion_id, LiquidacionesDetalle.concepto_orden',
                                'fields'        => array(
                                    'Liquidacion.relacion_legajo',
                                    'Liquidacion.trabajador_cuil',
                                    'Liquidacion.trabajador_nombre',
                                    'Liquidacion.trabajador_apellido',
                                    'LiquidacionesDetalle.concepto_nombre',
                                    'LiquidacionesDetalle.concepto_tipo',
                                    'LiquidacionesDetalle.coeficiente_valor',
                                    'LiquidacionesDetalle.coeficiente_nombre',
                                    'COUNT(LiquidacionesDetalle.concepto_nombre) AS cantidad',
                                    'SUM(LiquidacionesDetalle.valor_cantidad) AS suma_cantidad',
                                    'SUM(LiquidacionesDetalle.valor) AS valor'),
                                'group'         => array(
                                    'Liquidacion.relacion_id',
                                    'Liquidacion.relacion_legajo',
                                    'Liquidacion.trabajador_cuil',
                                    'Liquidacion.trabajador_nombre',
                                    'Liquidacion.trabajador_apellido',
                                    'LiquidacionesDetalle.concepto_nombre',
                                    'LiquidacionesDetalle.concepto_tipo',
                                    'LiquidacionesDetalle.coeficiente_valor',
                                    'LiquidacionesDetalle.coeficiente_nombre')));
                        foreach ($r as $record) {
                            $data[$record['Liquidacion']['trabajador_cuil']][] = $record;
                        }
                    } else {
                        $r = $this->Liquidacion->LiquidacionesDetalle->find('all', array(
                                'conditions'    => $conditions,
                                'order'         => 'LiquidacionesDetalle.concepto_orden',
                                'fields'        => array('LiquidacionesDetalle.concepto_nombre',
                                                        'LiquidacionesDetalle.concepto_tipo',
                                                        'LiquidacionesDetalle.coeficiente_valor',
                                                        'LiquidacionesDetalle.coeficiente_nombre',
                                                        'COUNT(LiquidacionesDetalle.concepto_nombre) AS cantidad',
                                                        'SUM(LiquidacionesDetalle.valor_cantidad) AS suma_cantidad',
                                                        'SUM(LiquidacionesDetalle.valor) AS valor'),
                                'group'         => array('LiquidacionesDetalle.concepto_nombre',
                                                        'LiquidacionesDetalle.concepto_tipo',
                                                        'LiquidacionesDetalle.coeficiente_valor',
                                                        'LiquidacionesDetalle.coeficiente_nombre')));
                        foreach ($r as $record) {
                            $data[$record['LiquidacionesDetalle']['coeficiente_nombre']][] = $record;
                        }
                    }

					if (empty($data)) {
						$this->Session->setFlash('No se han encontrado datos segun los criterios especificados.', 'error');
					}

                    $this->set('data', $data);
                    $this->set('totalWorkers', $workers[0]['Liquidacion']['cantidad']);
                    $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
                    $this->set('conditions', $this->data['Condicion']);
                    $this->set('group_option', $group_option);
                    $this->History->skip();
                }
            }
        }
        $this->set('options', array('coeficiente' => 'Coeficiente', 'trabajador' => 'Trabajador'));
        $this->set('types', $this->Liquidacion->opciones['tipo']);
        $this->set('states', $this->Liquidacion->opciones['estado']);
    }


	function libro_sueldos() {
		if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {
			if (empty($this->data['Condicion']['Bar-empleador_id'])
				&& empty($this->data['Condicion']['Bar-grupo_id'])) {
				$this->Session->setFlash('Debe seleccionar por lo menos un Empleador y/o un Grupo.', 'error');
			} elseif (empty($this->data['Condicion']['Bar-periodo_largo']) || $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo') === false) {
				$this->Session->setFlash('Debe especificar un periodo valido.', 'error');
			} else {
				$periodo = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
                $conditions = array('Liquidacion.estado'        => 'Confirmada',
                                    'Liquidacion.tipo'          => $this->data['Condicion']['Bar-tipo'],
                                    'Liquidacion.periodo'       => $periodo['periodo'],
                                    'Liquidacion.ano'           => $periodo['ano'],
                                    'Liquidacion.mes'           => $periodo['mes']);
                
                if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                    $conditions['Liquidacion.empleador_id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
                }

                if (!empty($this->data['Condicion']['Bar-area_id'])) {
                    $conditions['Liquidacion.relacion_area_id'] = explode('**||**', $this->data['Condicion']['Bar-area_id']);
                }
                
                if (!empty($this->data['Condicion']['Bar-grupo_id'])) {
                    $conditions['(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
                }

                $this->Liquidacion->LiquidacionesDetalle->Behaviors->detach('Permisos');
                foreach ($this->Liquidacion->LiquidacionesDetalle->find('all',
                        array(  'contain'       => array('Liquidacion'),
                                'conditions'    => $conditions,
                                'order'         => array(
                                    'Liquidacion.empleador_nombre',
                                    'Liquidacion.periodo',
                                    'LiquidacionesDetalle.concepto_tipo'))) as $k => $v) {

                    if (empty($liquidaciones[$v['Liquidacion']['id']]['Liquidacion'])) {
                        $liquidaciones[$v['Liquidacion']['id']]['Liquidacion'] = $v['Liquidacion'];
                    }
                    $liquidaciones[$v['Liquidacion']['id']]['LiquidacionesDetalle'][] = $v['LiquidacionesDetalle'];
                }

				if (empty($liquidaciones)) {
					$this->Session->setFlash('No se han encontrado liquidaciones confirmadas para el periodo seleccionado segun los criterios especificados.', 'error');
				} else {
                    if (!empty($this->data['Condicion']['Bar-grupo_id'])) {
						$this->set('groupParams', User::getGroupParams($this->data['Condicion']['Bar-grupo_id']));
                    }
                    if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                        $this->Liquidacion->Relacion->Empleador->contain(array('Actividad'));
                        $this->set('employer', $this->Liquidacion->Relacion->Empleador->findById($this->data['Condicion']['Bar-empleador_id']));
                    }
                    $this->set('startPage', $this->data['Condicion']['Bar-start_page']);
                    $this->set('periodo', $periodo['periodoCompleto']);
					$this->set('data', $liquidaciones);
					$this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
				}
			}
		}
		$this->set('types', $this->Liquidacion->opciones['tipo']);
	}

/**
 * PreLiquidar.
 * Me permite hacer una preliquidacion.
 */
	function preliquidar() {

		$periodo = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
		if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {
			
			if ($periodo !== false) {
				$message = null;
				if ($this->data['Condicion']['Liquidacion-tipo'] === 'normal' &&
						!in_array($periodo['periodo'], array('1Q', '2Q', 'M'))) {
					$message = __('Normal liquidation period should be of the form "YYYYMM[1Q|2Q|M]"', true);
				} elseif ($this->data['Condicion']['Liquidacion-tipo'] === 'holliday' &&
						!in_array($periodo['periodo'], array('1Q', '2Q', 'M'))) {
					$message = __('Holliday liquidation period should be of the form "YYYYMM[1Q|2Q|M]"', true);
				} elseif ($this->data['Condicion']['Liquidacion-tipo'] === 'sac' &&
						!in_array($periodo['periodo'], array('1S', '2S'))) {
					$message = __('Sac liquidation period should be of the form "YYYY[12]S"', true);
				}
			} elseif ($this->data['Condicion']['Liquidacion-tipo'] !== 'final') {
				$message = __('Invalid Period', true);
			}

			if (empty($this->data['Condicion']['Relacion-empleador_id'])
				&& empty($this->data['Condicion']['Relacion-trabajador_id'])
				&& empty($this->data['Condicion']['Relacion-area_id'])) {

				$message = 'Debe seleccionar un Empleador, un Trabajador o un Area de la Relacion Laboral.';
			}

			if (!empty($message)) {
				$this->Session->setFlash($message, 'error');
				$this->redirect(array('action' => 'preliquidar'));
			}


			/** Search for the relations */
			$condiciones = null;
			$condiciones = $this->Paginador->generarCondicion();
			unset($condiciones['Liquidacion.tipo']);
			unset($condiciones['Liquidacion.periodo_largo']);
			unset($condiciones['Liquidacion.estado']);
            unset($condiciones['Liquidacion.ano']);
            unset($condiciones['Liquidacion.mes']);
            unset($condiciones['Liquidacion.periodo']);
            if ($this->data['Condicion']['Liquidacion-tipo'] !== 'final') {
                $condiciones['Relacion.ingreso <='] = $periodo['hasta'];
            }
            
            $condiciones['Relacion.estado'] = 'Activa';
            if ($this->data['Condicion']['Liquidacion-tipo'] !== 'especial') {
                /**
                * De las liquidaciones que he seleccionado para pre-liquidar, verifico que no sean
                * liquidaciones ya confirmadas para el mismo periodo del mismo tipo.
                */
                $condicionesLiquidacion['Liquidacion.mes'] = $periodo['mes'];
                $condicionesLiquidacion['Liquidacion.ano'] = $periodo['ano'];
                $condicionesLiquidacion['Liquidacion.periodo'] = $periodo['periodo'];
                $condicionesLiquidacion['Liquidacion.tipo'] = $this->data['Condicion']['Liquidacion-tipo'];
                $condicionesLiquidacion['Liquidacion.estado'] = 'Confirmada';
                $liquidaciones = $this->Liquidacion->find('all', array(
                        'recursive'     => -1,
                        'fields'        => 'relacion_id',
                        'conditions'    => $condicionesLiquidacion));
                $confirmadas = Set::extract('/Liquidacion/relacion_id', $liquidaciones);
                if (!empty($confirmadas)) {
                    $condiciones['NOT'] = array('Relacion.id' => $confirmadas);
                }
            }

            $condiciones['(Relacion.group_id & ' . User::get('/Usuario/preferencias/grupo_default_id') . ') >'] = 0;
			$relaciones = $this->Liquidacion->Relacion->find('all',
					array(	'contain'		=> array(	'ConveniosCategoria',
                                                        'Modalidad',
														'Area',
                                                        'RelacionesHistorial' => array(
                                                                'limit'     => 1,
                                                            'conditions'    => array(
                                                                'RelacionesHistorial.estado' => 'Confirmado'),
                                                            'order'         => 'RelacionesHistorial.id DESC'),
														'Trabajador.ObrasSocial',
														'Empleador'),
							'conditions'	=> $condiciones));

			if (empty($relaciones)) {
				$this->Session->setFlash('No se encontraron relaciones para liquidar. Verifique que no se haya liquidado y confirmado previamente o los criterios de busqueda no son correctos.', 'error');
				$this->redirect(array('action' => 'preliquidar'));
			}


			/** Delete user's unconfirmed receipts */
            $this->Liquidacion->setSecurityAccess('readOwnerOnly');
			if (!$this->Liquidacion->deleteAll(array(
                'Liquidacion.user_id'   => User::get('/Usuario/id'),
                'Liquidacion.estado'    => 'Sin Confirmar'), true, false, true)) {
				$this->Session->setFlash(__('Can\'t delete previous liquidations. Call Administrator', true), 'error');
				$this->redirect(array('action' => 'preliquidar'));
			}


			/**
			 * Busco las informaciones de los convenios que pueden necesitarse en las formulas.
			 * Lo hago de esta forma, ya que busco todo junto y no uno por uno en cada relacion por una cuestion de performance,
			 * ya que seguramente las relaciones liquidadas tengas los mismos convenios.
			 */
			$informaciones = $this->Liquidacion->Relacion->ConveniosCategoria->Convenio->getInformacion(Set::extract('/ConveniosCategoria/convenio_id', $relaciones));

			/** Find all vars */
			$variables = Set::combine(ClassRegistry::init('Variable')->find('all', array(
					'recursive' => -1,
	 				'order' => false)), '{n}.Variable.nombre', '{n}.Variable');
			$variables['#tipo_liquidacion']['valor'] = $this->data['Condicion']['Liquidacion-tipo'];


			/** Make the liquidations if not done. */
			$ids = null;
			$opciones['variables'] = $variables;
			$opciones['informaciones'] = $informaciones;
			$opciones['empleadores'] = Set::extract('/Empleador/id', $relaciones);

			$groupParams = User::getGroupParams();
			if (!empty($groupParams['liquidar_con_liquidaciones_no_facturadas'])) {
 				$opciones['permitir_liquidar_con_liquidaciones_no_facturadas'] = $groupParams['liquidar_con_liquidaciones_no_facturadas'];
			} else {
				$opciones['permitir_liquidar_con_liquidaciones_no_facturadas'] = 'Si';
			}
			

			foreach ($relaciones as $k => $relacion) {

				$relacion['Relacion']['error'] = false;
                if ($this->data['Condicion']['Liquidacion-tipo'] === 'final') {
                    if (empty($relacion['RelacionesHistorial'][0]['fin'])
                    || $relacion['RelacionesHistorial'][0]['liquidacion_final'] != 'Si'
                    || (!empty($relacion['RelacionesHistorial'][0]['fin'])
                        && $relacion['RelacionesHistorial'][0]['fin'] == '0000-00-00')) {

						$relacion['Relacion']['error'] = array(
							'tipo'                  => 'Liquidaciones Final sin Historial',
							'gravedad'              => 'Alta',
							'concepto'              => '',
							'variable'              => '',
							'formula'               => '',
							'descripcion'           => 'No puede realizar una liquidacion final si no ha cargado la fecha fin en el historial de la Relacion.',
							'recomendacion'         => 'Agregue el historial a la relacion.',
							'descripcion_adicional' => '');

                    } else {
                        /** For finished relations, only allow last period receipt */
                        $periodo['hasta'] = $relacion['RelacionesHistorial'][0]['fin'];
                    }
                }


                $conveniosCategoriasHistoricoCondition['ConveniosCategoriasHistorico.convenios_categoria_id'] = $relacion['ConveniosCategoria']['id'];

                if ($this->data['Condicion']['Liquidacion-tipo'] !== 'final') {
                    $conveniosCategoriasHistoricoCondition['ConveniosCategoriasHistorico.desde <='] = $periodo['desde'];
                    $conveniosCategoriasHistoricoCondition['OR'] = array(
                        'ConveniosCategoriasHistorico.hasta >=' => $periodo['hasta'],
                        'ConveniosCategoriasHistorico.hasta'    => '0000-00-00');
                } else {
                    $conveniosCategoriasHistoricoCondition['ConveniosCategoriasHistorico.desde <='] = $relacion['RelacionesHistorial'][0]['fin'];
                    $conveniosCategoriasHistoricoCondition['OR'] = array(
                        'ConveniosCategoriasHistorico.hasta >=' => $relacion['RelacionesHistorial'][0]['fin'],
                        'ConveniosCategoriasHistorico.hasta'    => '0000-00-00');
                }

				$historico = $this->Liquidacion->Relacion->ConveniosCategoria->ConveniosCategoriasHistorico->find('first',
					array(
						'recursive'	 	=> -1,
	  					'checkSecurity'	=> false,
                        'order'         => 'ConveniosCategoriasHistorico.id',
						'conditions' 	=> $conveniosCategoriasHistoricoCondition));

				if (!empty($historico)) {
					$relacion['ConveniosCategoria']['costo'] = $historico['ConveniosCategoriasHistorico']['costo'];
				} else {
					$relacion['ConveniosCategoria']['costo'] = 0;
				}
				$this->Liquidacion->getReceipt($relacion, $periodo, $variables['#tipo_liquidacion']['valor'], $opciones);
			}
			$condiciones = array('Liquidacion.estado' => 'Sin Confirmar');
			$this->data['Condicion']['Liquidacion-estado'] = 'Sin Confirmar';			
		} else {
			$condiciones = array();
			if ($periodo !== false) {
				$condiciones['Liquidacion.mes'] = $periodo['mes'];
				$condiciones['Liquidacion.ano'] = $periodo['ano'];
				$condiciones['Liquidacion.periodo'] = $periodo['periodo'];
			}
		}
        
		/** Take care of filtering saved or unconfirmed receipt */
        if (empty($condiciones)) {
            $condiciones = $this->Paginador->generarCondicion();
        }
		if (empty($condiciones['Liquidacion.estado'])) {
            if (empty($this->data['Condicion']['Liquidacion-estado'])) {
                $condiciones = array_merge($condiciones, array('Liquidacion.estado' => array('Guardada', 'Sin Confirmar')));
                $this->data['Condicion']['Liquidacion-estado'] = array('Guardada', 'Sin Confirmar');
            } else {
                $condiciones = array_merge($condiciones, array('Liquidacion.estado' => $this->data['Condicion']['Liquidacion-estado']));
            }
		}

        $this->Liquidacion->setSecurityAccess('readOwnerOnly');
        $this->paginate = array_merge($this->paginate, array(
            'limit'     => 15,
            'contain'   => array(
                'Relacion.Trabajador',
                'Relacion.Empleador',
                'LiquidacionesError')));

        $this->Paginador->setCondition($condiciones);
        $this->set('registros', $this->Paginador->paginar());
		$this->set('states', array('Guardada' => 'Guardada', 'Sin Confirmar' => 'Sin Confirmar'));
	}


/**
 * Agrega datos que seran guardados en la tabla liquidaciones_auxiliares.
 *
 * @param array $auxiliar Los datos a guardar.
 * @return void.
 * @access private.
 */
	function guardar($id = null) {

		if (empty($id)) {
			if (!empty($this->params['data']['seleccionMultiple'])) {
				$id = $this->Util->extraerIds($this->params['data']['seleccionMultiple']);
			}
		}
        
        foreach ($this->Liquidacion->find('all', array(
            'recursive'     => -1,
            'conditions'    => array('Liquidacion.id' => $id))) as $receipt) {

            /** Delete user's saved ones for same period and type */
            $this->Liquidacion->setSecurityAccess('readOwnerOnly');
            if (!$this->Liquidacion->deleteAll(array(
                'Liquidacion.user_id'       => User::get('/Usuario/id'),
                'Liquidacion.tipo'          => $receipt['Liquidacion']['tipo'],
                'Liquidacion.ano'           => $receipt['Liquidacion']['ano'],
                'Liquidacion.mes'           => $receipt['Liquidacion']['mes'],
                'Liquidacion.periodo'       => $receipt['Liquidacion']['periodo'],
                'Liquidacion.relacion_id'   => $receipt['Liquidacion']['relacion_id'],
                'Liquidacion.estado'        => 'Guardada'), true, false, true)) {
            }
        }



        $this->Liquidacion->unbindModel(array('belongsTo' => array('Trabajador', 'Empleador', 'Relacion', 'Factura')));
        if ($this->Liquidacion->updateAll(
				array(
					'Liquidacion.estado'  		=> "'Guardada'",
					'Liquidacion.permissions'  	=> '504'),
				array('Liquidacion.id'      => $id))) {
			$this->Session->setFlash(sprintf('Se guardaron correctamente %s liquidacion/es', count($id)), 'ok');
		} else {
			$this->Session->setFlash('No fue posible guardar las liquidaciones seleccionadas', 'error');
		}
		$this->redirect(array('action' => 'preliquidar'));
	}


/**
 * recibo_html.
 * Muestra via desglose el recibo (detalle) de la preliquidacion.
 */
	function recibo_html($id = null) {
        $this->Liquidacion->setSecurityAccess('readOwnerOnly');
		$this->Liquidacion->contain('LiquidacionesDetalle');
		$this->data = $this->Liquidacion->read(null, $id);
	}

/**
 */
	function imprimir($id = null) {

		if (empty($id) && !empty($this->params['named']['id'])) {
			$id = $this->params['named']['id'];
			if (!empty($this->params['named']['tipo']) && $this->params['named']['tipo'] == 'preimpreso') {
				$render = 'recibo_excel_preimpreso';
			} else {
				$render = 'recibo_excel';
			}
        } elseif ((!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'preimpreso')
        || (!empty($this->params['named']['tipo']) && $this->params['named']['tipo'] === 'preimpreso')) {
            $render = 'recibo_excel_preimpreso';
        } else {
            $render = 'recibo_excel';
        }

		if (empty($id)) {
			if (!empty($this->params['data']['seleccionMultiple'])) {
				$id = $this->Util->extraerIds($this->params['data']['seleccionMultiple']);
			}
		}
        
        if (strstr($this->referer(), 'preliquidar')) {
            $this->Liquidacion->setSecurityAccess('readOwnerOnly');
        }
		$this->data = null;
		$this->Liquidacion->contain('LiquidacionesDetalle');
		$this->Liquidacion->Empleador->Suss->contain('Banco');
		foreach ($this->Liquidacion->find('all', array('order' => array('IF(Liquidacion.trabajador_cbu="",0,1)' => 'DESC', 'Liquidacion.trabajador_apellido', 'Liquidacion.trabajador_nombre'), 'conditions' => array('Liquidacion.id' => $id))) as $receipt) {

            $ano = $receipt['Liquidacion']['ano'];
            $mes = $receipt['Liquidacion']['mes'];
            if ($receipt['Liquidacion']['mes'] == 1) {
                $ano--;
                $mes = 12;
            } else {
                $mes--;
            }
            $mes = str_pad($mes, 2, '0', STR_PAD_LEFT);
            
			$suss = $this->Liquidacion->Empleador->Suss->find('first',
				array('conditions' => array(
					'Suss.empleador_id' => $receipt['Liquidacion']['empleador_id'],
					'Suss.periodo'		=> $ano . $mes))
			);
			if (!empty($suss)) {
				$this->data[] = array_merge($receipt, $suss);
			} else {
				$this->data[] = $receipt;
			}
		}

		$this->set('groupParams', User::getGroupParams());
        $this->render($render);
	}

	
/**
 * recibo_html_debug.
 * Muestra via desglose el recibo (detalle) de la preliquidacion con informacion de debug.
 */
	function recibo_html_debug($id) {
        $this->Liquidacion->setSecurityAccess('readOwnerOnly');
		$this->Liquidacion->contain(array('LiquidacionesDetalle'));
		$this->data = $this->Liquidacion->read(null, $id);
	}


/**
 * errores.
 * Muestra via desglose los errores de la preliquidacion.
 */
	function errores($id) {
        $this->Liquidacion->setSecurityAccess('readOwnerOnly');
		$this->Liquidacion->contain(array('LiquidacionesError'));
		$this->data = $this->Liquidacion->read(null, $id);
	}
	
	
	
	
	function agregar_observacion($id) {
		/**
		* Agrego una url a la History para que vuelva bien a donde debe, ya que no uso un edit  comun.
		*/
		$this->Liquidacion->recursive = -1;
		$this->data = $this->Liquidacion->read(null, $id);
	}


	function riesgo_indemnizatorio() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            $conditions['(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            
            if (!empty($this->data['Condicion']['Bar-relacion_id'])) {
                $conditions['Liquidacion.relacion_id'] = $this->data['Condicion']['Bar-relacion_id'];
            }
            $conditions['Liquidacion.tipo'] = 'Normal';
            $conditions['Liquidacion.estado'] = 'Confirmada';

            $this->Liquidacion->Behaviors->detach('Permisos');

            $this->set('data', $this->Liquidacion->find('all',
                    array(  'recursive'     => -1,
                            'limit'         => 12,
                            'order'         => array(
                                'Liquidacion.ano DESC',
                                'Liquidacion.mes DESC'),
                            'fields'        => array(
                                'Liquidacion.relacion_id',
                                'Liquidacion.ano',
                                'Liquidacion.mes',
                                'SUM(Liquidacion.remunerativo) AS total'),
                            'group'         => array(
                                'Liquidacion.relacion_id',
                                'Liquidacion.ano',
                                'Liquidacion.mes'),
                            'conditions'    => $conditions)));

			$this->Liquidacion->Relacion->contain(array(
				'Empleador',
				'Trabajador',
				'RelacionesHistorial' => array(
					'limit'         => 1,
					'conditions'    => array('RelacionesHistorial.estado' => 'Confirmado'),
					'order'         => 'RelacionesHistorial.id DESC')));
			$this->set('relacion',
				 $this->Liquidacion->Relacion->findById($this->data['Condicion']['Bar-relacion_id']));
			$this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
	}



/**
 * Genera el archivo para importar en SIAP y generar el 931.
 * TODO: Deberia mover esto al controller siap.
 *
 * @return void.
 * @access public.
*/
    function generar_archivo_siap() {

        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar' && !empty($this->data['Condicion']['Bar-version'])) {
            if (empty($this->data['Condicion']['Bar-periodo_largo']) || !preg_match('/^(20\d\d)(0[1-9]|1[012])$/', $this->data['Condicion']['Bar-periodo_largo'])) {
                $this->Session->setFlash('Debe especificar un periodo valido de la forma AAAAMM.', 'error');
            } else {
                $periodo = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
                
                $conditions = array('Liquidacion.estado'        => 'Confirmada',
                                    'Liquidacion.ano'           => $periodo['ano'],
                                    'OR'						=> array(
										'Liquidacion.mes'	=> $periodo['mes'],
										array(	'Liquidacion.mes'	=> 0,
												'Liquidacion.tipo'	=> 'Final')));

                if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                    $conditions['Liquidacion.empleador_id'] = $this->data['Condicion']['Bar-empleador_id'];
                    $contain = array('Empleador' => array('EmployersType'));
                } else {
                    $groupParams = User::getGroupParams($this->data['Condicion']['Bar-grupo_id']);
                    $contain = array();
                }
                $conditions['(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;

                $r = ClassRegistry::init('AusenciasSeguimiento')->find('all', array(
                    'contain'       => array('Liquidacion', 'Ausencia' => array('order' => 'Ausencia.desde')),
                    'conditions'    => array('(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >' => 0,
                                         'Liquidacion.estado' => 'Confirmada',
                                         'AusenciasSeguimiento.estado' => 'Liquidado',
                                         'Liquidacion.ano' => $periodo['ano'],
                                         'Liquidacion.mes' => $periodo['mes'])));

                $ausencias = array();
                foreach ($r as $ausencia) {
                    if (!empty($ausencia['Liquidacion']['trabajador_cuil'])) {
                        $ausencias[$ausencia['Liquidacion']['trabajador_cuil']][] = $ausencia;
                    }
                }


                App::import('Vendor', 'dates', 'pragmatia');
                $remuneraciones = null;
                $compone = null;
                $vantidadSueldo = $cantidadHorasExtras = null;
                $step = 0;
                do {
                    $r = $this->Liquidacion->find('all',
                            array(  'checkSecurity' => false,
                                    'limit' => $step . ',' . 100,
                                    'contain'       => array_merge($contain, array( 
                                            'LiquidacionesDetalle' => array('conditions' => array('OR' => array('LiquidacionesDetalle.concepto_imprimir' => 'Si', array('LiquidacionesDetalle.concepto_imprimir' => 'Solo con valor', 'ABS(LiquidacionesDetalle.valor) >' => 0)))),
                                            'Relacion'      => array(
                                                'RelacionesHistorial' => array(
                                                    'limit'         => 1,
                                                    'conditions'    => array(
                                                        'RelacionesHistorial.estado' => 'Confirmado'),
                                                    'order'         => 'RelacionesHistorial.id DESC'),
                                                'Actividad',
                                                'Situacion',
                                                'ConveniosCategoria',
                                                'Modalidad'),
                                            'Trabajador'    => array('ObrasSocial', 'Condicion', 'Siniestrado', 'Localidad'))),
                                    'conditions'    => $conditions));
                    $step+=100;
                    if (empty($r)) {
                        break;
                    }

                    $opcionesConcepto = $this->Liquidacion->LiquidacionesDetalle->Concepto->opciones;
                            
                    $ausenciasMotivo = $this->Liquidacion->Relacion->Ausencia->AusenciasMotivo->find('all',
						 array('conditions' => array('NOT' => array('AusenciasMotivo.situacion_id' => null))));
                    $ausenciasMotivo = Set::combine($ausenciasMotivo, '{n}.AusenciasMotivo.id', '{n}.Situacion');


                    $data = ClassRegistry::init('Siap')->findById($this->data['Condicion']['Bar-version']);
                    foreach ($data['SiapsDetalle'] as $k => $v) {
                        $detalles[$v['elemento']] = $v;
                    }

                    foreach ($r as $liquidacion) {

                        /** Inicialize arrays */
                        if (!isset($remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']])) {

                            $cantidadHorasExtras[$liquidacion['Liquidacion']['trabajador_cuil']] = 0;
							$cantidadSueldo[$liquidacion['Liquidacion']['trabajador_cuil']] = 0;

                            foreach ($opcionesConcepto['remuneracion'] as $k => $v) {
                                $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']][$v] = 0;
                            }

                            foreach ($opcionesConcepto['compone'] as $k => $v) {
                                $compone[$liquidacion['Liquidacion']['trabajador_cuil']][$k] = 0;
                            }
                        }

                        foreach ($liquidacion['LiquidacionesDetalle'] as $detalle) {
                            if (!empty($detalle['concepto_compone'])) {
                                $compone[$liquidacion['Liquidacion']['trabajador_cuil']][$detalle['concepto_compone']] += $detalle['valor'];

								if ($detalle['concepto_compone'] === 'Importe Horas Extras') {
									$cantidadHorasExtras[$liquidacion['Liquidacion']['trabajador_cuil']] += $detalle['valor_cantidad'];
								}

								if ($detalle['concepto_compone'] === 'Sueldo') {
									$cantidadSueldo[$liquidacion['Liquidacion']['trabajador_cuil']] += $detalle['valor_cantidad'];
								}
                            }
                            if (!empty($detalle['concepto_remuneracion'])) {
                                foreach ($opcionesConcepto['remuneracion'] as $k => $v) {
                                    if ($detalle['concepto_remuneracion'] & (int)$k) {
                                        $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']][$v] += $detalle['valor'];
                                    }
                                }
                            }
                        }

                        /** Must sumarize. Can't do in by query because of contain. */
                        if (!isset($liquidaciones[$liquidacion['Liquidacion']['trabajador_cuil']])) {
                            $liquidaciones[$liquidacion['Liquidacion']['trabajador_cuil']] = $liquidacion;
                        } else {
                            foreach (array('remunerativo', 'no_remunerativo', 'deduccion', 'total_pesos', 'total_beneficios', 'total') as $total) {
                                $liquidaciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Liquidacion'][$total] += $liquidacion['Liquidacion'][$total];
                            }
                        }
                    }

                    $lineas = null;
                    foreach ($liquidaciones as $liquidacion) {

                        if ($liquidacion['Liquidacion']['no_remunerativo'] < 0) {
                            $liquidacion['Liquidacion']['no_remunerativo'] = '0';
                        }

                        $campos = $detalles;
                        $campos['c1']['valor'] = str_replace('-', '', $liquidacion['Trabajador']['cuil']);
                        $campos['c2']['valor'] = $liquidacion['Trabajador']['apellido'] . ' ' . $liquidacion['Trabajador']['nombre'];

                        if (!empty($liquidacion['Relacion']['situacion_id'])) {
                            $campos['c5']['valor'] = $liquidacion['Relacion']['Situacion']['codigo'];
                        }
                        if (!empty($liquidacion['Trabajador']['condicion_id'])) {
                            $campos['c6']['valor'] = $liquidacion['Trabajador']['Condicion']['codigo'];
                        }
                        $campos['c7']['valor'] = $liquidacion['Relacion']['Actividad']['codigo'];
                        $campos['c8']['valor'] = $liquidacion['Trabajador']['Localidad']['codigo_zona'];
                        
                        if (!empty($liquidacion['Relacion']['modalidad_id'])) {
                            $campos['c10']['valor'] = $liquidacion['Relacion']['Modalidad']['codigo'];
                        }
                        if (!empty($liquidacion['Trabajador']['obra_social_id'])) {
                            $campos['c11']['valor'] = $liquidacion['Trabajador']['ObrasSocial']['codigo'];
                        }
                        $campos['c12']['valor'] = $liquidacion['Trabajador']['adherentes_os'];

                        $campos['c13']['valor'] = array_sum($compone[$liquidacion['Liquidacion']['trabajador_cuil']]) + $liquidacion['Liquidacion']['no_remunerativo'];

                        $campos['c14']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 1'];
                        $campos['c20']['valor'] = $liquidacion['Trabajador']['Localidad']['nombre'];
                        $campos['c21']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 2'];
                        $campos['c22']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 3'];
                        
                        $campos['c23']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 4'];
                        
                        if (!empty($liquidacion['Trabajador']['siniestrado_id'])) {
                            $campos['c24']['valor'] = $liquidacion['Trabajador']['Siniestrado']['codigo'];
                        }
                        
                        if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                            if ($liquidacion['Empleador']['corresponde_reduccion'] === 'Si') {
                                $campos['c25']['valor'] = 'S';
                            } else {
                                $campos['c25']['valor'] = ' ';
                            }
                        } else {
                            $campos['c25']['valor'] = $groupParams['siap_corresponde_reduccion'];
                        }
                        
                        if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                            $campos['c27']['valor'] = $liquidacion['Empleador']['EmployersType']['code'];
                        } else {
                            $campos['c27']['valor'] = $groupParams['siap_tipo_empleador'];
                        }
                        
                        if ($liquidacion['Trabajador']['jubilacion'] === 'Reparto') {
                            $campos['c29']['valor'] = '1';
                        } else {
                            $campos['c29']['valor'] = '0';
                        }
                        
                        
                        $camposTmp = null;
                        $camposTmp[0]['situacion'] = '1';
                        $camposTmp[0]['dia'] = '01';
                        $camposTmp[1]['situacion'] = '0';
                        $camposTmp[1]['dia'] = '00';
                        $camposTmp[2]['situacion'] = '0';
                        $camposTmp[2]['dia'] = '00';
                        $previous = null;
                        $diasRevista = 0;
                        $c = 0;
                        if (!empty($ausencias[$liquidacion['Liquidacion']['trabajador_cuil']])) {
                            $fin = 0;
                            foreach ($ausencias[$liquidacion['Liquidacion']['trabajador_cuil']] as $k => $ausencia) {
                                if ($ausenciasMotivo[$ausencia['Ausencia']['ausencia_motivo_id']]['codigo'] != '1') {
                                    if ($ausencia['Ausencia']['desde'] <= $periodo['desde']) {
                                        unset($camposTmp[0]);
                                    }

                                    if ($previous !== $ausenciasMotivo[$ausencia['Ausencia']['ausencia_motivo_id']]['codigo']) {
                                        $previous = $ausenciasMotivo[$ausencia['Ausencia']['ausencia_motivo_id']]['codigo'];
                                        $camposTmp[$c]['situacion'] = $ausenciasMotivo[$ausencia['Ausencia']['ausencia_motivo_id']]['codigo'];

                                        if ($ausencia['Ausencia']['desde'] > $periodo['desde']) {
                                            $camposTmp[$c]['dia'] = array_pop(explode('-', $ausencia['Ausencia']['desde']));
                                        } else  {
                                            $camposTmp[$c]['dia'] = array_pop(explode('-', $periodo['desde']));
                                        }

                                        if ($ausencia['AusenciasSeguimiento']['estado'] == 'Liquidado') {
                                            $fin += $ausencia['AusenciasSeguimiento']['dias'];
                                        }
                                        $diasRevista += $fin;

                                        if (!empty($ausencias[$liquidacion['Liquidacion']['trabajador_cuil']][$k+1]['desde'])) {
                                            $tmpNuevoInicio = array_pop(explode('-', $ausencias[$liquidacion['Liquidacion']['trabajador_cuil']][$k+1]['desde']));
                                            if ($tmpNuevoInicio == ($camposTmp[$c]['dia'] + $fin)) {
                                                continue;
                                            }
                                        }
                                        $c++;
                                        $camposTmp[$c]['situacion'] = '1';
                                        $camposTmp[$c]['dia'] = $camposTmp[($c-1)]['dia'] + $fin;
                                    }
                                }
                            }
                        }

                        ksort($camposTmp);
                        $campoNumero = 30;
                        foreach ($camposTmp as $k => $tmp) {
                            if ($k > 2) {
                                break;
                            }
                            $campoNumero += ($k * 2);
                            $campos['c' . $campoNumero]['valor'] = $tmp['situacion'];
                            $campos['c' . ($campoNumero + 1)]['valor'] = $tmp['dia'];
                            if ($tmp['situacion'] != '0') {
                                $campos['c5']['valor'] = $tmp['situacion'];
                            }
                        }
                        
                        $campos['c36']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Sueldo'];
                        $campos['c37']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['SAC'];
                        $campos['c38']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Importe Horas Extras'];
                        $campos['c39']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Plus Zona Desfavorable'];
                        $campos['c40']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Vacaciones'];

                        if ($liquidacion['Relacion']['ingreso'] > $periodo['desde']) {
                            $from = $liquidacion['Relacion']['ingreso'];
                        } else {
                            $from = $periodo['desde'];
                        }

                        if ($liquidacion['Relacion']['estado'] == 'Historica'
							&& !empty($liquidacion['Relacion']['RelacionesHistorial'][0]['fin'])
							&& $liquidacion['Relacion']['RelacionesHistorial'][0]['fin'] < $periodo['hasta']) {

                            $to = $liquidacion['Relacion']['RelacionesHistorial'][0]['fin'];
                        } else {
                            $to = $periodo['hasta'];
                        }


 						if ($liquidacion['Relacion']['ConveniosCategoria']['jornada'] == 'Por Hora') {
							$campos['c41']['valor'] = 0;
						} else {
							$diff = Dates::dateDiff($from, $to);
							$campos['c41']['valor'] = $diff['dias'] - $diasRevista;
						}


                        $campos['c42']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 5'];
                        if ($liquidacion['Relacion']['ConveniosCategoria']['nombre'] === 'Fuera de convenio') {
                            $campos['c43']['valor'] = '0';
                        } else {
                            $campos['c43']['valor'] = '1';
                        }
                        $campos['c44']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 6'];
                        $campos['c46']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Adicionales'];
                        $campos['c47']['valor'] = $compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Premios'];
                        $campos['c48']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 8'];
                        $campos['c49']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 7'];

                        if ($compone[$liquidacion['Liquidacion']['trabajador_cuil']]['Importe Horas Extras'] > 0) {
                            if (round($cantidadHorasExtras[$liquidacion['Liquidacion']['trabajador_cuil']]) > 1) {
                                $campos['c50']['valor'] = round($cantidadHorasExtras[$liquidacion['Liquidacion']['trabajador_cuil']]);
                            } else {
                                $campos['c50']['valor'] = 1;
                            }
                        }
                        $campos['c51']['valor'] = $liquidacion['Liquidacion']['no_remunerativo'];
						$campos['c54']['valor'] = $remuneraciones[$liquidacion['Liquidacion']['trabajador_cuil']]['Remuneracion 9'];

						if ($liquidacion['Relacion']['tarea_diferencial'] == 'Si') {
							$campos['c55']['valor'] = '2';
						}


						if ($liquidacion['Relacion']['ConveniosCategoria']['jornada'] == 'Por Hora') {
							$campos['c56']['valor'] = round($cantidadSueldo[$liquidacion['Liquidacion']['trabajador_cuil']]);
						} else {
							$campos['c56']['valor'] = 0;

						}

                        $lineas[] = $this->__generarRegistro($campos);
                    }
                } while (!empty($r));
            }

            if (!empty($lineas)) {
                $this->set('archivo', array(
                    'contenido' => implode("\r\n", $lineas),
                    'nombre'    => 'SICOSS_' . $periodo['ano'] . '-' . $periodo['mes'] . '.txt'));
                $this->render('..' . DS . 'elements' . DS . 'txt', 'txt');
            } else {
                $this->Session->setFlash('No se han encontrado liquidaciones confirmadas para el periodo seleccioando segun los criterios especificados.', 'error');
            }
        }
    }

	
	
/**
 * Genera una linea del archivo para importar en SIAP.
 *
 * @param array La descripcion del campo, donde me indica como debe comportarse.
 * @return string Una linea formateada para ser importada.
 * @access private.
*/
    function __generarRegistro($campos) {
        $v = array();
        if (!empty($campos)) {
            foreach ($campos as $k => $campo) {
                if ($campo['tipo'] === 'decimal') {
                    if (!empty($campo['valor_maximo']) && $campo['valor_maximo'] > 0 && $campo['valor'] > $campo['valor_maximo']) {
                        $campo['valor'] = $campo['valor_maximo'];
                    }
                    $campo['valor'] = number_format($campo['valor'], 2, ',', '');
                } elseif ($campo['tipo'] === 'text') {
                    $campo['valor'] = $this->Util->replaceNonAsciiCharacters($campo['valor']);
                }

                if ($campo['direccion_relleno'] === 'Derecha') {
                    $t = str_pad($campo['valor'], $campo['longitud'], $campo['caracter_relleno'], STR_PAD_RIGHT);
                } elseif ($campo['direccion_relleno'] === 'Izquierda') {
                    $t = str_pad($campo['valor'], $campo['longitud'], $campo['caracter_relleno'], STR_PAD_LEFT);
                } else {
                    $t = $campo['valor'];
                }
                $v[] = substr($t, 0, $campo['longitud']);
            }
        }
        return implode('', $v);
    }


	function index($zipFileName = null) {
		if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
			$periodo = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
			if (!empty($periodo)) {
				$this->data['Condicion']['Liquidacion-ano'] = $periodo['ano'];
				$this->data['Condicion']['Liquidacion-mes'] = $periodo['mes'];
                if (!empty($periodo['periodo'])) {
				    $this->data['Condicion']['Liquidacion-periodo'] = $periodo['periodo'];
                }
			}
		}

		$prevFilter = $this->Session->read('filtros.' . $this->name . '.' . $this->action);
        if (!empty($this->data['Condicion']['Bar-facturado'])
            && count($this->data['Condicion']['Bar-facturado']) == 1) {
            if ($this->data['Condicion']['Bar-facturado'][0] == 'Si') {
                $this->Paginador->setCondition(array(
                    'Liquidacion.factura_id !=' => null));
                $this->Paginador->removeCondition(array('Liquidacion.factura_id'));
            } else {
                $this->Paginador->setCondition(array(
                    'Liquidacion.factura_id' => null));
                $this->Paginador->removeCondition(array('Liquidacion.factura_id !='));
            }
		} elseif (!array_key_exists('Liquidacion.factura_id', (array)$prevFilter['condiciones'])
			&& !array_key_exists('Liquidacion.factura_id !=', (array)$prevFilter['condiciones'])) {
            $this->data['Condicion']['Bar-facturado'] = array('Si', 'No');
            $this->Paginador->removeCondition(array('Liquidacion.factura_id !=', 'Liquidacion.factura_id'));
        }
        
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] == 'limpiar') {
            $this->Paginador->removeCondition(array('Liquidacion.factura_id !=', 'Liquidacion.factura_id'));
        }
        
        $this->Paginador->setCondition(array('Liquidacion.estado' => 'Confirmada'));
        $this->set('zipFileName', $zipFileName);
		parent::index();
	}


	function beforeRender() {
		if ($this->action === 'index') {
			$filters = $this->Session->read('filtros.' . $this->name . '.' . $this->action);
			if (!empty($filters['condiciones']['Liquidacion.ano']) && !empty($filters['condiciones']['Liquidacion.mes']) && !empty($filters['condiciones']['Liquidacion.periodo like'])) {
				$this->data['Condicion']['Liquidacion-periodo_completo'] = $filters['condiciones']['Liquidacion.ano'] . $filters['condiciones']['Liquidacion.mes'] . str_replace('%', '', $filters['condiciones']['Liquidacion.periodo like']);
			}
		}
	}
	

/**
 * pagos.
 * Muestra via desglose los Pagos generados por esta liquidacion.
 */
	function pagos($id) {
		$this->Liquidacion->contain(array("Pago"));
		$this->data = $this->Liquidacion->read(null, $id);
	}


/**
 * Permite confirmar liquidaciones.
 * Las liquidaciones estan en la tabla liquidaciones pero con estado "Sin Confirmar".
 *
 * Esto implica que:
 *		- Las horas liquidadas cambian a estado "Liquidada".
 *		- Las ausencias_seguimientos liquidadas cambian a estado "Liquidado".
 *		- Las liquidaciones cambian a estado "Liquidada".
 *		- Se generan los pagos pendientes.
 *		- Se agregan detalles de descuentos.
 *		- En caso de que el empleador tenga marcado como auto_facturar en "Si", se realiza la pre-factura y se confirma.
 *
 * @return void.
 * @access public.
 */
	function confirmar() {

		$ids = $this->Util->extraerIds($this->data['seleccionMultiple']);
		
		if (!empty($ids)) {

            $this->Liquidacion->setSecurityAccess('readOwnerOnly');

			/** First, count all but special types, can be just one per type per period */
            $r1 = $this->Liquidacion->find('all', array(
				'recursive'		=> -1,
                'conditions'    => array(
                    'Liquidacion.id'        => $ids,
                    'Liquidacion.estado'    => array('Sin Confirmar', 'Guardada'),
					'Liquidacion.tipo !='	=> 'Especial',
                    'Liquidacion.total >='  => 0),
				'group'			=> array('relacion_id', 'tipo', 'ano', 'mes', 'periodo')
			));

			/** Second, count only special types, can be multiple special receipts by period */
            $r2 = $this->Liquidacion->find('all', array(
				'recursive'		=> -1,
                'conditions'    => array(
                    'Liquidacion.id'        => $ids,
                    'Liquidacion.estado'    => array('Sin Confirmar', 'Guardada'),
					'Liquidacion.tipo'		=> 'Especial',
                    'Liquidacion.total >='  => 0)
			));

            if ((count($r1) + count($r2)) != count($ids)) {
                $this->Session->setFlash('Ha seleccionado liquidaciones para confirmar que no pueden ser confirmadas.', 'error');
                $this->History->goBack();
            }


			/**
			* En la tabla auxiliares tengo un array de los datos listos para guardar.
			* Puede haber campos que deben ser guardados y no tienen valor, estos debo ponerle valor actual,
			* por ejemplo, la fecha del dia que se confirma, y no la del dia que se pre-liquido.
			*/
            //$this->Liquidacion->LiquidacionesAuxiliar->setSecurityAccess('readOwnerOnly');
			$this->Liquidacion->LiquidacionesAuxiliar->Behaviors->detach('Permisos');
			$auxiliares = $this->Liquidacion->LiquidacionesAuxiliar->find('all',
				array(	'recursive' => -1,
						'conditions' => array(
							'LiquidacionesAuxiliar.liquidacion_id' => $ids
						)
				)
			);

			$c = 0;
            $db = ConnectionManager::getDataSource($this->Liquidacion->useDbConfig);
            $db->begin($this);
			$idsAuxiliares = null;
			foreach ($auxiliares as $v) {
				$model = $v['LiquidacionesAuxiliar']['model'];
				$idsAuxiliares[] = $v['LiquidacionesAuxiliar']['id'];
				$save = unserialize($v['LiquidacionesAuxiliar']['save']);
				
                foreach ($save as $campo => $valor) {
					preg_match('/^##MACRO:([a-z_]+)##(.*)$/', $valor, $matches);
					if (!empty($matches[1])) {
						switch($matches[1]) {
							case 'fecha_liquidacion':
								$save[$campo] = date('Y-m-d');
								break;
							case 'liquidacion_id':
								$save[$campo] = $v['LiquidacionesAuxiliar']['liquidacion_id'];
								break;
							case 'concepto_valor':
								$this->Liquidacion->LiquidacionesDetalle->recursive = -1;
                                $this->Liquidacion->LiquidacionesDetalle->setSecurityAccess('readOwnerOnly');
								$concepto = $this->Liquidacion->LiquidacionesDetalle->find('first',
									array('conditions' => array('LiquidacionesDetalle.liquidacion_id' => $v['LiquidacionesAuxiliar']['liquidacion_id'],
										  'LiquidacionesDetalle.concepto_id' => $save['concepto_id'])));
                                if (!empty($save['condition']) && !empty($matches[2])) {
                                    $save[$campo] = $concepto['LiquidacionesDetalle']['valor'] . $matches[2];
                                    unset($save['concepto_id']);
                                } else {
                                    $save[$campo] = $concepto['LiquidacionesDetalle']['valor'];
                                }
								break;
						}
					}
				}

                if (!empty($save['condition'])) {
                    if ($this->Formulador->resolver('=if(' . $save['condition'] . ', true, false)') == false) {
                        $c++;
                        continue;
                    }
                    unset($save['condition']);
                }

				$modelSave = ClassRegistry::init($model);
                /** Just the owner and group can just read. Nobody can edit or delete it */
                $save['permissions'] = '288';
				$save = array($model => $save);
				$modelSave->create(false);
				if ($modelSave->save($save, false)) {
					$c++;
                }
			}

            /** If everything is ok, change state and permission so only owner and group can just read */
			if ($c === count($auxiliares)) {

				if (empty($this->data['LiquidacionGrupo']['observacion'])) {
					$this->data['LiquidacionGrupo']['observacion'] = '';
				}

				if ($this->Liquidacion->LiquidacionesGrupo->save(array(
					'LiquidacionesGrupo' => array(
						'observacion' 	=> $this->data['LiquidacionGrupo']['observacion'],
						'fecha'			=> date('Y-m-d'))))) {

					if ($this->Liquidacion->updateAll(array(
						'liquidaciones_grupo_id'	=> $this->Liquidacion->LiquidacionesGrupo->id,
						'estado'        			=> "'Confirmada'",
						'permissions'   			=> "'288'",
						'modified'      			=> 'NOW()'),
							array('Liquidacion.id' => $ids))) {

						/** Deletes auxiliar table */
						if (!empty($idsAuxiliares)) {
							$this->Liquidacion->LiquidacionesAuxiliar->recursive = -1;;
							$this->Liquidacion->LiquidacionesAuxiliar->deleteAll(array('LiquidacionesAuxiliar.id' => $idsAuxiliares));
						}

						/** If employer is marked as auto_facturar = 'Si', must create and confirm invoices */
						list($groupId, ) = array_keys(User::getUserGroups('default'));
						$createdInvoicesIds = $this->Liquidacion->Factura->getInvoice(array('Liquidacion.id' => $ids), $groupId, 	true);
						if ($createdInvoicesIds === false) {
							$db->rollback($this);
							$this->Session->setFlash('Ocurrio un error al intentar generar las facturas.', 'error');
						} else {
							$db->commit($this);


							$suffix = '_' . $this->Liquidacion->LiquidacionesGrupo->id . '.xls';
							$f = array();
							$f[] = 'reporte_resumen' . $suffix;
							$this->reporte_resumen_confirmadas($ids, TMP . $f[0]);
							$f[] = 'liquidaciones_confirmadas' . $suffix;
							$this->reporte_liquidaciones_confirmadas($this->Liquidacion->LiquidacionesGrupo->id, TMP . $f[1], false);
							$f[] = 'libro_sueldos' . $suffix;
							$this->libro_sueldos_confirmadas($ids, TMP . $f[2]);

							foreach ($createdInvoicesIds as $invoiceId) {
								$tmpF = 'reporte_facturacion_' . $invoiceId . $suffix;
								$this->reporte_facturacion_confirmadas($invoiceId, TMP . $tmpF);
								$f[] = $tmpF;
							}


							$zip = new ZipArchive();
							$zipFileName = 'confirmacion_' . $this->Liquidacion->LiquidacionesGrupo->id . '.zip';
							$zip->open(WWW_ROOT . 'files' . DS . 'tmp' . DS . $zipFileName, ZIPARCHIVE::CREATE);
							foreach ($f as $file) {
								$zip->addFile(TMP . $file, $file);
							}
							$zip->close();

							$this->redirect('index/' . $zipFileName);
						}
					} else {
						$db->rollback($this);
						$this->Liquidacion->__buscarError();
						$this->Session->setFlash('Ocurrio un error al intentar confirmar las liquidaciones.', 'error');
					}
				} else {
					$db->rollback($this);
					$this->Session->setFlash('Ocurrio un error al intentar crear el grupo de liquidaciones.', 'error');
				}
			} else {
				$db->rollback($this);
				$this->Session->setFlash('Ocurrio un error al intentar confirmar las liquidaciones. No se puedieron actualizar los registros relacionados.', 'error');
			}
		}
		$this->History->goBack();
	}


	function reporte_facturacion_confirmadas($facturaId, $fileName) {
		$records = $this->Liquidacion->Factura->report($facturaId);
		$this->set('data', $records);
		$this->set('fileName', $fileName);
		$this->render('../facturas/reporte_facturacion');
	}


	function libro_sueldos_confirmadas($receiptIds, $fileName) {

		$this->Liquidacion->LiquidacionesDetalle->Behaviors->detach('Permisos');
		foreach ($this->Liquidacion->LiquidacionesDetalle->find('all',
				array(  'contain'       => array('Liquidacion'),
						'conditions'    => array(
							'Liquidacion.id'		=> $receiptIds,
							'Liquidacion.estado'	=> 'Confirmada'
						),
						'order'         => array(
							'Liquidacion.empleador_nombre',
							'Liquidacion.periodo',
							'LiquidacionesDetalle.concepto_tipo'))) as $k => $v) {

			if (empty($liquidaciones[$v['Liquidacion']['id']]['Liquidacion'])) {
				$liquidaciones[$v['Liquidacion']['id']]['Liquidacion'] = $v['Liquidacion'];
			}
			$liquidaciones[$v['Liquidacion']['id']]['LiquidacionesDetalle'][] = $v['LiquidacionesDetalle'];

			$periods[] = $v['Liquidacion']['ano'] . str_pad($v['Liquidacion']['mes'], 2, 0, STR_PAD_LEFT) . $v['Liquidacion']['periodo'];
		}
		$this->Liquidacion->LiquidacionesDetalle->Behaviors->attach('Permisos');
		$periods = array_unique($periods);


		$this->set('groupParams', User::getGroupParams());

		/**
		TODO: Must check if not used by group
		*/
		/*
		$this->Liquidacion->Relacion->Empleador->recursive = -1;
		$this->set('employer', $this->Liquidacion->Relacion->Empleador->findById($this->data['Condicion']['Bar-empleador_id']));
		*/
		$this->set('startPage', 1);
		$this->set('periodo', trim(implode(' ', $periods)));
		$this->set('fileName', $fileName);
		$this->set('data', $liquidaciones);
		$this->set('fileFormat', 'Excel5');
		$this->render('libro_sueldos');
	}


    function reporte_liquidaciones_confirmadas($receiptsGroupId, $fileName = 'Excel5', $reprinted = true) {

		$this->Liquidacion->LiquidacionesGrupo->contain(array('Liquidacion' =>
			array(
				'order' =>
					array(
						'IF(Liquidacion.trabajador_cbu="",0,1)' => 'DESC',
						'Liquidacion.trabajador_apellido',
						'Liquidacion.trabajador_nombre'
					)
				)
			)
		);
		$data = $this->Liquidacion->LiquidacionesGrupo->findById($receiptsGroupId);

        $this->set('data', $data);
		$this->set('fileName', $fileName);
		$this->set('reprinted', $reprinted);
		$this->render('reporte_liquidaciones_confirmadas');
    }



    function reporte_resumen_confirmadas($receiptIds, $fileName) {


		$this->Liquidacion->LiquidacionesDetalle->Behaviors->detach('Permisos');
		$conditions['Liquidacion.id'] = $receiptIds;
		$conditions['OR'] = array(
			'LiquidacionesDetalle.concepto_imprimir' => 'Si',
			array(
				'LiquidacionesDetalle.concepto_imprimir' => 'Solo con valor',
				'ABS(LiquidacionesDetalle.valor) >' => 0
			)
		);

		$data = array();
		$r = $this->Liquidacion->LiquidacionesDetalle->find('all', array(
				'conditions'    => $conditions,
				'contain'       => 'Liquidacion',
				'order'         => 'Liquidacion.relacion_id, LiquidacionesDetalle.concepto_orden',
				'fields'        => array(
					'Liquidacion.relacion_legajo',
					'Liquidacion.trabajador_cuil',
					'Liquidacion.trabajador_nombre',
					'Liquidacion.trabajador_apellido',
					'LiquidacionesDetalle.concepto_nombre',
					'LiquidacionesDetalle.concepto_tipo',
					'LiquidacionesDetalle.coeficiente_valor',
					'LiquidacionesDetalle.coeficiente_nombre',
					'COUNT(LiquidacionesDetalle.concepto_nombre) AS cantidad',
					'SUM(LiquidacionesDetalle.valor_cantidad) AS suma_cantidad',
					'SUM(LiquidacionesDetalle.valor) AS valor'),
				'group'         => array(
					'Liquidacion.relacion_id',
					'Liquidacion.relacion_legajo',
					'Liquidacion.trabajador_cuil',
					'Liquidacion.trabajador_nombre',
					'Liquidacion.trabajador_apellido',
					'LiquidacionesDetalle.concepto_nombre',
					'LiquidacionesDetalle.concepto_tipo',
					'LiquidacionesDetalle.coeficiente_valor',
					'LiquidacionesDetalle.coeficiente_nombre')));
		foreach ($r as $record) {
			$data[$record['Liquidacion']['trabajador_cuil']][] = $record;
		}

		$this->Liquidacion->LiquidacionesDetalle->Behaviors->attach('Permisos');
		$this->Liquidacion->Behaviors->detach('Permisos');
		$workers = $this->Liquidacion->find('all', array(
				'conditions'    => array('Liquidacion.id' => $receiptIds),
				'fields'        => array('COUNT(DISTINCT Liquidacion.trabajador_id) AS cantidad'),
				'recursive'     => -1));
		$this->Liquidacion->Behaviors->attach('Permisos');

		$this->set('data', $data);
		$this->set('totalWorkers', $workers[0]['Liquidacion']['cantidad']);
		$this->set('fileName', $fileName);
		$this->set('group_option', 'trabajador');
		$this->data = array();
		$this->render('reporte_resumen');
    }

}
?>