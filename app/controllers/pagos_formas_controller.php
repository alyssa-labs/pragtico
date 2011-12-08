<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las formas de pagos
 * con las que se cancelan los pagos que se le realizan a las relaciones laborales.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 215 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-01-08 11:41:05 -0200 (jue 08 de ene de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

/**
 * La clase encapsula la logica de acceso a datos asociada a las formas de pagos
 * con las que se cancelan los pagos que se le realizan a las relaciones laborales.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class PagosFormasController extends AppController {

	function buscar_ultimo_numero_cheque($cuenta_id) {
		$ultimoNumeroCheque = $this->PagosForma->getUltimoNumeroCheque($cuenta_id);
		$this->set("data", str_pad($ultimoNumeroCheque + 1, 8, '0', STR_PAD_LEFT) );
		$this->render('../elements/string');
	}


	function revertir_pagos_forma($id) {
		if ($this->PagosForma->revertir($id)) {
			$this->Session->setFlash("La forma de pago se revirtio correctamente.", "ok");
		}
		else {
			$errores = $this->$this->PagosForma->getError();
			$this->Session->setFlash("No fue posible revertir la forma de pago.", "error", array("errores"=>$errores));
		}
		$this->History->goBack(2);
	}
	
	
/**
 * Realiza los seteos especificos (valores por defecto) al agregar y/o editar.
 */
	function beforeRender() {
		if (!empty($this->params['named']['PagosForma.pago_id'])) {
			$pagoId = $this->params['named']['PagosForma.pago_id'];
		} else if (!empty($this->data['PagosForma']['pago_id'])) {
			$pagoId = $this->data['PagosForma']['pago_id'];
		}

		if (!empty($this->params['named']['PagosForma.forma']) && $this->params['named']['PagosForma.forma'] === "Cheque") {
			$this->data['PagosForma']['forma'] = 'Cheque';
			$this->data['PagosForma']['fecha_pago'] = $this->Util->format($this->Util->dateAdd(date("Y-m-d")), 'date');
		}
		
		if (!empty($pagoId) && empty($this->data['PagosForma']['monto'])) {
			$this->PagosForma->Pago->contain('PagosForma');
			$pago = $this->PagosForma->Pago->read(null, $pagoId);
			$total = array_sum(Set::extract('/PagosForma/monto', $pago));
			$this->data['PagosForma']['monto'] = $pago['Pago']['monto'] - $total;
			$this->data['PagosForma']['pago_monto'] = $pago['Pago']['monto'];
			$this->data['PagosForma']['pago_acumulado'] = $total;
		}
	}

}
?>