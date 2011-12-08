<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los conceptos de los recibos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1346 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-04 17:28:00 -0300 (vie 04 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los conceptos de los recibos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RecibosConceptosController extends AppController {


    function save() {
        if (!empty($this->data['Form']['tipo'])
            && $this->data['Form']['tipo'] == 'addRapido'
            && !empty($this->data['RecibosConcepto']['recibo_id'])) {

            $assignedConcepts = $this->__getAssignedConcepts($this->data['RecibosConcepto']['recibo_id'], true);

			$add = $del = array();
            foreach ($this->data['Concepto'] as $k => $v) {
                list($conceptId, $conceptCode) = explode('|', $k);
                if ($v == 1 && !in_array($conceptCode, $assignedConcepts)) {
                    $add[] = array('RecibosConcepto' => array(
                        'recibo_id'     => $this->data['RecibosConcepto']['recibo_id'],
                        'concepto_id'   => $conceptId));
                } elseif ($v == 0 && in_array($conceptCode, $assignedConcepts)) {
					$data = $this->RecibosConcepto->find('first', array(
						'recursive'		=> -1,
						'fields'		=> array('RecibosConcepto.id'),
						'conditions' 	=> array(
							'recibo_id'     => $this->data['RecibosConcepto']['recibo_id'],
                        	'concepto_id'   => $conceptId
						)
					));

					if (!empty($data['RecibosConcepto']['id'])) {
						$del[] = array('RecibosConcepto' => array(
							'id'			=> $data['RecibosConcepto']['id'],
							'estado'		=> 'Eliminado'
						));
					}
                }
            }

			$save = array_merge($add, $del);
            if (!empty($save)) {
                $this->RecibosConcepto->appSave($save);
            }

			/*
            if (!empty($del)) {
                $this->RecibosConcepto->deleteAll(array(
                    'recibo_id'     => $this->data['RecibosConcepto']['recibo_id'],
                    'concepto_id'   => $del));
            }
			*/

            $this->Session->setFlash('La operacion se realizo con exito.', 'ok');
            $this->redirect(array('controller' => 'recibos', 'action' => 'index'));
        } else {
            return parent::save();
        }
    }

/**
* Permite realizar un add mediante tablas fromto.
*/
    function add_rapido() {

        if (!empty($this->passedArgs['RecibosConcepto.recibo_id'])) {
            $receipt = $this->__getAssignedConcepts($this->passedArgs['RecibosConcepto.recibo_id']);
            $assignedConcepts = Set::extract('/RecibosConcepto/Concepto/codigo', $receipt);
            $concepts = $this->RecibosConcepto->Concepto->find('all',
                array(  'recursive'  => -1,
                        'order'      => array('Concepto.nombre')));

            $this->set('receipt', $receipt);
            $this->set('concepts', $concepts);
            $this->set('assignedConcepts', $assignedConcepts);
        } else {
            $this->Session->setFlash('Debe seleccionar un Recibo.', 'error');
            $this->History->goBack(2);
        }
    }


    function __getAssignedConcepts($agreementId, $extract = false) {
        $this->RecibosConcepto->Recibo->contain(array('RecibosConcepto.Concepto'));
        $receipt = $this->RecibosConcepto->Recibo->findById($agreementId);
        if ($extract === true) {
            return Set::extract('/RecibosConcepto/Concepto/codigo', $receipt);
        } else {
            return $receipt;
        }
    }


}
?>