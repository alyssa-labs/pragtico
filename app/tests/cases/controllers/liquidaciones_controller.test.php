<?php
/**
 * Este archivo contiene un caso de prueba para el proceso de liquidaciones.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
App::import('Component', array('Session', 'Util'));
App::import("Model", array("Liquidacion", "Variable"));

require_once(APP . "tests" . DS . "cases" . DS . "models" . DS . "usuario.test.php");
//require_once(APP . "tests" . DS . "cases" . DS . "models" . DS . "liquidacion.test.php");
//require_once(APP . "tests" . DS . "cases" . DS . "models" . DS . "usuario.test.php");

App::import("Controller", "Liquidaciones");
 
/**
 * La clase para un para un controlador de prueba generico (fake).
 *
 * @package app.tests
 * @subpackage app.tests.controllers
 */
class LiquidacionesTestController extends CakeTestCase {
   
/**
 * Fixtures asociados a este caso de prueba.
 *
 * @var array
 * @access public
 */	
	var $fixtures = array('trabajador', 'liquidacion', 'relacion', 'localidad', 'provincia', 'siniestrado', 'condicion', 'obras_social', 'empleador', 'actividad',
						 'area', 'suss', 'sucursal', 'banco', 'recibo', 'recibos_concepto', 'concepto', 'coeficiente', 'convenio', 'convenios_categoria', 
						 'convenios_categorias_historico', 'situacion', 'modalidad', 'ausencias_motivo', 'ausencia', 'ausencias_seguimiento',
						 'ropa', 'ropas_detalle', 'hora', 'relaciones_concepto','pago', 'pagos_forma', 'cuenta', 'descuento', 'descuentos_detalle',
						 'convenios_informacion', 'informacion', 'convenios_concepto', 'empleadores_concepto', 'rubro', 'empleadores_rubro', 'empleadores_coeficiente',
						 'factura', 'facturas_detalle', 'liquidaciones_detalle', 'liquidaciones_auxiliar', 'liquidaciones_error', 'variable',
						 'usuario', 'rol', 'grupo', 'roles_usuario', 'grupos_usuario', 'grupos_parametro', 'roles_accion', 'roles_menu', 'menu', 'accion', 'controlador',
						 'preferencia', 'preferencias_usuario', 'preferencias_valor');
	
	
/**
 * Sobreescribo el metodo endController. Se ejecuta inmediatamente despues de testAction.
 * Cuando finaliza un caso de prueba, elimina (drop) las tablas creadas por los fixtures. Antes de que esto ocurra, 
 * realizo las queries necesarias y guardo los resultados obtenidos que utilizare para comparar contra los resultados esperados.
 *
 * @var array
 * @access public
 */	
	function endController(&$controller, $params = array()) {
		$controller->Liquidacion->LiquidacionesDetalle->recursive = -1;
		$this->__LiquidacionesDetalle = $controller->Liquidacion->LiquidacionesDetalle->find("all");
		
		/**
		* A Liquidacion (el primer elemento del vector), 
		* lo he creado mediante un fixture, si no lo saco, lo intentara dropear nuevamente y dara error.
		*/
		unset($this->_actionFixtures[0]);
		return parent::endController($controller, $params);
	}
	
	
/**
 * El caso de prueba para la preliquidacion.
 *
 * - Normal
 * - Mensual
 * - Empleado de Comercio (Auxiliar Especializado A)
 * - Conceptos:
 * 		- sueldo_basico
 * 		- antiguedad
 * 		- presentismo
 * 		- jubilacion
 * 		- ley_19032
 * 		- agec
 * 		- obra_social
*/	
	function xtestPreliquidacionNormal() { 
		
		$this->__login();
		
		/**
		* Preparo la data.
		*/
		$data['Formulario']['accion'] = "buscar";
		$data['Extras']['Liquidacion-periodo'] = "200808M";
		$data['Extras']['Liquidacion-tipo'] = "normal";
		$data['Condicion']['Relacion-id'] = "2";
		
		$this->__LiquidacionesDetalle = null;
		$result = $this->testAction('/liquidaciones/preliquidar', 
								array('connection'	=> 'test', 
										'method' 	=> 'post',
		  								'fixturize' => true, 
		  								'return'	=> 'vars',
		  								'data' 		=> $data));
		
		d($this->__LiquidacionesDetalle);
		foreach ($this->__LiquidacionesDetalle as $v) {
			$detalles[$v['LiquidacionesDetalle']['concepto_codigo']] = $v['LiquidacionesDetalle']['valor'];
		}
		d($detalles);
		$this->assertEqual(30, count($this->__LiquidacionesDetalle));
	}
	
	
	
