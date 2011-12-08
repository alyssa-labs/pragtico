<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la relacion que existe
 * entre los convenios colectivos y los conceptos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1126 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-11-10 16:55:56 -0300 (mar 10 de nov de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a la relacion que existe
 * entre los convenios colectivos y los conceptos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ConveniosConceptosController extends AppController {

    var $paginate = array(
        'order' => array(
            'Convenio.nombre'   => 'asc',
            'Concepto.nombre'   => 'asc'
        )
    );


    function save() {
        if (!empty($this->data['Form']['tipo'])
            && $this->data['Form']['tipo'] == 'addRapido'
            && !empty($this->data['ConveniosConcepto']['convenio_id'])) {

            $assignedConcepts = $this->__getAssignedConcepts($this->data['ConveniosConcepto']['convenio_id'], true);

            foreach ($this->data['Concepto'] as $k => $v) {
                list($conceptId, $conceptCode) = explode('|', $k);
                if ($v == 1 && !in_array($conceptCode, $assignedConcepts)) {
                    $add[] = array('ConveniosConcepto' => array(
                        'convenio_id'  => $this->data['ConveniosConcepto']['convenio_id'],
                        'concepto_id'   => $conceptId));
                } elseif ($v == 0 && in_array($conceptCode, $assignedConcepts)) {
                    $del[] = $conceptId;
                }
            }

            if (!empty($add)) {
                $this->ConveniosConcepto->saveAll($add);
            }
            if (!empty($del)) {
                $this->ConveniosConcepto->deleteAll(array(
                    'convenio_id'  => $this->data['ConveniosConcepto']['convenio_id'],
                    'concepto_id'   => $del));
            }
            $this->Session->setFlash('La operacion se realizo con exito.', 'ok');
            $this->redirect(array('controller' => 'convenios', 'action' => 'index'));
        } else {
            return parent::save();
        }
    }

/**
* Permite realizar un add mediante tablas fromto.
*/
    function add_rapido() {

        if (!empty($this->passedArgs['ConveniosConcepto.convenio_id'])) {
            $agreement = $this->__getAssignedConcepts($this->passedArgs['ConveniosConcepto.convenio_id']);
            $assignedConcepts = Set::extract('/Concepto/codigo', $agreement);
            $concepts = $this->ConveniosConcepto->Concepto->find('all',
                array(  'recursive'  => -1,
                        'order'      => array('Concepto.nombre')));

            $this->set('agreement', $agreement);
            $this->set('concepts', $concepts);
            $this->set('assignedConcepts', $assignedConcepts);
        } else {
            $this->Session->setFlash('Debe seleccionar un Empleador.', 'error');
            $this->History->goBack(2);
        }
    }


    function __getAssignedConcepts($agreementId, $extract = false) {
        $this->ConveniosConcepto->Convenio->contain(array('Concepto'));
        $agreement = $this->ConveniosConcepto->Convenio->findById($agreementId);
        if ($extract === true) {
            return Set::extract('/Concepto/codigo', $agreement);
        } else {
            return $agreement;
        }
    }
}
?>
