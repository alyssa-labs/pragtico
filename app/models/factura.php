<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las facturas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1423 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las facturas.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Factura extends AppModel {

    var $permissions = array('permissions' => 448, 'group' => 'default', 'role' => 'all');

	var $hasMany = array(	'Liquidacion',
							'FacturasDetalle' =>
                        array('dependent'   => true));

	var $belongsTo = array('Empleador', 'Area');



    function deleteAll($conditions, $cascade = true, $callbacks = false) {

        $this->setSecurityAccess('readOwnerOnly');
        $InvoiceIds = Set::extract('/Factura/id',
            $this->find('all', array(
                'recursive'     => -1,
                'conditions'    => array(
                    'Factura.user_id'   => User::get('/Usuario/id'),
                    'Factura.estado'    => 'Sin Confirmar'))));
        if (!empty($InvoiceIds)) {
            $this->Liquidacion->updateAll(
                array('Liquidacion.factura_id' => null),
                array('Liquidacion.factura_id' => $InvoiceIds));
        }
        
        $this->unbindModel(array('hasMany' => array('Liquidacion')), false);
        $return = parent::deleteAll($conditions, $cascade, $callbacks, true);
        $this->bindModel(array('hasMany' => array('Liquidacion')));
        return $return;
    }

	function __createAndSave($params) {

		$total = 0;
		$saveDatails = array();
		foreach ($params['saveDatails'] as $tmp) {
			$saveDatails[] = $tmp;
			$total += $tmp['total'];
		}

		$saveMaster['empleador_id'] = $params['employerId'];
		$saveMaster['area_id'] = $params['areaId'];
		$saveMaster['fecha'] = date('Y-m-d');
		$saveMaster['estado'] = 'Sin Confirmar';
		$saveMaster['total'] = $total;
		$saveMaster['confirmable'] = 'No';
		if ($params['conditions']['Liquidacion.estado'] === 'Confirmada') {
			$saveMaster['confirmable'] = 'Si';
		}
		$saveMaster['ano'] = $params['conditions']['Liquidacion.ano'];
		$saveMaster['mes'] = $params['conditions']['Liquidacion.mes'];
		$saveMaster['periodo'] = 'M';
		if (!empty($params['conditions']['Liquidacion.periodo like'])) {
			$saveMaster['periodo'] = str_replace('%', '', $params['conditions']['Liquidacion.periodo like']);
		}
		$saveMaster['tipo'] = Inflector::humanize($params['conditions']['Liquidacion.tipo']);

        if (!empty($params['groupId'])) {
            $saveMaster['group_id'] = $params['groupId'];
            foreach ($saveDatails as $k => $detail) {
                $saveDatails[$k]['group_id'] = $params['groupId'];
            }
        }

		/** When atomic is false, means it's coming from auto-invoice, so must confirm the created invoice also */
		if ($params['atomic'] === false) {
			$saveMaster['estado'] = 'Confirmada';
			$saveMaster['permissions'] = '288';
		}

		if (!empty($saveDatails)) {
			$save = array_merge(array('Factura' => $saveMaster), array('FacturasDetalle' => $saveDatails));
		} else {
			$save = array('Factura' => $saveMaster);
		}

		if ($this->saveAll($save, array('atomic' => $params['atomic']))) {
			$this->Liquidacion->unbindModel(array('belongsTo' => array(
				'Convenio', 'Area', 'Relacion', 'Factura', 'Trabajador', 'Empleador')));
			
			if ($this->Liquidacion->updateAll(array('Liquidacion.factura_id' => $this->id), array('Liquidacion.id' => $params['receiptIds']))) {
				return $this->id;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

    /** Must update receipts */
	function beforeDelete($casacade = true) {
        $this->recursive = -1;
        $invoice = $this->findById($this->id);
        if ($invoice['Factura']['estado'] === 'Sin Confirmar') {
            return $this->Liquidacion->updateAll(array('Liquidacion.factura_id' => null), array('Liquidacion.factura_id' => $this->id));
        }
        return false;
    }
    
	function getInvoice($conditions = null, $groupId = null, $checkAutoInvoice = false) {

		if (empty($conditions)) {
			return false;
		}

		$createdInvoicesIds = array();

		$conditions = array_merge($conditions,
			array(	'OR' => array(
				'Liquidacion.factura_id' 	=> null,
					array(	'Factura.estado' 				=> 'Sin Confirmar',
							'Liquidacion.factura_id !=' 	=> null))));

		$this->Liquidacion->setSecurityAccess('readOwnerOnly');
        $r = $this->Liquidacion->find('all',
			array(	'conditions' 	=> $conditions,
					'order' 		=> array(
						'Liquidacion.empleador_id',
						'Liquidacion.relacion_area_id'),
				 	'contain'		=> array(
						'Empleador',
						'LiquidacionesDetalle',
						'Factura')));


        if (!empty($r)) {

			foreach ($r as $receipt) {
				$separatedData[$receipt['Liquidacion']['tipo']][] = $receipt;
			}


			foreach ($separatedData as $data) {

				$saveMaster = $saveDatails = array();
				$employerId = null;
				$areaId = null;
				$receiptIds = null;

				foreach ($data as $k => $receipt) {

					$atomic = true;
					if ($checkAutoInvoice === true) {

						$atomic = false;

						if ($receipt['Empleador']['auto_facturar'] === 'No') {
							continue;
						} else {
							$conditions = array_merge($conditions,
								array(	'Liquidacion.estado' 	=> $receipt['Liquidacion']['estado'],
										'Liquidacion.ano' 		=> $receipt['Liquidacion']['ano'],
										'Liquidacion.mes' 		=> $receipt['Liquidacion']['mes'],
										'Liquidacion.tipo' 		=> $receipt['Liquidacion']['tipo']));
						}
					}


					if ($receipt['Empleador']['facturar_por_area'] === 'No'
						&& $employerId != $receipt['Liquidacion']['empleador_id']) {
						$employerId = $receipt['Liquidacion']['empleador_id'];
						$areaId = null;
						if ($k > 0) {
							$createdInvoicesIds[] = $this->__createAndSave(array(
								'employerId'		=> $employerId,
								'receiptIds'		=> $receiptIds,
								'areaId' 			=> $areaId,
								'saveDatails'		=> $saveDatails,
								'conditions' 		=> $conditions,
								'groupId'			=> $groupId,
								'atomic'			=> $atomic));
							$saveMaster = $saveDatails = $receiptIds = array();
						}
					} elseif ($receipt['Empleador']['facturar_por_area'] === 'Si'
						&& $areaId != $receipt['Liquidacion']['relacion_area_id']) {
						if ($areaId != null && !empty($saveDatails)) {
							$createdInvoicesIds[] = $this->__createAndSave(array(
								'employerId'		=> $employerId,
								'receiptIds'		=> $receiptIds,
								'areaId' 			=> $areaId,
								'saveDatails'		=> $saveDatails,
								'conditions' 		=> $conditions,
								'groupId'			=> $groupId,
								'atomic'			=> $atomic));
							$saveMaster = $saveDatails = $receiptIds = array();
						}
						$employerId = $receipt['Liquidacion']['empleador_id'];
						$areaId = $receipt['Liquidacion']['relacion_area_id'];
					}


					$receiptIds[] = $receipt['Liquidacion']['id'];
					foreach ($receipt['LiquidacionesDetalle'] as $detail) {
						if ($detail['coeficiente_tipo'] !== 'No Facturable' && ($detail['concepto_imprimir'] === 'Si' || ($detail['concepto_imprimir'] === 'Solo con valor') && abs($detail['valor']) > 0)) {
							if (!isset($saveDatails[$detail['coeficiente_id']])) {
								$saveDatails[$detail['coeficiente_id']]['coeficiente_id'] = $detail['coeficiente_id'];
								$saveDatails[$detail['coeficiente_id']]['coeficiente_nombre'] = $detail['coeficiente_nombre'];
								$saveDatails[$detail['coeficiente_id']]['coeficiente_tipo'] = $detail['coeficiente_tipo'];
								$saveDatails[$detail['coeficiente_id']]['coeficiente_valor'] = $detail['coeficiente_valor'];
								$saveDatails[$detail['coeficiente_id']]['subtotal'] = $detail['valor'];
								$saveDatails[$detail['coeficiente_id']]['total'] = $detail['valor'] * $detail['coeficiente_valor'];
							} else {
								$saveDatails[$detail['coeficiente_id']]['subtotal'] += $detail['valor'];
								$saveDatails[$detail['coeficiente_id']]['total'] += $detail['valor'] * $detail['coeficiente_valor'];
							}
						}
					}
				}


				if (!empty($receiptIds)) {
					$createdInvoicesIds[] = $this->__createAndSave(array(
						'employerId'		=> $employerId,
						'receiptIds'		=> $receiptIds,
						'areaId' 			=> $areaId,
						'saveDatails'		=> $saveDatails,
						'conditions' 		=> $conditions,
						'groupId'			=> $groupId,
						'atomic'			=> $atomic));
				}
			}

			return $createdInvoicesIds;

		} else {

			return false;

		}
	}
	
	
	function report($invoiceId) {

		$invoice = $this->find('first', array(
			'conditions'	=> array('Factura.id' => $invoiceId),
			'contain'		=> array('Empleador', 'FacturasDetalle', 'Liquidacion.LiquidacionesDetalle')));

		$reportData = null;
		$reportData['Facturado Remunerativo'] = 0;
		$reportData['Facturado No Remunerativo'] = 0;
		$reportData['Facturado Beneficios'] = 0;
		$reportData['Liquidado Remunerativo'] = 0;
		$reportData['Liquidado No Remunerativo'] = 0;
				
		if (!empty($invoice)) {
			foreach ($invoice['Liquidacion'] as $receipt) {

				$trabajador = null;
				foreach ($receipt['LiquidacionesDetalle'] as $detail) {

					if ($detail['coeficiente_tipo'] !== 'No Facturable' && ($detail['concepto_imprimir'] === 'Si' || ($detail['concepto_imprimir'] === 'Solo con valor') && abs($detail['valor']) > 0)) {

						if (empty($trabajador)) {
							$details[$receipt['trabajador_id']]['Trabajador'] = array(
								'legajo'	=> $receipt['relacion_legajo'],
								'nombre'	=> $receipt['trabajador_nombre'],
								'apellido'	=> $receipt['trabajador_apellido']);
						}

						$t = $detail['valor'] * $detail['coeficiente_valor'];
						$t1 = $t2 = $t3 = 0;
						if (!isset($totals[$receipt['trabajador_id']]['Liquidado'])) {
							$totals[$receipt['trabajador_id']]['Liquidado'] = $detail['valor'];
						} else {
							$totals[$receipt['trabajador_id']]['Liquidado'] += $detail['valor'];
						}
						if ($detail['concepto_pago'] === 'Beneficios') {
							if (!isset($totals[$receipt['trabajador_id']]['Beneficios'])) {
								$totals[$receipt['trabajador_id']]['Beneficios'] = $t;
							} else {
								$totals[$receipt['trabajador_id']]['Beneficios'] += $t;
							}
							$t3 = $t;
							$reportData['Facturado Beneficios'] += $t;
						} elseif ($detail['concepto_tipo'] === 'Remunerativo') {
							if (!isset($totals[$receipt['trabajador_id']]['Remunerativo'])) {
								$totals[$receipt['trabajador_id']]['Remunerativo'] = $t;
							} else {
								$totals[$receipt['trabajador_id']]['Remunerativo'] += $t;
							}
							$t1 = $t;
							$reportData['Facturado Remunerativo'] += $t;
							$reportData['Liquidado Remunerativo'] += $detail['valor'];
						} elseif ($detail['concepto_tipo'] === 'No Remunerativo') {
							if (!isset($totals[$receipt['trabajador_id']]['No Remunerativo'])) {
								$totals[$receipt['trabajador_id']]['No Remunerativo'] = $t;
							} else {
								$totals[$receipt['trabajador_id']]['No Remunerativo'] += $t;
							}
							$t2 = $t;
							$reportData['Facturado No Remunerativo'] += $t;
							$reportData['Liquidado No Remunerativo'] += $detail['valor'];
						}

						$details[$receipt['trabajador_id']]['Concepto'][$detail['concepto_codigo']] = array(
							'Descripcion'				=> $detail['concepto_nombre'],
							'Cantidad'					=> $detail['valor_cantidad'],
							'Liquidado'					=> $detail['valor'],
							'Facturado Remunerativo'	=> $t1,
							'Facturado No Remunerativo'	=> $t2,
							'Facturado Beneficios'		=> $t3);

                        $details[$receipt['trabajador_id']]['Totales'] = array_merge(array(
                                'Liquidado'      => 0,
                                'Remunerativo'   => 0,
                                'No Remunerativo'=> 0,
                                'Beneficios'     => 0), $totals[$receipt['trabajador_id']]);
					}
				}
			}

			$reportData['Total de Empleados Facturados'] = count($details);
			$reportData['Iva'] = ($reportData['Facturado No Remunerativo'] + $reportData['Facturado Remunerativo'] + $reportData['Facturado Beneficios']) * 21 / 100;
			$reportData['Total'] = $reportData['Facturado No Remunerativo'] + $reportData['Facturado Remunerativo'] + $reportData['Facturado Beneficios'] + $reportData['Iva'];
			$reportData['Total Liquidado'] = $reportData['Liquidado Remunerativo'] + $reportData['Liquidado No Remunerativo'];
			
            return array(	'invoice'	=> $invoice['Factura'],
						 	'employer' 	=> $invoice['Empleador'],
						 	'details' 	=> $details,
	   						'totals' 	=> $reportData);
		} else {
			return array();
		}
	}
	
}
?>