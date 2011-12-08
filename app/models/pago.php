<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los pagos que se le realizan a las relaciones laborales.
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
 * La clase encapsula la logica de acceso a datos asociada a los pagos que se le realizan a las relaciones laborales.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Pago extends AppModel {

    var $permissions = array('permissions' => 288, 'group' => 'default', 'role' => 'all');

	var $modificadores = array(	'index' =>
			array('contain'	=> array('Descuento', 'Liquidacion', 'PagosForma', 'Relacion'	=> array('Empleador', 'Trabajador'))));
	
	var $validate = array(
        'fecha' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe especificar una fecha valida.')
        )
	);

	var $belongsTo = array('Relacion', 'Liquidacion', 'Descuento');
                              
	var $hasMany = array(	'PagosForma' =>
                        array('className'    => 'PagosForma',
                              'foreignKey'   => 'pago_id'));


/**
 * Calculate balance. Total payment - sum(partial payments).
 */
	function afterFind($results, $primary = false) {
		if ($primary === true && !empty($results[0]['Pago'])) {
			foreach ($results as $k => $result) {
				$results[$k]['Pago']['saldo'] = $results[$k]['Pago']['monto'] - $this->__getPartialPayments($result);
			}
		}
		return $results;
	}
	

    function getTotal($conditions = array()) {
        $this->unbindModel(array(
            'hasMany'             => array('PagosForma')));
        $result = $this->find('first', array(
            'conditions'    => $conditions,
            'callbacks'     => false,
            'fields'        => 'SUM(Pago.monto) as total',
            ));
        $this->bindModel(array(
            'hasMany'             => array('PagosForma')));
        return $result[0]['total'];
    }

/**
 *
 */
	function registrarPago($ids, $tipo, $savePago = array()) {

		$retorno = true;
		$tipo = ucfirst($tipo);
			
		if ($tipo === 'Deposito') {
			$this->contain(array('PagosForma', 'Relacion.Trabajador'));
		} else {
			$this->contain('PagosForma');
		}
		$pagosTmp = $this->find('all',
            array('conditions'=>array('Pago.id' => $ids, 'Pago.estado' => array('Pendiente', 'En Soporte'))));

		$ids = array();
		foreach ($pagosTmp as $pago) {
			$pagos[$pago['Pago']['id']] = $pago;
			$ids[] = $pago['Pago']['id'];
		}

		$c = 0;
		foreach ($ids as $id) {
			if (($pagos[$id]['Pago']['moneda'] === 'Beneficios' && $tipo === 'Beneficios')
				|| ($pagos[$id]['Pago']['moneda'] === 'Pesos' && $tipo !== 'Beneficios')) {

				
				$acumulado = $this->__getPartialPayments($pagos[$id]);
				/**
				* Determino si tiene la pagos parciales.
				*/
				$acumulado = 0;
				foreach ($pagos[$id]['PagosForma'] as $v) {
					$acumulado += $v['monto'];
				}
				$save = null;
				$save['id'] = null;
				$save['pago_id'] = $id;
				$save['forma'] = $tipo;
				$save['fecha'] = date('Y-m-d');
				$save['fecha_pago'] = date('Y-m-d');
				$save['permissions'] = '292';
				$save['monto'] = $pagos[$id]['Pago']['monto'] - $acumulado;
				/**
				* El beforeSave hara otra validacion, para lo cual necesitara estos datos.
				*/
				$save['pago_monto'] = $pagos[$id]['Pago']['monto'];
				$save['pago_acumulado'] = $acumulado;
				
				if ($tipo === 'Deposito') {
					$save['cbu_numero'] = $pagos[$id]['Relacion']['Trabajador']['cbu'];
				}


				/** Try to get the account id */
				if ($pagos[$id]['Pago']['estado'] == 'En Soporte') {
					$tmp = explode('|', $pagos[$id]['Pago']['identificador']);
					if (count($tmp) == 2 && is_numeric($tmp[0])) {
						$save['cuenta_id'] = $tmp[0];
					}
				}


				/**
				* Cuando un pago esta imputado, ya no permito que sea borrado o modificado.
				*/
				$savePago['permissions'] = '292';
				$savePago['estado'] = 'Imputado';
				$savePago['id'] = $id;
				
				if ($this->appSave(array(
						'Pago' 			=> $savePago,
					 	'PagosForma'	=> array($save)),
						array('validate' => false))) {

                    $c++;
                }
			}
		}
		return $c;
	}


