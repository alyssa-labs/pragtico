<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la facturacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1423 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a la facturacion.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class FacturasController extends AppController {

    var $paginate = array(
        'order' => array(
            'Factura.ano'       => 'desc',
            'Factura.mes'       => 'desc',
            'Factura.periodo'   => 'desc'
        )
    );

	var $helpers = array('Documento');


    function reporte_evolucion_facturacion() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            $conditions['(Factura.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Factura.empleador_id'] = $this->data['Condicion']['Bar-empleador_id'];
            }

            if (!empty($this->data['Condicion']['Bar-periodo_largo_partida']) && !empty($this->data['Condicion']['Bar-periodo_largo_final'])) {
                $periodFrom = $this->Util->format($this->data['Condicion']['Bar-periodo_largo_partida'], 'periodo');
                $periodTo = $this->Util->format($this->data['Condicion']['Bar-periodo_largo_final'], 'periodo');
                $conditions['Factura.ano'] = array($periodFrom['ano'], $periodTo['ano']);
                $conditions['Factura.mes'] = array($periodFrom['mes'], $periodTo['mes']);
                if (!is_array($periodFrom['periodo'])) {
                    $conditions['Factura.periodo'] = array($periodFrom['periodo'], $periodTo['periodo']);
                } else {
                    $conditions['Factura.periodo'] = array_merge($periodFrom['periodo'], $periodTo['periodo']);
                }
            } else {
                $this->Session->setFlash('Debe seleccionar los dos periodos para la comparacion.', 'error');
            }
            $conditions['Factura.estado'] = 'Confirmada';
                    
            $this->Factura->Behaviors->detach('Permisos');
            $this->Factura->contain(array('Empleador', 'Area'));
            $data = array();
            $queryData = array(
                'conditions'    => $conditions,
                'fields'        => array(
                    'Empleador.cuit',
                    'Empleador.nombre',
                    'Area.nombre',
                    'Factura.ano',
                    'Factura.mes',
                    'Factura.periodo',
                    'SUM(Factura.total) AS total'),
                'order'         => array(
                    'Empleador.nombre',
                    'Area.nombre',
                    'Factura.ano',
                    'Factura.mes',
                    'Factura.periodo'),                    
                'group'         => array(
                    'Factura.empleador_id',
                    'Factura.area_id',
                    'Factura.ano',
                    'Factura.mes',
                    'Factura.periodo'));
            
            if (is_array($periodFrom['periodo'])) {
                unset($queryData['fields'][5]);
                unset($queryData['order'][4]);
                unset($queryData['group'][4]);
            }
            
            foreach ($this->Factura->find('all', $queryData) as $v) {
                $data[$v['Empleador']['cuit']][$v['Area']['nombre']][$this->Util->format($v['Factura'], 'periodo')] = $v;
            }

            $this->set('data', $data);
            $this->set('periods', array(
                $this->data['Condicion']['Bar-periodo_largo_partida'],
                $this->data['Condicion']['Bar-periodo_largo_final']));
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }


	function reporte_facturacion($facturaId, $state = 'Confirmada') {

        if ($state == 'Sin Confirmar') {
            $this->Factura->setSecurityAccess('readOwnerOnly');
        }

		$records = $this->Factura->report($facturaId);
		if (empty($records)) {
			$this->Session->setFlash('No se han encontrado facturas para el periodo seleccioando segun los criterios especificados.', 'error');
		} else {
			$this->set('data', $records);
		}
	}
	

	function asignar_numero($facturaId, $number) {
		if ($this->Factura->updateAll(array('Factura.numero' => $number), array('Factura.id' => $facturaId))) {
			$this->set('data', 'ok');
		} else {
			$this->set('data', 'er');
		}
		$this->render('..' . DS . 'elements' . DS . 'string');
	}

	function prefacturar() {

        $condiciones = array();
        
        if (!empty($this->data['Formulario']['accion'])) {
            
            $data = $this->data;
            if ($this->data['Formulario']['accion'] === 'generar') {

                $period = $this->Util->format($this->data['Condicion']['Bar-periodo_completo'], 'periodo');
                
                if ($period === false) {
                    $this->Session->setFlash(__('Must enter a valid period', true), 'error');
                    $this->History->goBack();
                }

                $this->data['Condicion']['Liquidacion-ano'] = $period['ano'];
                $this->data['Condicion']['Liquidacion-mes'] = $period['mes'];
                if ($period['periodo'] !== 'M') {
                    $this->data['Condicion']['Liquidacion-periodo'] = $period['periodo'];
                }

                $groupId = null;
                if (!empty($this->data['Condicion']['Liquidacion-grupo_id'])) {
                    $condiciones['(Liquidacion.group_id & ' . $this->data['Condicion']['Liquidacion-grupo_id'] . ') >'] = 0;
                    $groupId = $this->data['Condicion']['Liquidacion-grupo_id'];
                    unset($this->data['Condicion']['Liquidacion-grupo_id']);
                }
                $condiciones = array_merge($condiciones, $this->Paginador->generarCondicion($this->data));

                /** Delete user's unconfirmed Invoices */
                if (!$this->Factura->deleteAll(array(
                    'Factura.user_id'   => User::get('/Usuario/id'),
                    'Factura.estado'    => 'Sin Confirmar'), true, false)) {
                    $this->Session->setFlash(__('Can\'t delete previous invoices. Call Administrator', true), 'error');
                    $this->redirect(array('action' => 'prefecturar'));
                }


                if (!$this->Factura->getInvoice($condiciones, $groupId)) {
                    $this->Session->setFlash(__('Can\'t create invoices. Check search criterias', true), 'error');
                    $resultados['registros'] = array();
                }
                $condiciones = array();
            } elseif ($this->data['Formulario']['accion'] === 'limpiar') {
                $data = array();
            } elseif ($this->data['Formulario']['accion'] === 'buscar') {

                $period = $this->Util->format($this->data['Condicion']['Bar-periodo_completo'], 'periodo');
                if ($period !== false) {
                    $this->data['Condicion']['Factura-fecha__desde'] = $this->Util->format($period['desde'], 'date');
                    $this->data['Condicion']['Factura-fecha__hasta'] = $this->Util->format($period['hasta'], 'date');
                }

                if (!empty($this->data['Condicion']['Liquidacion-empleador_id'])) {
                    $this->data['Condicion']['Factura-empleador_id'] = $this->data['Condicion']['Liquidacion-empleador_id'];
                    unset($this->data['Condicion']['Relacion-empleador_id']);
                }
                unset($this->data['Condicion']['Liquidacion-empleador_id']);
                unset($this->data['Condicion']['Liquidacion-grupo_id']);
                unset($this->data['Condicion']['Liquidacion-estado']);
                unset($this->data['Condicion']['Liquidacion-tipo']);
                $condiciones = $this->Paginador->generarCondicion($this->data);
            }
        }

        $this->Factura->setSecurityAccess('readOwnerOnly');
        $this->paginate = array_merge($this->paginate, array(
            'limit'     => 15,
            'contain'   =>array('Empleador', 'Area')));
        $this->Paginador->setCondition(array_merge($condiciones, array('Factura.estado' => 'Sin Confirmar')), true);
        $resultados = $this->Paginador->paginar();
		
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'limpiar') {
            $this->data = array();
        } elseif (!empty($data)) {
			$this->data = $data;
		}

		$this->set('registros', $resultados);
		$this->set('grupos', User::getUserGroups());
	}
	
