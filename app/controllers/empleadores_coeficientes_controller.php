<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al manejo de los coefientes
 * relacionados a cada empleador.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1363 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-16 07:02:05 -0300 (mié 16 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al manejo de los coefientes
 * relacionados a cada empleador.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class EmpleadoresCoeficientesController extends AppController {


    var $helpers = array('Documento');
    
    function reporte_coeficientes() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            $conditions['(Empleador.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Empleador.id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
            }

            $data = $this->EmpleadoresCoeficiente->Empleador->find('all', array(
				'contain'		=> array('Area.Coeficiente', 'Coeficiente' => array('order' => 'Coeficiente.nombre')),
				'conditions' 	=> $conditions));

            if (!empty($this->data['Condicion']['Bar-solo_con_valor']) && $this->data['Condicion']['Bar-solo_con_valor'] == 'No') {
                $coeficientes = Set::combine(
                    $this->EmpleadoresCoeficiente->Coeficiente->find('all', array(
                        'recursive' => -1,
                        'order' 	=> 'Coeficiente.nombre')),
                            '{n}.Coeficiente.id', '{n}.Coeficiente');

                $ids = array_keys($coeficientes);
                foreach ($data as $k => $record) {
                    foreach (array_diff($ids, Set::extract('/Coeficiente/id', $record)) as $missingId) {
                        $data[$k]['Coeficiente'][] = $coeficientes[$missingId];
                    }
                }
            }


			$d = array();
			foreach ($data as $k => $record) {

				foreach ($record['Area'] as $k1 => $area) {
					if (empty($area['Coeficiente'])) {
						$data[$k]['Area'][$k1]['Coeficiente'] = $record['Coeficiente'];
					} else {
						$coeficientesEmpleador = Set::combine($record['Coeficiente'], '{n}.id', '{n}');
						$coeficientesArea = Set::combine($data[$k]['Area'][$k1]['Coeficiente'], '{n}.id', '{n}');
						//$data[$k]['Area'][$k1]['Coeficiente'] = array_merge($record['Coeficiente'], $data[$k]['Area'][$k1]['Coeficiente']);
						foreach ($coeficientesArea as $kTmp => $vTmp) {
							if (!empty($coeficientesEmpleador[$kTmp])) {
								$tmp = $vTmp['AreasCoeficiente'];
								$coeficientesEmpleador[$kTmp]['AreasCoeficiente'] = $tmp;
							} else {
								$coeficientesEmpleador[$kTmp] = $vTmp;
							}
						}
						$data[$k]['Area'][$k1]['Coeficiente'] = $coeficientesEmpleador;
					}
				}

			}

            $this->set('data', $data);
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }


    function add_rapido() {
        $empleadoresCoeficientes = Set::combine($this->EmpleadoresCoeficiente->find('all', array(
          'recursive'   => -1,
          'conditions'  => array('EmpleadoresCoeficiente.empleador_id' => $this->params['named']['EmpleadoresCoeficiente.empleador_id']))), '{n}.EmpleadoresCoeficiente.coeficiente_id', '{n}.EmpleadoresCoeficiente');
        foreach ($this->EmpleadoresCoeficiente->Coeficiente->find('all', array(
            'order' => array('Coeficiente.tipo', 'Coeficiente.nombre'))) as $v) {
            $v['EmpleadoresCoeficiente']['id'] = null;
            $v['EmpleadoresCoeficiente']['porcentaje'] = 0;
            if (isset($empleadoresCoeficientes[$v['Coeficiente']['id']])) {
                $v['EmpleadoresCoeficiente']['id'] = $empleadoresCoeficientes[$v['Coeficiente']['id']]['id'];
                $v['EmpleadoresCoeficiente']['porcentaje'] = $empleadoresCoeficientes[$v['Coeficiente']['id']]['porcentaje'];
            }
            $coefientes[] = $v;
        }
        $this->set('coefientes', $coefientes);
        $this->EmpleadoresCoeficiente->Empleador->recursive = -1;
        $this->set('empleador', $this->EmpleadoresCoeficiente->Empleador->findById($this->params['named']['EmpleadoresCoeficiente.empleador_id']));
    }

    function save() {

        if (!empty($this->data['Form']['accion']) && $this->data['Form']['accion'] === 'grabar') {

            foreach ($this->data['EmpleadoresCoeficiente'] as $k => $v) {
                if (!empty($v['delete']) && !empty($v['id'])) {
                    $delete[] = $v['id'];
                } elseif (!empty($v['porcentaje'])) {
                    $data[] = $v;
                }
            }

            if (!empty($delete)) {
                $this->EmpleadoresCoeficiente->deleteAll(array('EmpleadoresCoeficiente.id' => $delete));
            }
            $this->data['Form']['accion'] = 'grabar';
        }

        if (!empty($data)) {
            return parent::save($data);
        } else {
            return parent::save();
        }
    }
    
}
?>