<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los conceptos propios
 * de las relaciones laborales que existen entre los trabajador y los empleadores .
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1019 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-24 16:55:57 -0300 (jue 24 de sep de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los conceptos de las relaciones laborales.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RelacionesConceptosController extends AppController {

    function save() {
        if (!empty($this->data['Form']['tipo']) && $this->data['Form']['tipo'] == 'addRapido' && !empty($this->data['RelacionesConcepto']['relacion_id'])) {

            $assignedConcepts = Set::extract('/Concepto/codigo',
                $this->RelacionesConcepto->find('all', array(
                    'contain'       => 'Concepto',
                    'conditions'    => array(
                        'RelacionesConcepto.relacion_id' => $this->data['RelacionesConcepto']['relacion_id']))));

            foreach ($this->data['Concepto'] as $k => $v) {
                list($conceptId, $conceptCode) = explode('|', $k);
                if ($v == 1) {
                    if (!in_array($conceptCode, $assignedConcepts)) {
                        $add[] = array('RelacionesConcepto' => array(
                            'relacion_id' => $this->data['RelacionesConcepto']['relacion_id'],
                            'concepto_id' => $conceptId));
                    }
                } else {
                    if (in_array($conceptCode, $assignedConcepts)) {
                        $del[] =$conceptId;
                    }
                }
            }

            if (!empty($add)) {
                $this->RelacionesConcepto->saveAll($add);
            }           
            if (!empty($del)) {
                $this->RelacionesConcepto->deleteAll(array(
                    'relacion_id' => $this->data['RelacionesConcepto']['relacion_id'],
                    'concepto_id' => $del));
            }
            $this->Session->setFlash('La operacion se realizo con exito.', 'ok');
            $this->redirect(array('controller' => 'relaciones', 'action' => 'index'));
        } else {
            return parent::save();
        }
    }

/**
* Permite realizar un add mediante tablas fromto.
*/
	function add_rapido() {

		if (!empty($this->passedArgs['RelacionesConcepto.relacion_id'])) {
			$this->RelacionesConcepto->Relacion->contain(array("ConveniosCategoria", "Trabajador", "Empleador", "RelacionesConcepto.Concepto"));
			$relacion = $this->RelacionesConcepto->Relacion->findById($this->passedArgs['RelacionesConcepto.relacion_id']);

            $assignedConcepts = Set::extract('/RelacionesConcepto/Concepto/codigo', $relacion);
			$concepts = $this->RelacionesConcepto->Concepto->find('all',
				array(	'recursive'	 => -1,
						'order'		 => array('Concepto.nombre')));
			
			$this->set('relacion', $relacion);
			$this->set('concepts', $concepts);
			$this->set('assignedConcepts', $assignedConcepts);
		} else {
			$this->Session->setFlash('Debe seleccionar una relacion.', 'error');
			$this->History->goBack(2);
		}
	}
    
}
?>