	function testVariables() {
		/**
		* Instancio el controller.
		*/
		$LiquidacionesController = new LiquidacionesController();
		
		/**
		* Obtengo el listado completo de variables.
		*/
		$Variable = ClassRegistry::init("Variable");
		$variablesTmp = $Variable->find("all", array("order"=>false));
		foreach ($variablesTmp as $v) {
			$variables[$v['Variable']['nombre']] = $v['Variable'];
		}
		$variables['#tipo_liquidacion']['valor'] = "normal";
		$LiquidacionesController->__setVariable($variables);
		
		/**
		* Instancio el component.
		*/
		$LiquidacionesController->Util = ClassRegistry::init("UtilComponent");
		$LiquidacionesController->__periodo = $LiquidacionesController->Util->format("2008091Q", "periodo");

		/**
		* Instancio el model.
		*/
		$LiquidacionesController->Liquidacion = ClassRegistry::init("Liquidacion");
		
		/**
		* Busco una relacion.
		*/
		$contain = array(	"ConveniosCategoria.ConveniosCategoriasHistorico",
							"Trabajador.ObrasSocial",
							"Empleador");
		$LiquidacionesController->Liquidacion->Relacion->contain($contain);
		$LiquidacionesController->__relacion = $LiquidacionesController->Liquidacion->Relacion->findById(2);
		
		/**
		* Seteo las variables que vienen dadas por informacion del convenio.
		*/
		$informaciones = $LiquidacionesController->Liquidacion->Relacion->ConveniosCategoria->Convenio->getInformacion(Set::extract("/ConveniosCategoria/convenio_id", $LiquidacionesController->__relacion));
		$LiquidacionesController->__setVariable($informaciones[2]);
		
		
		/**
		* Hago los tests propiamente dicho.
		*/
		$result = $LiquidacionesController->__getVariableValor("#tipo_liquidacion");
		$expected = "normal";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dias_corridos_periodo");
		$expected = "15";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_por_mes");
		$expected = "176";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dia_del_gremio");
		$expected = "13";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#ausencias_injustificadas");
		$expected = "2";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#ausencias_justificadas");
		$expected = "4";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas");
		$expected = "10";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_extra_50");
		$expected = "65";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_extra_100");
		$expected = "31";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_ajuste");
		$expected = "32";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_ajuste_extra_50");
		$expected = "21.30";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_ajuste_extra_100");
		$expected = "24.30";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_nocturna");
		$expected = "3.70";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_extra_nocturna_50");
		$expected = "1.70";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_extra_nocturna_100");
		$expected = "0.70";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_ajuste_nocturna");
		$expected = "0.70";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_ajuste_extra_nocturna_50");
		$expected = "3.20";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#horas_ajuste_extra_nocturna_100");
		$expected = "5.30";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#anos_antiguedad");
		$expected = "0";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#meses_antiguedad");
		$expected = "11";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dias_antiguedad");
		$expected = "330";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#mes_liquidacion");
		$expected = "09";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#ano_liquidacion");
		$expected = "2008";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#periodo_liquidacion");
		$expected = "1Q";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#periodo_liquidacion_completo");
		$expected = "2008091Q";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#fecha_desde_liquidacion");
		$expected = "2008-09-01";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#fecha_desde_liquidacion");
		$expected = "2008-09-01";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#fecha_hasta_liquidacion");
		$expected = "2008-09-15";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dia_hasta_liquidacion");
		$expected = "2008-09-15";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dia_desde_liquidacion");
		$expected = "2008-09-01";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#ano_egreso");
		$expected = "";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#ano_ingreso");
		$expected = "2007";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#mes_egreso");
		$expected = "";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#mes_ingreso");
		$expected = "10";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#mes_ingreso");
		$expected = "10";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dia_egreso");
		$expected = "";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#dia_ingreso");
		$expected = "22";
		$this->assertEqual($expected, $result);
		
		$result = $LiquidacionesController->__getVariableValor("#fecha_actual");
		$expected = date("Y-m-d");
		$this->assertEqual($expected, $result);
	}
	

/**
 * El caso de prueba para la preliquidacion Normal por Hora.
 */	
	function xtestPreliquidacionNormalPorHora() { 
		
		$this->__login();
		
		/**
		* Preparo la data.
		*/
		$data['Formulario']['accion'] = "buscar";
		$data['Extras']['Liquidacion-periodo'] = "2008092Q";
		$data['Condicion']['Relacion-id'] = "2";
		$data['Extras']['Liquidacion-tipo'] = "normal";
		
		$result = $this->testAction('/liquidaciones/preliquidar', 
								array('connection'	=> 'test', 
										'method' 	=> 'post',
		  								'fixturize' => true, 
		  								'return'	=> 'vars',
		  								'data' 		=> $data));
		
		d($result);
		$this->assertEqual(1244, $result['registros'][0]['Liquidacion']['total_pesos']);
		$this->assertEqual(1, count($result['registros'][0]['LiquidacionesError']));
		$this->assertEqual(23, $this->cantidadDetalles);
	}
	
	
	
/**
* Simula un login como administrador.
*/
	function __login() { 
		$Usuario = new UsuarioTest();
		$condiciones['nombre'] = "root";
		$condiciones['clave'] = "x";	
		$usuario = $Usuario->verificarLogin($condiciones);
		$session = &new SessionComponent();
		$session->write('__Usuario', $usuario);
	}
	
} 

?>