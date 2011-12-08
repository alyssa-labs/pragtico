<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la relacion que existe
 * entre los empleadores y los conceptos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1125 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-11-10 16:45:04 -0300 (mar 10 de nov de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a la relacion que existe
 * entre los empleadores y los conceptos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class EmpleadoresConceptosController extends AppController {

    function save() {
        if (!empty($this->data['Form']['tipo'])
            && $this->data['Form']['tipo'] == 'addRapido'
            && !empty($this->data['EmpleadoresConcepto']['empleador_id'])) {

            $assignedConcepts = $this->__getAssignedConcepts($this->data['EmpleadoresConcepto']['empleador_id'], true);

            foreach ($this->data['Concepto'] as $k => $v) {
                list($conceptId, $conceptCode) = explode('|', $k);
                if ($v == 1 && !in_array($conceptCode, $assignedConcepts)) {
                    $add[] = array('EmpleadoresConcepto' => array(
                        'empleador_id'  => $this->data['EmpleadoresConcepto']['empleador_id'],
                        'concepto_id'   => $conceptId));
                } elseif ($v == 0 && in_array($conceptCode, $assignedConcepts)) {
                    $del[] = $conceptId;
                }
            }

            if (!empty($add)) {
                $this->EmpleadoresConcepto->saveAll($add);
            }
            if (!empty($del)) {
                $this->EmpleadoresConcepto->deleteAll(array(
                    'empleador_id'  => $this->data['EmpleadoresConcepto']['empleador_id'],
                    'concepto_id'   => $del));
            }
            $this->Session->setFlash('La operacion se realizo con exito.', 'ok');
            $this->redirect(array('controller' => 'empleadores', 'action' => 'index'));
        } else {
            return parent::save();
        }
    }

/**
* Permite realizar un add mediante tablas fromto.
*/
    function add_rapido() {

        if (!empty($this->passedArgs['EmpleadoresConcepto.empleador_id'])) {
            $employer = $this->__getAssignedConcepts($this->passedArgs['EmpleadoresConcepto.empleador_id']);
            $assignedConcepts = Set::extract('/Concepto/codigo', $employer);
            $concepts = $this->EmpleadoresConcepto->Concepto->find('all',
                array(  'recursive'  => -1,
                        'order'      => array('Concepto.nombre')));

            $this->set('employer', $employer);
            $this->set('concepts', $concepts);
            $this->set('assignedConcepts', $assignedConcepts);
        } else {
            $this->Session->setFlash('Debe seleccionar un Empleador.', 'error');
            $this->History->goBack(2);
        }
    }


    function __getAssignedConcepts($employerId, $extract = false) {
        $this->EmpleadoresConcepto->Empleador->contain(array('Concepto'));
        $employer = $this->EmpleadoresConcepto->Empleador->findById($employerId);
        if ($extract === true) {
            return Set::extract('/Concepto/codigo', $employer);
        } else {
            return $employer;
        }
    }

}
?>
