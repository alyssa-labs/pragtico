<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las ausencias.
 * Las ausencias son cuando un trabajador no se presenta a trabajar a un empleador (una relacion laboral).
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1287 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-04-26 11:03:10 -0300 (lun 26 de abr de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las ausencias.
 * Se refiere a cuando un trabajador no se presenta a trabajar para con un empleador (una relacion laboral).
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class AusenciasController extends AppController {


    var $paginate = array(
        'order' => array(
            'Ausencia.desde' => 'desc'
        )
    );

    var $helpers = array('Documento');

    function reporte_ausencias_liquidadas() {
        
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            $conditions['(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Liquidacion.empleador_id'] = $this->data['Condicion']['Bar-empleador_id'];
            }

            if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
                $period = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
                $conditions['Liquidacion.ano'] = $period['ano'];
                $conditions['Liquidacion.mes'] = $period['mes'];
            }
            
            $conditions['Liquidacion.tipo'] = 'Normal';
            $conditions['Liquidacion.estado'] = 'Confirmada';

            $this->Ausencia->AusenciasSeguimiento->Liquidacion->Behaviors->detach('Permisos');
            $this->Ausencia->AusenciasSeguimiento->Liquidacion->LiquidacionesDetalle->Behaviors->detach('Permisos');
            $this->Ausencia->AusenciasSeguimiento->Behaviors->detach('Permisos');

            $r = array();
            foreach ($this->Ausencia->AusenciasSeguimiento->find('all', array(
                'contain'      => array(
                    'Liquidacion'   => array('order' => 'Liquidacion.empleador_nombre',
                            'LiquidacionesDetalle'),
                    'Ausencia'      => array('order' => array('Ausencia.relacion_id'),
                            'AusenciasMotivo',
                            'Relacion' => array('Empleador', 'Trabajador'))),
                'conditions'    => array(
                    'AusenciasSeguimiento.liquidacion_id' => array_unique(
                        Set::extract('/Liquidacion/id',
                            $this->Ausencia->AusenciasSeguimiento->Liquidacion->find('all',
                                array(  'recursive'     => -1,
                                        'fields'        => array('Liquidacion.id'),
                                        'conditions'    => $conditions))))))) as $detail) {

                if (empty($r[$detail['Ausencia']['Relacion']['id']])) {
                    $r[$detail['Ausencia']['Relacion']['id']]['employer'] = $detail['Ausencia']['Relacion']['Empleador']['nombre'];
                    $r[$detail['Ausencia']['Relacion']['id']]['cuil'] = $detail['Ausencia']['Relacion']['Trabajador']['cuil'];
                    $r[$detail['Ausencia']['Relacion']['id']]['last_name'] = $detail['Ausencia']['Relacion']['Trabajador']['apellido'];
                    $r[$detail['Ausencia']['Relacion']['id']]['name'] = $detail['Ausencia']['Relacion']['Trabajador']['nombre'];
                    $r[$detail['Ausencia']['Relacion']['id']]['lines'] = 0;
                }


                if (empty($r[$detail['Ausencia']['Relacion']['id']][$detail['Ausencia']['AusenciasMotivo']['tipo']])) {
                    $r[$detail['Ausencia']['Relacion']['id']]['lines']++;
                    $r[$detail['Ausencia']['Relacion']['id']][$detail['Ausencia']['AusenciasMotivo']['tipo']]['days'] = 0;
                    $r[$detail['Ausencia']['Relacion']['id']][$detail['Ausencia']['AusenciasMotivo']['tipo']]['amount'] = 0;
                }

                $conceptCode = null;
                $tmpName = 'ausencias_' . strtolower($detail['Ausencia']['AusenciasMotivo']['tipo']);
                $conceptCode[] = $tmpName;
                if ($tmpName === 'ausencias_accidente') {
                    $conceptCode[] = $tmpName . '_art';
                }
                foreach ($detail['Liquidacion']['LiquidacionesDetalle'] as $d) {
                    if (in_array($d['concepto_codigo'], $conceptCode)
                        && empty($receiptsToSkip[$detail['Liquidacion']['id']][$d['concepto_codigo']])) {
                        $r[$detail['Ausencia']['Relacion']['id']][$detail['Ausencia']['AusenciasMotivo']['tipo']]['amount'] += $d['valor'];
                        $r[$detail['Ausencia']['Relacion']['id']][$detail['Ausencia']['AusenciasMotivo']['tipo']]['days'] += $d['valor_cantidad'];
                        $receiptsToSkip[$detail['Liquidacion']['id']][$d['concepto_codigo']] = true;
                    }
                }
            }

            $this->set('data', $r);
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }

    
    function index() {
        if (!empty($this->data['Condicion']['AusenciasSeguimiento-estado'])) {
            
            $this->Paginador->setWhiteList('AusenciasSeguimiento-estado');
            $this->Paginador->setCondition(array('Ausencia.id' => array_unique(Set::extract('/AusenciasSeguimiento/ausencia_id', $this->Ausencia->AusenciasSeguimiento->find('all', array(  'recursive' => -1,
                    'conditions' => array(
                        'AusenciasSeguimiento.estado' => $this->data['Condicion']['AusenciasSeguimiento-estado'])))))));
            //unset($this->data['Condicion']['AusenciasSeguimiento-estado']);
        }
        return parent::index();
    }

    
/**
* Permite confirmar las ausencias.
*/
	function confirmar() {
		$ids = $this->Util->extraerIds($this->data['seleccionMultiple']);
		if (!empty($ids)) {
			if ($this->Ausencia->AusenciasSeguimiento->updateAll(array("estado" => "'Confirmado'"), array("AusenciasSeguimiento.ausencia_id"=>$ids))) {
				$this->Session->setFlash("Se confirmaron correctamente las ausencias seleccionadas.", "ok");
			}
			else {
				$this->Session->setFlash("Ocurrio un error al intentar confirmar las ausencias.", "error");
			}
		}
		$this->redirect("index");
	}


/**
 * seguimientos.
 * Muestra via desglose el seguimiento de la ausencia.
 */
	function seguimientos($id) {
		$this->Ausencia->contain(array('AusenciasSeguimiento' => array('order' => 'AusenciasSeguimiento.id')));
		$this->data = $this->Ausencia->read(null, $id);
	}

    
/**
 * trabajadores.
 * Muestra via desglose los trabajdores.
 */
    function trabajadores($id) {
        $this->Ausencia->contain(array('Relacion.Trabajador.Localidad'));
        $this->data = $this->Ausencia->read(null, $id);
    }

}
?>