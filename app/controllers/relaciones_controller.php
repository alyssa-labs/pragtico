<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las relaciones
 * laborales que existen entre los trabajador y los empleadores .
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since			pragtico v 1.0.0
 * @version         $Revision: 1345 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-04 16:17:50 -0300 (vie 04 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las relaciones laborales.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class RelacionesController extends AppController {

    var $helpers = array('Documento');


	function reingreso() {
		if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] == 'confirmar' && !empty($this->data['Relacion']['ingreso']) && !empty($this->data['Relacion']['id'])) {
			$this->Relacion->Behaviors->detach('Permisos');
			$this->data['Relacion']['estado'] = 'Activa';
			$this->data['Relacion']['permissions'] = 496;
			if ($this->Relacion->save(array('Relacion' => $this->data['Relacion']), false)) {
				$this->Session->setFlash('El reingreso se completo correctamente.', 'ok');
			} else {
				$this->Session->setFlash('No fue posible guardar el reingreso.', 'error');
			}
			$this->History->goBack();
		} else {
			$this->Relacion->contain(array('Empleador', 'Trabajador'));
			$this->data = $this->Relacion->findById($this->params['named']['Relacion.id']);
		}
	}


    function archivo_bimestral_ministerio_trabajo() {

        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            switch ($this->data['Condicion']['Bar-bimestre']) {
                case 1:
                    $month = array(1, 2);
                break;
                case 2:
                    $month = array(3, 4);
                break;
                case 3:
                    $month = array(5, 6);
                break;
                case 4:
                    $month = array(7, 8);
                break;
                case 5:
                    $month = array(9, 10);
                break;
                case 6:
                    $month = array(11, 12);
                break;
            }

            $conditions = null;
            $this->Relacion->Liquidacion->Behaviors->detach('Permisos');
            $conditions['Relacion.id'] =
                array_unique(
                    Set::extract('/Liquidacion/relacion_id',
                        $this->Relacion->Liquidacion->find('all', array(
                            'recursive'     => -1,
                            'conditions'    => array(
                                'Liquidacion.mes'       => $month,
                                'Liquidacion.ano'       => $this->data['Condicion']['Bar-ano'],
                                '(Liquidacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'  => 0)))));
            $conditions['(Relacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;

            $data = $this->Relacion->find('all', array(
                'contain'       => array(
					'RelacionesHistorial' => array(
						'order' 		=> 'RelacionesHistorial.id DESC',
						'conditions' 	=> array(
							'RelacionesHistorial.estado' => array('Liquidado', 'Confirmado'))),
					'Trabajador',
					'Empleador',
					'Area' => 'Provincia',
					'ConveniosCategoria' => 'Convenio'),
                'conditions'    => $conditions));

            if (!empty($data)) {

                App::import('Vendor', 'utils', 'pragmatia');
                $lineas = array();
                $groupParams = User::getGroupParams($this->data['Condicion']['Bar-grupo_id']);

                $linea = null;
                $c = 1;
                $linea[$c++] = 'HR';
                $linea[$c++] = Utils::normalizeText($groupParams['ministerio_trabajo_legajo'], 4);
                $linea[$c++] = Utils::normalizeText(str_replace('-', '', $groupParams['cuit']), 11);
                $linea[$c++] = Utils::normalizeText($groupParams['nombre_fantasia'], 50);
                $linea[$c++] = Utils::normalizeText($groupParams['direccion'], 30);
                $linea[$c++] = Utils::normalizeText($groupParams['ciudad'], 20);
                $linea[$c++] = Utils::normalizeText($groupParams['codigo_postal'], 8);
                $linea[$c++] = Utils::normalizeText($groupParams['ministerio_trabajo_provincia'], 2, 'number');
                $linea[$c++] = $this->data['Condicion']['Bar-bimestre'];
                $linea[$c++] = $this->data['Condicion']['Bar-ano'];
                $linea[$c++] = '00';
                $linea[$c++] = str_repeat(' ', 378);
                $lineas[] = implode('', $linea);


                foreach ($data as $r) {
                    $linea = null;
                    $c = 1;
                    
                    if (empty($historico[$r['ConveniosCategoria']['id']])) {
                        $tmp = $this->Relacion->ConveniosCategoria->ConveniosCategoriasHistorico->find('first',
                            array(
                                'recursive'     => -1,
                                'checkSecurity' => false,
                                'order'         => 'ConveniosCategoriasHistorico.id DESC',
                                'conditions'    =>
                                    array('ConveniosCategoriasHistorico.convenios_categoria_id' => $r['ConveniosCategoria']['id'])));

                        $historico[$r['ConveniosCategoria']['id']] = $tmp['ConveniosCategoriasHistorico']['costo'];
                    }
                    
                    $linea[$c++] = 'DR';
                    $linea[$c++] = Utils::normalizeText($r['Trabajador']['nombre'], 50);
                    $linea[$c++] = Utils::normalizeText($r['Trabajador']['apellido'], 50);
                    $linea[$c++] = Utils::normalizeText(str_replace('-', '', $r['Trabajador']['cuil']), 11);
                    $linea[$c++] = Utils::normalizeText($r['ConveniosCategoria']['nombre'], 30);
                    $linea[$c++] = Utils::normalizeText($r['ConveniosCategoria']['Convenio']['numero'], 20);
                    $linea[$c++] = Utils::normalizeText(($r['Relacion']['basico'] > 0)?$r['Relacion']['basico']:$historico[$r['ConveniosCategoria']['id']], 8, 'number');
                    if ($r['ConveniosCategoria']['jornada'] == 'Mensual') {
                        $linea[$c++] = 1;
                    } else {
                        $linea[$c++] = 2;
                    }
                    $linea[$c++] = Utils::normalizeText($r['Empleador']['nombre'], 50);
                    $linea[$c++] = Utils::normalizeText(str_replace('-', '', $r['Empleador']['cuit']), 11);
                    $linea[$c++] = Utils::normalizeText($r['Area']['direccion'], 30);
                    $linea[$c++] = Utils::normalizeText($r['Area']['ciudad'], 20);
                    $linea[$c++] = Utils::normalizeText($r['Area']['codigo_postal'], 8);
                    $linea[$c++] = Utils::normalizeText((!empty($r['Area']['Provincia']['codigo'])?$r['Area']['Provincia']['codigo']:'0'), 2, 'number');
                    $linea[$c++] = Utils::normalizeText($this->Util->format($r['Relacion']['ingreso'], array('type' => 'date', 'format' => 'dmY')), 8);
					if ($r['Relacion']['estado'] == 'Historica') {
                    	$linea[$c++] = Utils::normalizeText($this->Util->format($r['RelacionesHistorial'][0]['fin'], array('type' => 'date', 'format' => 'dmY')), 8);
					} else {
						$linea[$c++] = str_repeat(' ', 8);
					}
                    $linea[$c++] = '0';
                    $linea[$c++] = str_repeat(' ', 134);
                    $lineas[] = implode('', $linea);
                }

                $linea = null;
                $c = 1;
                $linea[$c++] = 'FR';
                $linea[$c++] = Utils::normalizeText(str_replace('-', '', $groupParams['cuit']), 11);
                $linea[$c++] = Utils::normalizeText(count($data), 50, 'number');
                $linea[$c++] = str_repeat(' ', 449);
                $lineas[] = implode('', $linea);
                
                $this->set('archivo', array(
                    'contenido' => implode("\r\n", $lineas),
                    'nombre'    => 'BIMESTRAL_MT_' . $this->data['Condicion']['Bar-ano'] . '-' . $this->data['Condicion']['Bar-bimestre'] . '.txt'));
                $this->render('..' . DS . 'elements' . DS . 'txt', 'txt');
            } else {
                $this->Session->setFlash('No se encontraron registros para generar el archivo.', 'error');
                $this->History->goBack();
            }
        }
    }

    function reporte_relaciones_activas() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {


			$conditions['Relacion.estado <>'] = 'Historica';

            if (!empty($this->data['Condicion']['Bar-grupo_id'])) {
                $conditions['(Relacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            }
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Relacion.empleador_id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
            }

			if (!empty($this->data['Condicion']['Bar-state'])) {
				$conditions['Relacion.estado'] = $this->data['Condicion']['Bar-state'];
			}


            if (!empty($this->data['Condicion']['Bar-con_liquidacion_periodo']) && $this->data['Condicion']['Bar-con_liquidacion_periodo'] === 'Si') {

				if (!empty($this->data['Condicion']['Bar-periodo_largo'])) {
					$period = $this->Util->format($this->data['Condicion']['Bar-periodo_largo'], 'periodo');
				} else {
					$this->Session->setFlash('Debe ingresar el periodo.', 'error');
					$this->History->goBack();
				}

                $this->Relacion->Liquidacion->Behaviors->detach('Permisos');
                $conditions['Relacion.id'] = Set::extract('/Liquidacion/relacion_id',
					$this->Relacion->Liquidacion->find('all', array(
						'recursive' => -1,
						'group' 	=> 'Liquidacion.relacion_id',
						'conditions' => array(
							'Liquidacion.ano' => $period['ano'],
							'Liquidacion.mes' => $period['mes']))));
            }

            if (!empty($contain)) {
				$this->Relacion->RelacionesHistorial->contain($contain);
			}

			$this->Relacion->Behaviors->detach('Permisos');
            $this->set('data', $this->Relacion->find('all', array(
				'contain'		=> array(
					'Trabajador',
					'Empleador',
					'Area'),
				'conditions' 	=> $conditions,
				'order' 		=> array('Trabajador.apellido', 'Trabajador.nombre'))));

            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }


    function reporte_relaciones_historicas() {
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

			$conditions['RelacionesHistorial.liquidacion_final'] = array('Si', 'No');
			$conditions['RelacionesHistorial.estado'] = 'Confirmado';


			if (!empty($this->data['Condicion']['Bar-desde'])) {
				$conditions['RelacionesHistorial.fin >='] = $this->data['Condicion']['Bar-desde'];
			}

			if (!empty($this->data['Condicion']['Bar-hasta'])) {
				$conditions['RelacionesHistorial.fin <='] = $this->data['Condicion']['Bar-hasta'];
			}

            if (!empty($this->data['Condicion']['Bar-grupo_id'])) {
                $conditions['(Relacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;
            }
            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $conditions['Relacion.empleador_id'] = explode('**||**', $this->data['Condicion']['Bar-empleador_id']);
            }


			$this->Relacion->RelacionesHistorial->Behaviors->detach('Permisos');

			$conditions['RelacionesHistorial.id'] = Set::extract('/RelacionesHistorial/id', $this->Relacion->RelacionesHistorial->find('all', array(
				'contain'		=> 'Relacion',
				'fields'		=> 'MAX(RelacionesHistorial.id) AS id',
				'group'			=> 'RelacionesHistorial.relacion_id',
				'conditions' 	=> $conditions)));

            $this->set('data', $this->Relacion->RelacionesHistorial->find('all', array(
				'contain'		=> array(
					'Relacion' => array('Trabajador', 'Empleador'),
					'Relacion.Area',
					'EgresosMotivo'),
				'conditions' 	=> $conditions,
				'order' 		=> array('RelacionesHistorial.id' => 'DESC'))));

            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }

	
/**
 * Set default search condition to active relations.
 */
	function index() {
		if (empty($this->data)) {
			$this->data['Condicion']['Relacion-estado'] = 'Activa';
		}
		return parent::index();
	}


/**
 * areas_relacionado.
 */
	function areas_relacionado($id) {
		$c=0;
		foreach ($this->Relacion->Area->find('list', array('fields' => array('Area.nombre'), 'conditions'=>array('Area.empleador_id' => $id))) as $k => $v) {
			$areas[$c]['optionValue'] = $k;
			$areas[$c]['optionDisplay'] = $v;
			$c++;
		}
		$this->set('data', $areas);
		$this->render('../elements/json');
	}


/**
 * recibos_relacionado.
 */
	function recibos_relacionado($id) {
		$c=0;

		foreach ($this->Relacion->Empleador->Recibo->find('all', array(
				'recursive'		=> -1,
				'order'			=> array('Recibo.convenio_id' => 'DESC'),
				'conditions'	=> array('OR' => array(
					'Recibo.empleador_id' 	=> $id,
					'Recibo.convenio_id !=' => null)
				)
			)) as $v) {
			if (!empty($v['Recibo']['convenio_id'])) {
				$v['Recibo']['nombre'] = '(C.C.) ' . $v['Recibo']['nombre'];
			} else {
				$v['Recibo']['nombre'] = '(Emp.) ' . $v['Recibo']['nombre'];
			}
			$recibos[$c]['optionValue'] = $v['Recibo']['id'];
			$recibos[$c]['optionDisplay'] = $v['Recibo']['nombre'];
			$c++;
		}
		$this->set('data', $recibos);
		$this->render('../elements/json');
	}

/**
 * Save.
 */
	function save() {

		/**
		* Si esta grabando y selecciona un recibo del empleador, agrego a la relacion laboral,
		* los conceptos que posea ese recibo.
		*/
        if (empty($this->data['Relacion']['id']) && !empty($this->data['Relacion']['recibo_id']) && !empty($this->data['Form']['accion']) && $this->data['Form']['accion'] === 'grabar') {
        	$recibo = $this->Relacion->Empleador->Recibo->findById($this->data['Relacion']['recibo_id']);
        	foreach ($recibo['RecibosConcepto'] as $v) {
        		$relacionesConcepto[] = array('concepto_id' => $v['concepto_id']);
        	}
            unset($this->Relacion->RelacionesConcepto->validate['relacion_id']);
	        $this->data = array_merge($this->data, array('RelacionesConcepto' => $relacionesConcepto));
        }
        return parent::save();
	}


/**
 * Pagos.
 * Muestra via desglose los Pagos asociados a la relacion laboral.
 */
	function pagos($id) {
		$this->Relacion->contain(array('Pago'));
		$this->data = $this->Relacion->read(null, $id);
	}


/**
 * Vacaciones.
 * Muestra via desglose las Vacaciones asociadas a la relacion laboral.
 */
	function vacaciones($id) {
		$this->data = $this->Relacion->read(null, $id);
	}


/**
 * Ausencias.
 * Muestra via desglose las Ausencias asociadas a la relacion laboral.
 */
	function ausencias($id) {
		$this->Relacion->contain(array('Ausencia.AusenciasMotivo'));
		$result = $this->Relacion->read(null, $id);
		$this->data = $result;
	}


/**
 * Conceptos.
 * Muestra via desglose los Conceptos asociados a la relacion laboral.
 */
	function conceptos($id) {
		$this->Relacion->contain(array('RelacionesConcepto.Concepto', 'ConveniosCategoria.Convenio'));
		$relacion = $this->Relacion->read(null, $id);

        foreach ($relacion['RelacionesConcepto'] as $k => $concepto) {
            $r = $this->Relacion->Concepto->findConceptos('ConceptoPuntual',
                array('relacion' => $relacion, 'codigoConcepto' => $concepto['Concepto']['codigo']));
            $relacion['RelacionesConcepto'][$k]['Concepto'] = array_pop($r);
        }
        $this->data = $relacion;
	}


/**
 * Ropas.
 * Muestra via desglose la ropa entregada a la relacion laboral.
 */
	function ropas($id) {
		$this->Relacion->contain('Ropa');
		$this->data = $this->Relacion->read(null, $id);
	}


/**
 * horas.
 * Muestra via desglose las horas asignadas a una relacion.
 */
	function horas($id) {
		$this->Relacion->contain('Hora');
		$this->data = $this->Relacion->read(null, $id);
	}


/**
 * descuentos.
 * Muestra via desglose los descuentos de una relacion.
 */
	function descuentos($id) {
		$this->Relacion->contain('Descuento');
		$this->data = $this->Relacion->read(null, $id);
	}


    function historial($id) {
        $this->Relacion->contain('RelacionesHistorial.EgresosMotivo');
        $this->data = $this->Relacion->read(null, $id);
    }


/**
 * descuentos_detalle.
 * Muestra via desglose el detalle de los descuentos realizados.
 */
	function descuentos_detalle($id) {
		$this->Relacion->Descuento->contain(array('DescuentosDetalle'));
		$this->data = $this->Relacion->Descuento->read(null, $id);
		$this->render('../descuentos/detalles');
	}

}
?>