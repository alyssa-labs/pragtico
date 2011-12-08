<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las novedades.
 * Una novedad es un ingreso de datos al sistema no confirmado aun.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1305 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-18 16:10:16 -0300 (mar 18 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a las novedades.
 * Una novedad es un ingreso de datos al sistema no confirmado aun.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */

class NovedadesController extends AppController {

    var $paginate = array(
        'order' => array(
            'Novedad.periodo' => 'desc',
            'Novedad.tipo' => 'asc'
        )
    );

	var $helpers = array('Documento');


	function index($paymentIds = null) {
        $this->set('paymentIds', $paymentIds);
		parent::index();
	}


/**
 * Confirma las novedades seleccionadas.
 */
	function confirmar() {
		if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'confirmar') {
			$result = $this->Novedad->confirmar($this->Util->extraerIds($this->data['seleccionMultiple']));
			if ($result) {
				$this->Session->setFlash('Se confrmaron correctamente ' . $result['quantity'] . ' novedades', 'ok');
				if (!empty($result['idByType']['Descuento'])) {
					$this->redirect('index/' . implode('|', $result['idByType']['Descuento']));
				}
			} else {
				$this->Session->setFlash('No fue posible confirmar las novedades', 'error');
			}
		}
		$this->redirect('index');
	}

/**
 * detalles.
 * Muestra via desglose los detalles de la novedad.
 */
	function detalles($id) {
		$this->Novedad->recursive = -1;
		$data = $this->Novedad->read(null, $id);
		$this->set("data", unserialize($data['Novedad']['data']));
	}

	
/**
 * Importa una planilla en formato Excel2007 o Excel5 con las novedades.
 */
	function importar_planilla() {
		if (!empty($this->data['Formulario']['accion'])) {
			if ($this->data['Formulario']['accion'] === 'importar') {
				if (!empty($this->data['Novedad']['planilla']['tmp_name'])) {
					set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes');
					App::import('Vendor', 'IOFactory', true, array(APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel'), 'IOFactory.php');
					
					if (preg_match("/.*\.xls$/", $this->data['Novedad']['planilla']['name'])) {
						$objReader = PHPExcel_IOFactory::createReader('Excel5');
					} elseif (preg_match("/.*\.xlsx$/", $this->data['Novedad']['planilla']['name'])) {
						$objReader = PHPExcel_IOFactory::createReader('Excel2007');
					}
                    $objReader->setReadDataOnly(true);
					$objPHPExcel = $objReader->load($this->data['Novedad']['planilla']['tmp_name']);
					
					/**
					* Vuelvo 10 columnas antes del final, ya que puede haber validaciones, siempre estan la final.
					*/
					for($i = 8; $i < PHPExcel_Cell::columnIndexFromString($objPHPExcel->getActiveSheet()->getHighestColumn()); $i++) {
						$value = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($i, 8)->getValue();
						if (empty($value)) {
							break;
						}
						
						if ($value === 'Horas') {
							$mapeo['Horas']['Normal']						= $i;
							$mapeo['Horas']['Extra 50%']					= $i+1;
							$mapeo['Horas']['Extra 100%' ] 					= $i+2;
							$i = $i+2;
						} elseif ($value === 'Horas Ajuste') {
							$mapeo['Horas']['Ajuste Normal'] 				= $i;
							$mapeo['Horas']['Ajuste Extra 50%']				= $i+1;
							$mapeo['Horas']['Ajuste Extra 100%']			= $i+2;
							$i = $i+2;
						} elseif ($value === 'Horas Nocturna') {
							$mapeo['Horas']['Normal Nocturna']				= $i;
							$mapeo['Horas']['Extra Nocturna 50%']			= $i+1;
							$mapeo['Horas']['Extra Nocturna 100%']			= $i+2;
							$i = $i+2;
						} elseif ($value === 'Horas Ajuste Nocturna') {
							$mapeo['Horas']['Ajuste Normal Nocturna']		= $i;
							$mapeo['Horas']['Ajuste Extra Nocturna 50%']	= $i+1;
							$mapeo['Horas']['Ajuste Extra Nocturna 100%']	= $i+2;
							$i = $i+2;
						} elseif ($value === 'Ausencias') {
							$mapeo['Ausencias']['Motivo']					= $i;
							$mapeo['Ausencias']['Desde']					= $i+1;
							$mapeo['Ausencias']['Dias']						= $i+2;
							$i = $i+2;
                        } elseif ($value === 'Vacaciones') {
                            $mapeo['Vacaciones']['Corresponde']              = $i;
                            $mapeo['Vacaciones']['Periodo']                  = $i+1;
                            $mapeo['Vacaciones']['Inicio']                   = $i+2;
                            $mapeo['Vacaciones']['Dias']                     = $i+3;
                            $i = $i+3;
						} elseif ($value === 'Vales') {
							$mapeo['Vales']['Importe']						= $i;
						} else {
							$mapeo[$value]['Valor']							= $i;
						}
					}

					for ($i = 10; $i <= $objPHPExcel->getActiveSheet()->getHighestRow() - 1; $i++) {
						$relacionId = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getValue();

						/** Try to get period out of file */
						$tmp = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getValue();
						if (!empty($tmp)) {
							$relacionId .= '|' . $tmp;
						} else {
							$relacionId .= '|' . $this->data['Novedad']['periodo'];
						}

						/** Try to get receipt type out of file */
						$tmp = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getValue();
						if (!empty($tmp)
							&& in_array(strtolower($this->data['Novedad']['liquidacion_tipo']), array_keys($this->Novedad->Liquidacion->opciones['tipo']))) {
							$relacionId .= '|' . strtolower($tmp);
						} else {
							$relacionId .= '|' . $this->data['Novedad']['liquidacion_tipo'];
						}
						
						foreach ($mapeo as $k => $v) {
							foreach ($v as $k1 => $v1) {
								$valor = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($v1, $i)->getValue();
								if (!empty($valor) && !empty($relacionId)) {
									$datos[$relacionId][trim($k)][$k1] = $valor;
								}
							}
						}
					}

					if ($this->Novedad->grabar($datos)) {
						$this->redirect('index');
					}
				}
			} elseif ($this->data['Formulario']['accion'] === 'cancelar') {
				$this->redirect('index');
			}
		}
        $this->set('liquidacion_tipo', $this->Novedad->Liquidacion->opciones['tipo']);
		$this->data['Novedad']['formato'] = 'Excel2007';
	}
	
	
/**
 * Genera una planilla en formato Excel2007 o Excel5 para el ingreso de novedades.
 * El contenido de la planilla son las relaciones especificadas por los criterios, mas los conceptos seleccionados.
 *
 * @access public.
 * @return void.
 */
	function generar_planilla() {
		if (!empty($this->data['Formulario']['accion'])) {
			if ($this->data['Formulario']['accion'] === 'buscar') {
				if (empty($this->data['Condicion']['Relacion-trabajador_id'])
					&& empty($this->data['Condicion']['Relacion-empleador_id'])
					&& empty($this->data['Condicion']['Relacion-id'])) {
					$this->Session->setFlash('Debe seleccionar al menos un criterio para la generacion de la planilla.', 'error');
				} elseif (empty($this->data['Condicion']['Novedad-tipo'])) {
					$this->Session->setFlash('Debe seleccionar al menos un tipo para la generacion de la planilla.', 'error');
				} else {
					$tipos = $this->data['Condicion']['Novedad-tipo'];
					$formatoDocumento = $this->data['Condicion']['Novedad-formato'];
					unset($this->data['Condicion']['Novedad-formato']);
					unset($this->data['Condicion']['Novedad-tipo']);

                    $contain = array();
                    if (!empty($this->data['Condicion']['Novedad-periodo_vacacional'])) {
                        $contain = array('Vacacion' => array('conditions' => array('Vacacion.periodo' => $this->data['Condicion']['Novedad-periodo_vacacional'])));
                        unset($this->data['Condicion']['Novedad-periodo_vacacional']);
                    }
                    
					$conditions = $this->Paginador->generarCondicion(false);
                    $conditions['Relacion.estado'] = 'Activa';

					$registros = $this->Novedad->Relacion->find('all',
						array('contain'	=> array_merge(array(
                            'RelacionesHistorial',
                            'ConveniosCategoria',
                            'Trabajador',
                            'Empleador'), $contain),
                            'order'     => array('Empleador.nombre', 'Trabajador.apellido', 'Trabajador.nombre'),
							'conditions'=> $conditions));

					if (!empty($registros)) {
						$this->set('registros', $registros);
						$this->set('motivos', $this->Novedad->Relacion->Ausencia->AusenciasMotivo->find('list', array('fields'	=> array('AusenciasMotivo.id', 'AusenciasMotivo.motivo'))));
						$this->set('formatoDocumento', $formatoDocumento);
						$this->set('tipos', $tipos);
						$this->set('tiposPredefinidos', $this->Novedad->getIngresosPosibles('predefinidos'));
						$this->layout = 'ajax';
					} else {
						$this->Session->setFlash('No se encontraron relaciones para generar la planilla con los criterios establecidos. Por favor verifique.', 'error');
					}
				}
			} elseif ($this->data['Formulario']['accion'] === "limpiar") {
				$this->Session->delete('filtros.' . $this->name . '.' . $this->action);
				unset($this->data['Condicion']);
			}
		}
		/** Fijo lo que viene preseleccionado */
		$this->data['Condicion']['Novedad-formato'] = "Excel2007";
		$tiposIngreso = $this->Novedad->getIngresosPosibles();
		$this->data['Condicion']['Novedad-tipo'] = $tiposIngreso;
		foreach ($tiposIngreso as $v) {
			$tiposIngresoKey[$v] = $v;
		}
		$this->set('tiposIngreso', $tiposIngresoKey);
	}
	

    function delete($id = null, $goBack = 0) {
        $this->Novedad->setSecurityAccess('readOwnerOnly');
        return parent::delete($id, $goBack);
    }
        
}
?>