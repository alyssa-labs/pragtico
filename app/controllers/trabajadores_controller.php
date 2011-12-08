<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los trabajadores.
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
 * La clase encapsula la logica de negocio asociada a los trabajadores.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class TrabajadoresController extends AppController {


	var $helpers = array('Documento');


	function afterSave() {

		if (empty($this->data['Trabajador']['id'])
			&& !empty($params['Relacion.trabajador_id'])
			&& $params['Relacion.trabajador_id'] == '##ID##') {

			$params['Relacion.trabajador_id'] = $this->Trabajador->id;
			$this->Session->setFlash('El Trabajador ha sido guardado, por favor ahora cree la relacion con un Empleador.', 'ok');

			$this->redirect($params);
			return false;
		} else {
			return parent::afterSave();
		}
	}


    function importar_cbus() {
        if (!empty($this->data['Formulario']['accion'])) {
            if ($this->data['Formulario']['accion'] === 'importar') {
                if (!empty($this->data['Trabajador']['planilla']['tmp_name'])) {
                    set_include_path(get_include_path() . PATH_SEPARATOR . APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes');
                    App::import('Vendor', 'IOFactory', true, array(APP . 'vendors' . DS . 'PHPExcel' . DS . 'Classes' . DS . 'PHPExcel'), 'IOFactory.php');
                    
                    if (preg_match("/.*\.xls$/", $this->data['Trabajador']['planilla']['name'])) {
                        $objReader = PHPExcel_IOFactory::createReader('Excel5');
                    } elseif (preg_match("/.*\.xlsx$/", $this->data['Trabajador']['planilla']['name'])) {
                        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
                    }
                    $objPHPExcel = $objReader->load($this->data['Trabajador']['planilla']['tmp_name']);

                    $c = 0;
                    $this->Trabajador->unbindModel(array('belongsTo' => array_keys($this->Trabajador->belongsTo)));
                    for($i = 1; $i <= $objPHPExcel->getActiveSheet()->getHighestRow(); $i++) {
                        $cuil = $cbu = null;
                        $cuil = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getValue();
                        $cbu = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue();
                        if (!empty($cuil) && !empty($cuil)
                            && in_array(strlen($cuil), array(11, 13)) && strlen($cbu) == 22
                            && $this->Trabajador->updateAll(
                                array(
                                    'Trabajador.solicitar_tarjeta_debito'   => "'No'",
                                    'Trabajador.cbu'                        => "'" . $cbu . "'"),
                                array("REPLACE(Trabajador.cuil, '-', '')"   => str_replace('-', '', $cuil)))) {

                            $c++;
                        }
                    }

                    if ($c > 0) {
                        $this->Session->setFlash('Se actualizaron ' . $c . ' Cbus.', 'ok');
                    } else {
                        $this->Session->setFlash('No fue posible actualizar ningun Cbu. Verifique la planilla', 'error');
                    }
                    $this->redirect('index');
                }
            } elseif ($this->data['Formulario']['accion'] === 'cancelar') {
                $this->redirect('index');
            }
        }
    }


    function solicitar_tarjetas_debito() {
        
        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {
            $conditions['Trabajador.solicitar_tarjeta_debito'] = 'Si';
            $conditions['(Relacion.group_id & ' . $this->data['Condicion']['Bar-grupo_id'] . ') >'] = 0;

            $this->Trabajador->Relacion->Behaviors->detach('Permisos');
            $data = $this->Trabajador->Relacion->find('all', array(
                'contain'       => array('Trabajador.Localidad.Provincia', 'Empleador'),
                'conditions'    => $conditions));

            if (!empty($data)) {
                /** Update state to avoid selecting again next time */
                if ($this->data['Condicion']['Bar-marcar'] == 'si') {
                    $this->Trabajador->updateAll(
                        array('Trabajador.solicitar_tarjeta_debito' => "'Solicitud en Proceso'"),
                        array('Trabajador.id' => Set::extract('/Trabajador/id', $data)));
                }
                $this->set('data', $data);
                $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
            } else {
                $this->Session->setFlash('No se encontraron trabajadores a los cuales solicitarle Tarjeta de Debito.', 'error');
                $this->History->goBack();
            }
        }
        
    }
	

/**
 * Permite descargar y/o mostrar la foto del trabajador.
 */
	function descargar($id) {
		$trabajador = $this->Trabajador->findById($id);
		$archivo['data'] = $trabajador['Trabajador']['file_data'];
		$archivo['size'] = $trabajador['Trabajador']['file_size'];
		$archivo['type'] = $trabajador['Trabajador']['file_type'];
		$archivo['name'] = $this->Util->getFileName($trabajador['Trabajador']['nombre'], $trabajador['Trabajador']['file_type']);
		$this->set("archivo", $archivo);
		if (!empty($this->params['named']['mostrar']) && $this->params['named']['mostrar'] == true) {
			$this->set("mostrar", true);
		}
		$this->render("../elements/descargar", "descargar");
	}

/**
 * Relaciones.
 * Muestra via desglose las Relaciones Laborales existentes entre un trabajador y un empleador.
 */
	function relaciones($id) {
		$this->Trabajador->contain('Empleador');
		$this->data = $this->Trabajador->read(null, $id);
	}



/**
 * Familiares.
 * Muestra via desglose los familiares de los trabajadores.
 */
    function familiares($id) {
        $this->Trabajador->contain('Familiar');
        $this->data = $this->Trabajador->read(null, $id);
    }
}
?>