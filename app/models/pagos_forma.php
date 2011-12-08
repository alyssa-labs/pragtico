<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las formas de pagos
 * con las que se cancelan los pagos que se le realizan a las relaciones laborales.
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
 * La clase encapsula la logica de acceso a datos asociada a las formas de pagos
 * con las que se cancelan los pagos que se le realizan a las relaciones laborales.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class PagosForma extends AppModel {

    var $permissions = array('permissions' => 288, 'group' => 'default', 'role' => 'all');

	var $modificadores = array(	'add'  	=> 
			array('valoresDefault' => array(	'fecha' 		=> array('date' => 'Y-m-d'),
										   		'fecha_pago' 	=> array('date' => 'Y-m-d'))));
	
	var $validate = array(
        'fecha' => array(
			array(
				'rule'		=> VALID_DATE, 
				'message'	=> 'Debe especificar una fecha valida.')
        ),
        'forma' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar una forma de pago.')
        ),
        'cbu_numero' => array(
			array(
				'rule'		=> array('minLength', 22),
				'allowEmpty' => true,
				'message' 	=> 'Debe ingresar los 22 numeros del CBU.'),
			array(
				'rule'		=> 'validCbu',
				'message'	=> 'El Cbu ingresado no es valido.')
        ),
        'cheque_numero' => array(
			array(
				'rule'		=> array('minLength', 8),
				'allowEmpty' => true,
				'message'	 => 'Debe ingresar los 8 numeros del cheque.')
        ),
        'monto' => array(
			array(
				'rule'		=> VALID_NUMBER_MAYOR_A_CERO, 
				'message'	=> 'Debe especificar un monto mayor a cero.')
        )
	);

	var $belongsTo = array(	'Pago' =>
                        array('className'    => 'Pago',
                              'foreignKey'   => 'pago_id'),
							'Cuenta' =>
                        array('className'    => 'Cuenta',
                              'foreignKey'   => 'cuenta_id')
                        );


/**
 * Permite revertir una forma de pago.
 */
	function revertir($id) {
		$this->recursive = -1;
		$pagosForma = $this->findById($id);
		$pagosForma['PagosForma']['id'] = null;
		$pagosForma['PagosForma']['monto'] = $pagosForma['PagosForma']['monto'] * -1;
		$pagosForma['PagosForma']['observacion'] = 'Esta forma de pago ha sido revertida';
		$this->chequearBeforeSave = false;
		$this->begin();
		if ($this->save($pagosForma, false)) {
			if ($this->Pago->updateState($pagosForma['PagosForma']['pago_id'])) {
				$this->commit();
				return true;
			}
		}
		$this->rollback();
		return false;
	}

	
	function beforeValidate($options = array()) {

		/**
		* Cada forma de pago tiene valores que no corresponden, me aseguro de quitarlos.
		*/
		switch($this->data['PagosForma']['forma']) {
			case 'Efectivo':
			case 'Beneficios':
			case 'Otro':
				unset($this->data['PagosForma']['cheque_numero']);
				$this->data['PagosForma']['cuenta_id'] = null;
                unset($this->data['PagosForma']['cbu_numero']);
				break;
			case 'Deposito':
				if (empty($this->data['PagosForma']['cbu_numero'])) {
					$pago = $this->Pago->find('first', 
					  		array('contain'		=> array( 'Liquidacion.Relacion.Trabajador'),
								  'conditions' 	=> 
										array('Pago.id' => $this->data['PagosForma']['pago_id'])));
					if (empty($pago['Liquidacion']['Relacion']['Trabajador']['cbu'])) {
						$this->invalidate('cbu_numero', 'El trabajador no tiene CBU asignado y no ha ingresado ninguno.');
						return false;
					}
					$this->data['PagosForma']['cbu_numero'] = $pago['Liquidacion']['Relacion']['Trabajador']['cbu'];
				}
				if (empty($this->data['PagosForma']['fecha_pago'])) {
					$this->invalidate('fecha_pago', 'Debe ingresar La fecha de acreditacion del deposito.');
					return false;
				}
				if (empty($this->data['PagosForma']['cuenta_id'])) {
					$this->invalidate('cuenta_id', 'Debe seleccionar la Cuenta Emisora del Deposito.');
				}
				unset($this->data['PagosForma']['cheque_numero']);
				break;
			case 'Cheque':
				if (empty($this->data['PagosForma']['fecha_pago'])) {
					$this->invalidate('fecha_pago', 'Debe ingresar La fecha de pago del Cheque.');
					return false;
				}
				if (empty($this->data['PagosForma']['cheque_numero'])) {
					$this->invalidate('cheque_numero', 'Debe ingresar el Numero de Cheque.');
					return false;
				}
				if (empty($this->data['PagosForma']['cuenta_id'])) {
					$this->invalidate('cuenta_id', 'Debe seleccionar la Cuenta Emisora del Cheque.');
				}
				break;
		}
		if (($this->data['PagosForma']['pago_acumulado'] + $this->data['PagosForma']['monto']) > $this->data['PagosForma']['pago_monto']) {
			$this->dbError['errorDescripcion'] = 'El monto ingresado ($ ' . $this->data['PagosForma']['monto'] . ') mas el acumulado ($ ' . $this->data['PagosForma']['pago_acumulado'] . ') supera el Total del Pago ($ ' . $this->data['PagosForma']['pago_monto'] . '). Verifique.';
			return false;
		}
		else if (($this->data['PagosForma']['pago_acumulado'] + $this->data['PagosForma']['monto']) == $this->data['PagosForma']['pago_monto']) {
			$save = array('id'=>$this->data['PagosForma']['pago_id'], 'estado' => 'Imputado', 'permissions' => '292');
			if (!$this->Pago->save(array('Pago' => $save))) {
				$this->dbError['errorDescripcion'] = 'No fue posible actualizar el estado del Pago.';
				return false;
			}
		}
		/**
		* Permiso de solo lectura.
		*/
		$this->data['PagosForma']['permissions'] = '292';
		return parent::beforeValidate($options);
	}


/**
 * Dada una cuenta, retorna el ultimo numero de cheque emitido desde dicha cuenta.
 */
	function getUltimoNumeroCheque($cuentaId) {
		if (!empty($cuentaId) && is_numeric($cuentaId)) {
			$chequeNumero = $this->find('first', array('conditions' => array('PagosForma.cuenta_id'=>$cuentaId, 'PagosForma.forma' => 'Cheque'),
											'fields' => 'MAX(cheque_numero) AS cheque_numero', 'recursive' => -1, 'seguridad'=>false));
			if (!empty($chequeNumero[0]['cheque_numero'])) {
				return $chequeNumero[0]['cheque_numero'];
			}
		}
		return 0;
	}

}
?>