/**
 * Gets the sum of partials payments.
 *
 * @param mixed $payment The payment with all it's partial payments or the paymentId.
 * @return 	double The sum of partial payments.
 *	
 * @access private
 */	
	function __getPartialPayments($payment) {
		if (!is_array($payment)) {
			$this->contain('PagosForma');
			$payment = $this->findById($payment);
		} 
		return array_sum(Set::extract('/PagosForma/monto', $payment));
	}

	
/**
 * Sets the payment state.
 *
 * @param array $payment The payment id.
 * @return 	boolean True on success, false in other case.
 *	
 * @access public
 */	
	function updateState($paymentId) {
		$save = null;
		
		/**
		 * Cuando un pago esta imputado, ya no permito que sea borrado o modificado.
		 */
		$save['id'] = $paymentId;
		$save['permissions'] = '292';
		
		$this->contain('PagosForma');
		$payment = $this->findById($paymentId);
		if ($payment['Pago']['monto'] == $this->__getPartialPayments($payment)) {
			$save['estado'] = 'Imputado';
		} else {
			$save['estado'] = 'Pendiente';
		}
		if ($this->save(array('Pago' => $save))) {
			return true;
		}
		return false;
	}
	
	
/**
 * Permite revertir un pago.
 */
	function revertir($id) {
		$this->begin();
		$save = null;
		$save['id'] = null;
		$save['monto'] = $this->__getPartialPayments($id) * -1;
		$save['forma'] = 'Efectivo';
		$save['observacion'] = 'Este pago ha sido revertido';
		$save['fecha'] = date('Y-m-d');
		$save['pago_id'] = $id;
		
		$this->begin();
		if ($this->PagosForma->save(array('PagosForma' => $save), false) && $this->updateState($id)) {
			$this->commit();
			return true;
		}
		$this->rollBack();
		return false;
	}	
	
	