/*
	function index() {
		if (!empty($this->data['Condicion']['Liquidacion-periodo_completo'])) {
			$periodo = $this->Util->format($this->data['Condicion']['Liquidacion-periodo_completo'], 'periodo');
			if (!empty($periodo)) {
				$this->data['Condicion']['Liquidacion-ano'] = $periodo['ano'];
				$this->data['Condicion']['Liquidacion-mes'] = $periodo['mes'];
				$this->data['Condicion']['Liquidacion-periodo'] = $periodo['periodo'];
				unset($this->data['Condicion']['Liquidacion-periodo_completo']);
			}
		}
		parent::index();
	}


	function beforeRender() {
		if ($this->action === 'index') {
			$filters = $this->Session->read('filtros.' . $this->name . '.' . $this->action);
			if (!empty($filters['condiciones']['Liquidacion.ano']) && !empty($filters['condiciones']['Liquidacion.mes']) && !empty($filters['condiciones']['Liquidacion.periodo like'])) {
				$this->data['Condicion']['Liquidacion-periodo_completo'] = $filters['condiciones']['Liquidacion.ano'] . $filters['condiciones']['Liquidacion.mes'] . str_replace('%', '', $filters['condiciones']['Liquidacion.periodo like']);
			}
		}
	}
*/
	
/**
 * Detalles
 * Muestra via desglose los detalles de una factura.
 */
	function detalles($id) {
        $this->Factura->setSecurityAccess('readOwnerOnly');
		$this->Factura->contain('FacturasDetalle.Coeficiente');
		$this->data = $this->Factura->read(null, $id);
	}


	function confirmar() {
		$ids = $this->Util->extraerIds($this->data['seleccionMultiple']);
		
		if (!empty($ids)) {
			$this->Factura->unbindModel(array('belongsTo' => array('Empleador')));
			if ($this->Factura->updateAll(array('Factura.permissions' => "'288'", 'Factura.estado' => "'Confirmada'"), array('Factura.id' => $ids, 'Factura.confirmable' => 'Si'))) {
				$this->Session->setFlash('Las facturas seleccionadas se confirmaron correctamente', 'ok');
			} else {
				$this->Session->setFlash('No pudieron confirmarse las facturas. Verifique.', 'error');
			}
		}
		$this->History->goBack();
	}
	
	function index() {
		$this->paginate['conditions'] = array('Factura.estado' => 'Confirmada');

		if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
			$period = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');

			$this->paginate['conditions']['Factura.ano'] = $period['ano'];
			$this->paginate['conditions']['Factura.mes'] = $period['mes'];
			if ($period['periodo'] !== 'M') {
				$this->paginate['conditions']['Factura.periodo'] = $period['periodo'];
			}
		}

		parent::index();
	}

}
?>