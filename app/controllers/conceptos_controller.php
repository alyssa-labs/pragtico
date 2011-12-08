<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los conceptos.
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
 * La clase encapsula la logica de negocio asociada a los conceptos.
 *
 * Son los conceptos que daran origen a las liquidaciones.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ConceptosController extends AppController {

    var $paginate = array(
        'order' => array(
            'Concepto.orden' => 'asc'
        )
    );


/**
 * Rangos.
 * Muestra via desglose los rangos del concepto.
 */
	function rangos($id) {
		$this->Concepto->contain(array('ConceptosRango'));
		$this->data = $this->Concepto->read(null, $id);
	}


/**
 * Convenios.
 * Muestra via desglose los convenios colectivos en los que esta incluido el concepto.
 */
	function convenios($id) {
		$this->Concepto->contain(array('Convenio'));
		$this->data = $this->Concepto->read(null, $id);
	}


/**
 * Empleadores.
 * Muestra via desglose los empleadores que tienen asociado al concepto.
 */
	function empleadores($id) {
		$this->Concepto->contain(array('Empleador'));
		$this->data = $this->Concepto->read(null, $id);
	}


/**
 * Relaciones.
 * Muestra via desglose las relaciones laborales que tienen asociado al concepto.
 */
	function relaciones($id) {
		$this->Concepto->contain(array('Relacion.Empleador', 'Relacion.Trabajador'));
		$this->data = $this->Concepto->read(null, $id);
	}

/**
 * Asigna un concepto a todos los trabajadores de un convenio celectivo incluyendo o excluyendo a los ciertos empleadores.
 */
	function manipular_concepto($accion = null) {
		if (!empty($this->params['pass']['0']) && !empty($this->params['named']['concepto_id'])) {
			$this->set('convenios', $this->Concepto->ConveniosConcepto->Convenio->find('list', array('fields' => array('Convenio.id', 'Convenio.nombre'))));
			$this->set('concepto', $this->Concepto->findById($this->params['named']['concepto_id']));
			$this->set('accion', $this->params['pass']['0']);
			$this->set('comportamientos', array('incluir' => 'Incluir', 'excluir' => 'Excluir'));
		} elseif (!empty($this->data['Asignar']['accion']) && !empty($this->data['Asignar']['convenio_id']) && !empty($this->data['Asignar']['concepto_id'])) {
			$accion = $this->data['Asignar']['accion'];
			if ($this->data['Form']['accion'] == 'grabar') {

				$conditions = array();
				/**
				* Si tengo empleadores seleccionados, debo decidir si los incluto o los exluyo a estos.
				*/
				if (!empty($this->data['Asignar']['empleador_id'])) {
					$empleadoresId = explode('**||**', $this->data['Asignar']['empleador_id']);
					if ($this->data['Asignar']['empleador_comportamiento'] == 'incluir') {
						$conditions = array('Relacion.empleador_id' => $empleadoresId);
					} elseif ($this->data['Asignar']['empleador_comportamiento'] == 'excluir') {
						$conditions = array(array('NOT' => array('Relacion.empleador_id' => $empleadoresId)));
					}
				}

				$this->Concepto->ConveniosConcepto->Convenio->ConveniosCategoria->recursive = -1;
				$conveniosCategoria = $this->Concepto->ConveniosConcepto->Convenio->ConveniosCategoria->find('list', array('conditions' => array('ConveniosCategoria.convenio_id' => $this->data['Asignar']['convenio_id'])));
				$this->Concepto->RelacionesConcepto->Relacion->contain();
				$conditions = array_merge($conditions, array('Relacion.convenios_categoria_id' => array_values($conveniosCategoria)));
				$relaciones = $this->Concepto->RelacionesConcepto->Relacion->find('list', array('fields' => array('Relacion.id'), 'conditions' => $conditions));

				$c = $this->Concepto->RelacionesConcepto->Concepto->agregarQuitarConcepto($relaciones, array($this->data['Asignar']['concepto_id']), array('accion' => $accion));
				if ($c > 0) {
					$this->Session->setFlash('El concepto se pudo ' . $accion . ' correctamente a ' . $c . ' trabajadores.', 'ok');
				} else {
					$this->Session->setFlash('El concepto no se lo pudo ' . $accion . ' a ningun trabajador. Puede que ya haya estado asignado/quitado.', 'warning');
				}
			}
			$this->redirect('index');
		}
	}

}
?>