/**
 * Genera el contenido del archivo para presentar en los bancos para la acreditacion de haberes.
 *
 * @param array $opciones Array con datos requeridos para la generacion del archivo.
 *						Son requeridos 	cuenta_id,
 *										pago_id,
 *										empleador_id
 *						Opcional		fecha_acreditacion
 * @param boolean $confirm. False when just count, True when update to new state.
 * @return 	mixed 	array de dos componentes:
 *						contenido, con el contenido del archivo en caso de exito.
 *						banco, con nombre del banco para el cual se genero el archivo.
 *					false en caso de falla.
 * @access public
 */
	function generarSoporteMagnetico($opciones, $confirm = false) {

		$contenido = false;
		
		if (!empty($opciones['cuenta_id']) && !empty($opciones['pago_id'])) {

			$conditions = array(
					'Pago.estado'		=> 'Pendiente',
	 				'Pago.id'			=> $opciones['pago_id']);
	  				
			$pagos =  $this->find('all', 
			  		array(	'contain'		=> array('Relacion.Trabajador'),
						  	'conditions' 	=> $conditions));
			
			if (!empty($pagos)) {

                App::import('Vendor', 'utils', 'pragmatia');
                
                $this->Relacion->Empleador->Cuenta->contain(array('Empleador', 'Sucursal.Banco'));
                $cuenta = $this->Relacion->Empleador->Cuenta->findById($opciones['cuenta_id']);
                $bankCode = str_pad($cuenta['Sucursal']['Banco']['codigo'], 3, '0', STR_PAD_LEFT);
                
				$total = 0;
				$pagosIds = array();
				foreach ($pagos as $pago) {
                    
					if (preg_match('/(\d\d\d)(\d\d\d\d)\d(\d\d\d\d\d\d\d\d\d\d\d\d\d)\d$/', $pago['Relacion']['Trabajador']['cbu'], $matches)) {

                        /** Avoid creating a deposit where origin and target accounts are fron different banks */
                        if ($matches[1] != $bankCode) {
                            continue;
                        }

                        $pagosIds[] = $pago['Pago']['id'];
                        $total += number_format($pago['Pago']['monto'], 2, '.', '');
                        
                        if ($confirm === true) {
                            switch ($bankCode) {
                                case '072': //Rio
                                    if ($pago['Relacion']['Trabajador']['tipo_cuenta'] === 'Cta. Cte.') {
                                        $tipoCuentaTrabajador = '2';
                                    } elseif ($pago['Relacion']['Trabajador']['tipo_cuenta'] === 'Caja de Ahorro') {
                                        $tipoCuentaTrabajador = '3';
                                    }
                                    $c = null;
                                    $c[] = '0'; // moneda
                                    $c[] = Utils::normalizeText($pago['Relacion']['Trabajador']['apellido'] . ' ' . $pago['Relacion']['Trabajador']['nombre'], 15); //nombre
                                    $c[] = Utils::normalizeText(str_replace('-', '', $pago['Relacion']['Trabajador']['cuil']), 11); //cuil
                                    $c[] = $matches[2]; // Sucursal
                                    $c[] = $tipoCuentaTrabajador; // Tipo de Cuenta
                                    $c[] = substr($matches[3], 2); // Cuenta
                                    $c[] = Utils::normalizeText(number_format($pago['Pago']['monto'], 2, '', ''), 15, 'number'); // importe
                                    $rds[] = implode(';', $c);
                                    break;
                                case '007': //Galicia
                                    if ($pago['Relacion']['Trabajador']['tipo_cuenta'] === 'Cta. Cte.') {
                                        $tipoCuentaTrabajador = '0';
                                    } elseif ($pago['Relacion']['Trabajador']['tipo_cuenta'] === 'Caja de Ahorro') {
                                        $tipoCuentaTrabajador = '4';
                                    }
                                    
                                    $rd = null;
                                    $rd[] = 'D';
                                    $rd[] = str_pad($cuenta['Cuenta']['convenio'], 5, '0', STR_PAD_LEFT); //Numero de empresa (convenio)
                                    $rd[] = $tipoCuentaTrabajador; //tipo de cuenta
                                    $rd[] = str_pad(substr($pago['Relacion']['Trabajador']['cbu'], 13, 6), 6, '0', STR_PAD_LEFT); //folio (cuenta)
                                    $rd[] = substr($pago['Relacion']['Trabajador']['cbu'], 19, 1); //1 digito
                                    $rd[] = str_pad(substr($pago['Relacion']['Trabajador']['cbu'], 4, 3), 3, '0', STR_PAD_LEFT); //sucursal
                                    $rd[] = substr($pago['Relacion']['Trabajador']['cbu'], -2, 1); //2 digito
                                    $rd[] = strtoupper(Utils::normalizeText($pago['Relacion']['Trabajador']['apellido'] . ' ' . $pago['Relacion']['Trabajador']['nombre'], 20)); //nombre
                                    $rd[] = Utils::normalizeText(number_format($pago['Pago']['monto'], 2, '', ''), 14, 'number'); //importe
                                    $rd[] = '01'; //concepto
                                    $rd[] = str_repeat(' ', 11); //libre
                                    $rds[] = implode('', $rd);
                                    break;
                                case '011': //Nacion
                                    if (!empty($opciones['fecha_acreditacion']) && preg_match(VALID_DATE, $opciones['fecha_acreditacion'], $matchesDate)) {
                                        $fechaAcreditacion = $matchesDate[3] . $matchesDate[2] . substr($matchesDate[1], -2);
                                    } else {
                                        $fechaAcreditacion = date('dmy');
                                    }
                                    $c = null;
                                    
                                    $c[] = $matches[2]; // Sucursal
                                    $c[] = substr($matches[3], -10); // Nro cuenta
                                    $c[] = '141'; // nadie sabe que es, pero debe ir este valor
                                    $c[] = $fechaAcreditacion; // fecha de acreditacion
                                    $c[] = 'CTRE0'; // nadie sabe que es, pero debe ir este valor
                                    $c[] = Utils::normalizeText($pago['Relacion']['Trabajador']['numero_documento'], 8, 'number'); //dni
                                    $c[] = Utils::normalizeText(number_format($pago['Pago']['monto'], 2, '', ''), 13, 'number'); //importe
                                    $c[] = Utils::normalizeText($pago['Relacion']['Trabajador']['apellido'] . ' ' . $pago['Relacion']['Trabajador']['nombre'], 30); //nombre
                                    $c[] = '96'; // nadie sabe que es, pero debe ir este valor
                                    $c[] = Utils::normalizeText($pago['Relacion']['Trabajador']['numero_documento'], 8, 'number'); //dni
                                    $rds[] = implode('', $c);
                                    break;
                                case '044': //Hipotecario

                                    if (!empty($opciones['fecha_acreditacion']) && preg_match(VALID_DATE, $opciones['fecha_acreditacion'], $matchesDate)) {
                                        $fechaAcreditacion = $matchesDate[3] . '/' . $matchesDate[2] . '/' . $matchesDate[1];
                                    } else {
                                        $fechaAcreditacion = date('d/m/y');
                                    }
                                    $c = null;
                                    $c[] = $pago['Relacion']['legajo'];
									$c[] = $pago['Relacion']['Trabajador']['apellido'] . ' ' . $pago['Relacion']['Trabajador']['nombre']; //nombre
									//           1 2  3 4 5  6  7  8 9 1  2 3 4 5 6 7 8 9 1 2 3  4
									preg_match('/\d\d(\d\d\d)\d(\d)\d\d\d(\d\d\d\d\d\d\d\d\d\d\d)\d$/', $pago['Relacion']['Trabajador']['cbu'], $matchesTmp);
									unset($matchesTmp[0]);
                                    $c[] = implode('', $matchesTmp);
									$c[] = $pago['Relacion']['Trabajador']['cbu'];
									$c[] = '';
									$c[] = '11';
									$c[] = $pago['Relacion']['Trabajador']['numero_documento'];
                                    $c[] = $fechaAcreditacion;
                                    $c[] = $pago['Pago']['monto'];
                                    $rds[] = implode(',', $c);
                                    break;
                            }
                        }
					}
				}

                if ($confirm === true) {
                    if (!empty($rds)) {
                        switch ($bankCode) {
                            case '072':
                            case '011':
							case '044':
                                $contenido = implode("\r\n", $rds);
                                break;
                            case '007':
                                $fechaAcreditacion = date('Ymd');
                                if (!empty($opciones['fecha_acreditacion'])) {
                                    preg_match('/(\d\d)\/(\d\d)\/(\d\d\d\d)$/', $opciones['fecha_acreditacion'], $matches);
                                    if (!empty($matches[1]) && !empty($matches[2]) && !empty($matches[3])) {
                                        $fechaAcreditacion = $matches[3] . $matches[2] . $matches[1];
                                    }
                                }
                                if ($cuenta['Cuenta']['tipo'] == 'Cta. Cte.') {
                                    $tipoCuentaEmpleador = '0';
                                } elseif ($cuenta['Cuenta']['tipo'] == 'Caja de Ahorro') {
                                    $tipoCuentaEmpleador = '9';
                                }
                                $rh[] = 'H';
                                $rh[] = Utils::normalizeText($cuenta['Cuenta']['convenio'], 5, 'number'); //Numero de empresa
                                $rh[] = $tipoCuentaEmpleador; //tipo de cuenta
                                $rh[] = str_pad(substr($cuenta['Cuenta']['cbu'], 13, 6), 6, '0', STR_PAD_LEFT); //folio (cuenta)
                                $rh[] = substr($cuenta['Cuenta']['cbu'], 19, 1); //1 digito
                                $rh[] = str_pad(substr($cuenta['Cuenta']['cbu'], 4, 3), 3, '0', STR_PAD_LEFT); //sucursal
                                $rh[] = substr($cuenta['Cuenta']['cbu'], -2, 1); //2 digito
                                $rh[] = Utils::normalizeText(number_format($total, 2, '', ''), 14, 'number'); //importe total
                                $rh[] = Utils::normalizeText($fechaAcreditacion, 8); //fecha acreditacion
                                $rh[] = str_pad('', 25, ' ', STR_PAD_RIGHT); //libre
                                $rhs = implode('', $rh);
                                $rf[] = 'F';
                                $rf[] = Utils::normalizeText($cuenta['Cuenta']['convenio'], 5, 'number'); //Numero de empresa
                                $rf[] = Utils::normalizeText(count($rds), 7, 'number'); //cantidad registros
                                $rf[] = str_repeat(' ', 52); //libre
                                $rfs = implode('', $rf);
                                $contenido = $rhs . "\r\n" . implode("\r\n", $rds) . "\r\n" . $rfs;
                                break;
                        }
                    }
                    $this->unbindModel(array(
                        'belongsTo' => array_keys($this->belongsTo)
                    ));
                    $this->updateAll(
                            array(  'Pago.identificador'    => '\'' . $opciones['cuenta_id'] . '|' . date('Y-m-d H:i:s') . '\'',
                                    'Pago.estado'           => '\'En Soporte\''),
                            array(  'Pago.id'               => $pagosIds));
                }
			} else {
				return false;
			}
		}

        if ($confirm === true) {
            return array('contenido' => $contenido, 'banco' => $bankCode);
        } else {
            return array('pagos' => $pagosIds, 'total' => $total, 'cuenta' => $cuenta);
        }
	}

}
?>