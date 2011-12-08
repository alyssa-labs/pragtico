<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la relacion entre roles y acciones.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 762 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-27 16:56:28 -0300 (lun 27 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

/**
 * La clase encapsula la logica de negocio asociada a la relacion entre roles y acciones.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RolesAccionesController extends AppController {

	function save() {
		/**
		* Detecto si viene de un addRapido.
		*/
		if (!empty($this->data['Form']['tipo']) && $this->data['Form']['tipo'] == 'addRapido' && !empty($this->data['RolesAccion']['rol_id'])) {

			if ($this->data['Form']['accion'] === 'grabar') {
				$rolId = $this->data['RolesAccion']['rol_id'];
				unset($this->data['RolesAccion']['rol_id']);
				$this->RolesAccion->begin();
				$this->RolesAccion->deleteAll(array('RolesAccion.rol_id'=>$rolId));
                $saveAll = array();
				foreach ($this->data['RolesAccion'] as $actions) {
                    if (!empty($actions)) {
                        foreach ($actions as $accionId) {
                            $saveAll[] = array('RolesAccion' =>
                                array('rol_id' => $rolId, 'accion_id' => $accionId));
                        }
                    }
				}

                if (!empty($saveAll)) {
                    if ($this->RolesAccion->saveAll($saveAll)) {
                        $this->Session->setFlash('Los cambios se guardaron correctamente.', 'ok');
                        $this->RolesAccion->commit();
                    } else {
                        $this->Session->setFlash('Los cambios no pudieron guardarse.', 'error');
                        $this->RolesAccion->rollback();
                    }
                } else {
                    /** Must commit deleted actions */
                    $this->Session->setFlash('Las acciones se eliminaron correctamente.', 'ok');
                    $this->RolesAccion->commit();
                }
			}
		}
        $this->History->goBack();
	}
	

/**
* Permite realizar un add mediante seleccion multiple en combos.
*/
	function add_rapido() {
		if (!empty($this->params['named']['RolesAccion.rol_id']) && is_numeric($this->params['named']['RolesAccion.rol_id'])) {
			$data = $controladores = $acciones = array();
			$this->RolesAccion->contain('Accion');
			
			$r = $this->RolesAccion->find('all', array('conditions'=>array('RolesAccion.rol_id'=>$this->params['named']['RolesAccion.rol_id'])));
			if (!empty($r)) {
				$r = Set::extract('/Accion/id', $r);
			}
			
			foreach ($this->RolesAccion->Accion->Controlador->find('all') as $controlador) {
				$controladores[$controlador['Controlador']['id']] = $controlador['Controlador']['nombre'];
				foreach ($controlador['Accion'] as $accion) {
					$acciones[$accion['id']] = $accion['nombre'];
					if (!empty($r) && in_array($accion['id'], $r)) {
						$data[$controlador['Controlador']['id']][$accion['id']] = true;
					} else {
						$data[$controlador['Controlador']['id']][$accion['id']] = false;
					}
				}
			}
			$this->set('data', $data);
			$this->set('dataControladores', $controladores);
			$this->set('dataAcciones', $acciones);
			$this->set('rolId', $this->params['named']['RolesAccion.rol_id']);
		} else {
			$this->Session->setFlash('Debe seleccionar un Rol.', 'error');
			$this->History->goBack(2);
		}
	}


}
?>