<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los pagos que se le realizan a las relaciones laborales.
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
 * La clase encapsula la logica de negocio asociada a los pagos que se le realizan a las relaciones laborales.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class PagosController extends AppController {


	var $helpers = array('Documento');

    var $paginate = array(
        'order' => array(
			'Pago.fecha'							=> 'DESC',
            'IF(Liquidacion.trabajador_cbu="",0,1)' => 'DESC',
			'Liquidacion.trabajador_apellido',
			'Liquidacion.trabajador_nombre'
        )
    );


    function index() {
        $this->__setConditions();
        return parent::index();
    }


    function afterPaginate($results) {
        $this->__setConditions();
        if (!empty($results)) {
            $this->set('monto', $this->Pago->getTotal($this->Paginador->getCondition()));
        } else {
            $this->set('monto', 0);
        }
    }


    function __setConditions() {
        if (!empty($this->data['Condicion']['Pago-origen'])) {

            $this->Paginador->setWhiteList('Pago-origen');
            if ($this->data['Condicion']['Pago-origen'] === 'liquidaciones') {
                $this->Paginador->removeCondition(array('Pago.descuento_id !=', 'Pago.liquidacion_id'));
                $this->Paginador->setCondition(array(
                    'Pago.descuento_id'         => null,
                    'Pago.liquidacion_id !='    => null));
            } else {
                $this->Paginador->removeCondition(
					array(
						'Pago.descuento_id',
						'Pago.liquidacion_id !=',
						'Liquidacion.liquidaciones_grupo_id'
					)
				);
                $this->Paginador->setCondition(array(
                    'Pago.descuento_id !='      => null,
                    'Pago.liquidacion_id'       => null));
            }
        }
    }


    function beforeRender() {
        if ($this->action === 'index') {
            $this->set('bancos', ClassRegistry::init('Banco')->find('list', array(
                'recursive' => -1,
                'fields'    => array('Banco.codigo', 'Banco.nombre'))));
            $filters = $this->Session->read('filtros.' . $this->name . '.' . $this->action);

            if (!empty($filters['condiciones']['Liquidacion.ano'])
				&& !empty($filters['condiciones']['Liquidacion.mes'])
				&& !empty($filters['condiciones']['Liquidacion.periodo like'])) {

                $this->data['Condicion']['Liquidacion-periodo_completo'] = $filters['condiciones']['Liquidacion.ano'] . $filters['condiciones']['Liquidacion.mes'] . str_replace('%', '', $filters['condiciones']['Liquidacion.periodo like']);
            }
        }
    }

/**
 * formas.
 * Muestra via desglose las formas de pago relacionadas a este pago.
 */
    function formas($id) {
        $this->Pago->contain(array('PagosForma'));
        $this->data = $this->Pago->read(null, $id);
    }


    function revertir_pago($id) {
        if ($this->Pago->revertir($id)) {
            $this->Session->setFlash('El pago se revirtio correctamente.', 'ok');
        }
        else {
            $errores = $this->Pago->getError();
            $this->Session->setFlash('No fue posible revertir el Pago.', 'error', array('errores'=>$errores));
        }
        $this->History->goBack();
    }


/**
 * Busca las cuentas relacionadas con un empleador. Esta funcion esta preparada para generar datos que se
 * pintaran en un control relacionado via Json.
 *
 * @param number $id Id del empleador del cual se desea recuperar sus cuentas relacionadas.
 * @return    void
 * @access public
 */
    function cuentas_relacionado($id) {
        if (is_numeric($id)) {
            $empleador = $this->Pago->Relacion->Empleador->findById($id);
            if (!empty($empleador['Cuenta'])) {
                $this->Pago->Relacion->Empleador->contain(array('Cuenta'));
                $c=0;
                foreach ($empleador['Cuenta'] as $k=>$v) {
                    $this->Pago->Relacion->Empleador->Cuenta->Sucursal->contain(array('Banco'));
                    $sucursal = $this->Pago->Relacion->Empleador->Cuenta->Sucursal->findById($v['sucursal_id']);
                    $cuentas[$c]['optionValue'] = $v['id'];
                    $cuentas[$c]['optionDisplay'] = $sucursal['Banco']['nombre'] . ', ' . $sucursal['Sucursal']['direccion'];
                    $c++;
                }

                $this->set('data', $cuentas);
                $this->render('../elements/json');
            }
        }
    }


/**
 * Permite la registracion masiva de pagos por algun medio (formas).
 *
 * @param string $tipo Las formas de pago masivo: efectivo, beneficios, deposito
 * @return    void
 * @access public
 */
    function registrar_pago_masivo($tipo) {

        if (!empty($tipo) && is_string($tipo) && in_array($tipo, array('efectivo', 'beneficios', 'deposito'))) {

            $ids = $this->Util->extraerIds($this->data['seleccionMultiple']);

            $cantidad = $this->Pago->registrarPago($ids, $tipo);
            if ($cantidad) {
                $this->Session->setFlash('Se confirmaron correctamente ' . $cantidad . ' de ' . count($ids) . ' pagos con ' . ucfirst($tipo) . '.', 'ok');
            } else {
                $this->Session->setFlash('Ocurrio un error al intentar confirmar los pagos con ' . ucfirst($tipo) . '.', 'error');
            }
        }
        $this->redirect('index');
    }


    function confirmar_soporte_magnetico() {

        if (!empty($this->data['seleccionMultiple'])) {
            $this->set('ids', serialize($this->Util->extraerIds($this->data['seleccionMultiple'])));
        }
        if (!empty($this->data['Soporte']['pago_id'])
            && !empty($this->data['Soporte']['identificador'])) {

			$ids = unserialize($this->data['Soporte']['pago_id']);
			$this->Pago->registrarPago($ids, 'Deposito',
				array('identificador' 	=> $this->data['Soporte']['identificador'])
			);

            $this->redirect(array('controller' => 'pagos', 'action' => 'index'));
        }
    }

    
/**
 * Permite generar un archivo con el formato especificado por cada banco para la acreditacion de haberes en las
 * cuentas de los trabajadores.
 *
 * @return    void
 * @access public
 */
    function generar_soporte_magnetico() {
        
        if (!empty($this->data['Soporte']['pago_id'])
            && !empty($this->data['Soporte']['cuenta_id'])) {

            $opciones = array(  'pago_id'               => unserialize($this->data['Soporte']['pago_id']),
                                'fecha_acreditacion'    => '',
                                'cuenta_id'             => $this->data['Soporte']['cuenta_id']);

            if (!empty($this->data['Soporte']['fecha_acreditacion'])) {
                $opciones['fecha_acreditacion'] = $this->data['Soporte']['fecha_acreditacion'];
            }


            if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] == 'confirmar') {
                $archivo = $this->Pago->generarSoporteMagnetico($opciones, true);

                if (!empty($archivo)) {
                    $this->set('archivo', array('contenido'=>$archivo['contenido'], 'nombre'=>$archivo['banco'] . '-' . date('Y-m-d') . '.txt'));
                    $this->render('..' . DS . 'elements' . DS . 'txt', 'txt');
                } else {
                    $this->Session->setFlash('Ocurrio un error al intentar generar el soporte magnetico. Ningun pago seleccionado es posible realizarlo con la cuenta seleccionada.', 'error');
                    $this->History->goBack();
                }
            } else {
                $this->set('fecha_acreditacion', $opciones['fecha_acreditacion']);

				$confirmation = $this->Pago->generarSoporteMagnetico($opciones, false);
				if (!empty($confirmation)) {
                	$this->set('confirmar', $confirmation);
				} else {
					$this->Session->setFlash('Ocurrio un error al intentar generar el soporte magnetico. Ningun pago seleccionado es valido.', 'error');
					$this->redirect(array('controller' => 'pagos', 'action' => 'index'));
				}
            }
        } elseif (isset($this->data['seleccionMultiple'])) {
            $ids = $this->Util->extraerIds($this->data['seleccionMultiple']);
            $pagos = $this->Pago->find('all', array('contain' => 'PagosForma', 'conditions'=>array('Pago.moneda' => 'Pesos', 'Pago.estado' => 'Pendiente', 'Pago.id'=>$ids)));
            if (empty($pagos)) {
                $this->Session->setFlash('Ocurrio un error al intentar generar el soporte magnetico. Ningun pago seleccionado es valido.', 'error');
                $this->History->goBack();
            }
            $this->set('ids', serialize($ids));
        }
    }


	function reporte_recibos($id = null) {
		$pagos = $this->Pago->find('all',
			array(
				'contain' 		=> array('PagosForma', 'Relacion' => array('Trabajador', 'Empleador')),
				'conditions'	=> array(
					'Pago.id' 		=> $id,
					'Pago.moneda' 	=> 'Pesos',
				)
			)
		);
		$this->set('data', $pagos);
	}
}
?>