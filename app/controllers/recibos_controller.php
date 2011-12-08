<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los recibos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1345 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-04 16:17:50 -0300 (vie 04 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los recibos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RecibosController extends AppController {


	function afterSave($params) {
		if (!empty($params['RecibosConcepto.recibo_id']) && $params['RecibosConcepto.recibo_id'] == '##ID##') {
			$params['RecibosConcepto.recibo_id'] = $this->Recibo->id;
			$this->Session->setFlash('El Recibo ha sido guardado, por favor ahora agregue los conceptos al recibo.', 'ok');
			$this->redirect($params);
			return false;
		} else {
			return parent::afterSave();
		}
	}


/**
 * detalles.
 * Muestra via desglose los conceptos de un recibo.
 */
	function conceptos($id) {
		$this->Recibo->contain(array('RecibosConcepto', 'RecibosConcepto.Concepto'));
		$this->data = $this->Recibo->read(null, $id);
	}


	function sync($receiptId) {
		$this->Recibo->Empleador->Relacion->recursive = -1;
		$c = 0;
		foreach ($this->Recibo->Empleador->Relacion->find('all', array(
					'recursive'		=> -1,
					'fields'		=> array('Relacion.id'),
					'conditions'	=> array(
						'Relacion.estado'		=> 'Activa',
						'Relacion.recibo_id' 	=> $receiptId))) as $relation) {

			if ($this->Recibo->sync($relation['Relacion']['id'], $receiptId)) {
				$c++;
			}
		}

		$this->Session->setFlash('Se actualizaron los conceptos de ' . $c . ' relaciones.', 'ok');
		$this->redirect('index');
	}


}
?>