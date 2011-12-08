<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los convenios.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1455 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-07-02 22:07:06 -0300 (sáb 02 de jul de 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los convenios.
 *
 * Son los convenios colectivos.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ConveniosController extends AppController {


    var $paginate = array(
        'order' => array(
            'Convenio.nombre' => 'asc'
        )
    );


	var $helpers = array('Documento');

/**
 * Permite descargar el archivo del convenio colectivo.
 */
	function descargar($id) {
		$convenio = $this->Convenio->findById($id);
		$archivo['data'] = $convenio['Convenio']['file_data'];
		$archivo['size'] = $convenio['Convenio']['file_size'];
		$archivo['type'] = $convenio['Convenio']['file_type'];
		$archivo['name'] = $this->Util->getFileName($convenio['Convenio']['nombre'], $convenio['Convenio']['file_type']);
		$this->set("archivo", $archivo);
		$this->render("../elements/descargar", "descargar");
	}


/**
 * Categorias.
 * Muestra via desglose las categorias de los convenios colectivos.
 */
	function categorias($id) {
		$this->Convenio->contain(array('ConveniosCategoria.ConveniosCategoriasHistorico'));
		$this->data = $this->Convenio->read(null, $id);

		foreach ($this->data['ConveniosCategoria'] as $k => $v) {
			if (isset($v['ConveniosCategoriasHistorico'])) {
				$this->data['ConveniosCategoria'][$k]['costo'] = $this->Convenio->ConveniosCategoria->__getCosto($v['ConveniosCategoriasHistorico']);
			}
		}

	}


/**
 * Antiguedades.
 * Muestra via desglose las antiguedades de los convenios colectivos.
 */
	function antiguedades($id) {
		$this->Convenio->contain(array('ConveniosAntiguedad'));
		$this->data = $this->Convenio->read(null, $id);
	}


/**
 * Conceptos.
 * Muestra via desglose los conceptos asociados a este convenio colectivo.
 */
	function conceptos($id) {
		$this->Convenio->contain(array('Concepto'));
		$this->data = $this->Convenio->read(null, $id);
	}


/**
 * Recibos
 * Muestra via desglose los Recibos de un convenio colectivo.
 */
	function recibos($id) {
		$this->Convenio->contain(array('Recibo'));
		$this->data = $this->Convenio->read(null, $id);
	}


/**
 * Informaciones.
 * Muestra via desglose los conceptos asociados a este convenio colectivo.
 */
	function informaciones($id) {
		$this->Convenio->contain(array("ConveniosInformacion.Informacion"));
		$this->data = $this->Convenio->read(null, $id);
	}
	
/**
 * Asigna un concepto a todos los trabajadores de todos los empleadores de un convenio.
 */
	function manipular_concepto($accion = null) {
		if (!empty($this->params['named']['concepto_id']) && !empty($this->params['named']['convenio_id'])
			&& is_numeric($this->params['named']['concepto_id']) && is_numeric($this->params['named']['convenio_id'])
			&& !empty($accion)) {
			$this->Convenio->ConveniosCategoria->contain();
			$conveniosCategoria = $this->Convenio->ConveniosCategoria->find("list", array("conditions"=>array("ConveniosCategoria.convenio_id"=>$this->params['named']['convenio_id'])));
			$this->Convenio->ConveniosCategoria->Relacion->contain();
			
			$relaciones = $this->Convenio->ConveniosCategoria->Relacion->find("list", array("fields"=>array("Relacion.id"), "conditions"=>array("Relacion.convenios_categoria_id"=>array_values($conveniosCategoria))));
			$c = $this->Convenio->ConveniosCategoria->Relacion->RelacionesConcepto->Concepto->agregarQuitarConcepto($relaciones, array($this->params['named']['concepto_id']), array("accion"=>$accion));
			if ($c > 0) {
				$this->Session->setFlash("El concepto se pudo " . $accion . " correctamente a " . $c . " trabajadores.", "ok");
			} else {
				$this->Session->setFlash("El concepto no se lo pudo " . $accion . " a ningun trabajador. Puede que ya haya estado asignado/quitado.", "warning");
			}
		}
		$this->redirect('index');
	}


/**
 * Genera una planilla en formato Excel2007 o Excel5 para el ingreso de actualizaciones de escalas en las categorias.
 *
 * @access public.
 * @return void.
 */
	function generar_planilla() {
		if (!empty($this->data['Formulario']['accion'])) {
			if ($this->data['Formulario']['accion'] === 'buscar') {

				if (!empty($this->data['Condicion']['ConveniosCategoria-convenio_id'])) {

					$conditions['ConveniosCategoria.convenio_id'] = explode('**||**', $this->data['Condicion']['ConveniosCategoria-convenio_id']);

					$registros = $this->Convenio->ConveniosCategoria->find('all',
						array(
							'contain'		=> array('Convenio'),
							'order'     	=> array('Convenio.nombre', 'ConveniosCategoria.nombre'),
							'conditions'	=> $conditions
						)
					);


					if (!empty($registros)) {
						$this->set('fileFormat', $this->data['Condicion']['Novedad-formato']);
						$this->set('registros', $registros);
						$this->layout = 'ajax';
					} else {
						$this->Session->setFlash('No se encontraron categorias cargadas para el convenio seleccionado. Por favor verifique.', 'error');
					}

				} else {
					$this->Session->setFlash('Debe seleccionar por lo menos un convenio', 'error');
				}

			} elseif ($this->data['Formulario']['accion'] === 'limpiar') {
				$this->Session->delete('filtros.' . $this->name . '.' . $this->action);
				unset($this->data['Condicion']);
			}
		}

		/** Fijo lo que viene preseleccionado */
		$this->data['Condicion']['Novedad-formato'] = "Excel2007";
	}


/**
 * Importa una planilla en formato Excel2007 o Excel5 con las actualizaciones de las escalas en las categorias.
 */
	function importar_planilla() {
		if (!empty($this->data['Formulario']['accion'])) {
			if ($this->data['Formulario']['accion'] === 'importar') {
				if (!empty($this->data['ConveniosCategoria']['planilla']['tmp_name'])) {

					set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes');
					App::import('Vendor', 'IOFactory', true, array(APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel'), 'IOFactory.php');
					
					if (preg_match("/.*\.xls$/", $this->data['ConveniosCategoria']['planilla']['name'])) {
						$objReader = PHPExcel_IOFactory::createReader('Excel5');
					} elseif (preg_match("/.*\.xlsx$/", $this->data['ConveniosCategoria']['planilla']['name'])) {
						$objReader = PHPExcel_IOFactory::createReader('Excel2007');
					}
                    $objReader->setReadDataOnly(true);
					$objPHPExcel = $objReader->load($this->data['ConveniosCategoria']['planilla']['tmp_name']);

					App::import('Vendor', 'dates', 'pragmatia');	
					for ($i = 10; $i <= $objPHPExcel->getActiveSheet()->getHighestRow() - 1; $i++) {

						$values[] = array(
							'ConveniosCategoriasHistorico' => array(
								'convenios_categoria_id' => $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->getValue(),
								'desde' => $this->Util->format(Dates::dateAdd('1970-01-01', $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue() - 25569, 'd', array('fromInclusive' => false)), 'date'),
								'hasta' =>
								$this->Util->format(Dates::dateAdd('1970-01-01', $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue() - 25569, 'd', array('fromInclusive' => false)), 'date'),
								'costo' =>
								$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->getValue()
							)
						);
					}

					if (!empty($values)) {
						if ($this->Convenio->ConveniosCategoria->ConveniosCategoriasHistorico->saveAll($values)) {
							$this->Session->setFlash('Se importaron correctamente las categorias', 'error');
							$this->redirect('convenios');
						} else {
							$this->Session->setFlash('No fue posible importar las categorias. Verifique la planilla', 'error');
						}
					}

				}
			}
		}
	}


}
?>