<?php
/**
 * Este archivo contiene un model generico (fake) para los casos de pruebas.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

App::import('Component', array('Session', 'Util')); 
App::import('Model', array('Liquidacion', 'Relacion', 'Trabajador', 'Variable'));
 
/**
 * La clase para un para un caso de prueba generico (fake).
 *
 * @package app.tests
 * @subpackage app.tests.models
 */
class LiquidacionTest extends Liquidacion {

	
/**
 * Indico en nombre del model.
 *
 * @var string
 * @access public
 */
	var $name = 'Liquidacion';
	
	
/**
 * Indico que use la conexion de prueba (test).
 *
 * @var string
 * @access public
 */
	var $useDbConfig = 'test';


 /**
    var $hasMany = array();
    var $hasOne = array();
    var $belongsTo = array();
    var $hasAndBelonsToMany = array();
 */   

}


class VariableTest extends Variable {

    
/**
 * Indico en nombre del model.
 *
 * @var string
 * @access public
 */
    var $name = 'Variable';
    
    
/**
 * Indico que use la conexion de prueba (test).
 *
 * @var string
 * @access public
 */
    var $useDbConfig = 'test';


 /**
    var $hasMany = array();
    var $hasOne = array();
    var $belongsTo = array();
    var $hasAndBelonsToMany = array();
 */   

}


class LiquidacionTestCase extends CakeTestCase {


    var $fixtures = array('trabajador', 'liquidacion', 'relacion', 'variable', 'convenio', 'convenios_informacion', 'ausencia', 'ausencias_seguimiento', 'ausencias_motivo', 'hora', 'concepto');


/**
 * __detachBehaviors method
 *
 * @access private
 * @return void
 */
    function __detachBehaviors(&$model, $whiteList = array()) {
        foreach ($model->Behaviors->attached() as $behavior) {
            if (!in_array($behavior, $whiteList)) {
                $model->Behaviors->detach($behavior);
            }
        }
    }


/**
 * startTest method
 *
 * @access public
 * @return void
 */
    function startTest() {
        $this->Liquidacion = new LiquidacionTest();
    }
    

	function testConceptos() {
        /**
         * Search for a relation.
         */
        $contain = array(   'ConveniosCategoria.ConveniosCategoriasHistorico',
                            'Trabajador.ObrasSocial',
                            'Empleador');
        $this->Liquidacion->Relacion->contain($contain);
        $this->Liquidacion->setRelationship($this->Liquidacion->Relacion->findById(2));
		

        /** Get all vars. */
        $Variable = new VariableTest();
        $this->__detachBehaviors($Variable);
        $vars = Set::combine($Variable->find('all', array('order' => false, 'recursive' => -1)), '{n}.Variable.nombre', '{n}.Variable');
		
        /** Set vars and period. */
        $this->Liquidacion->setVar($vars);
        $this->Liquidacion->setVar('#tipo_liquidacion', 'normal');
        $this->Liquidacion->setPeriod('2008091Q');

		/** Find concepts. */
		$opcionesFindConcepto = null;
		$opcionesFindConcepto['desde'] = $this->Liquidacion->getVarValue("#fecha_desde_liquidacion");
		$opcionesFindConcepto['hasta'] = $this->Liquidacion->getVarValue("#fecha_hasta_liquidacion");
		foreach ($this->Liquidacion->Relacion->RelacionesConcepto->Concepto->findConceptos('Relacion', array_merge(array('relacion' => $this->Liquidacion->getRelationship()), $opcionesFindConcepto)) as $conceptData) {
			d($this->Liquidacion->__getConceptValue($conceptData));
		}
	}
	
    
    function testVariables() {
        
        //$this->Liquidacion = new LiquidacionTest();
        $this->__detachBehaviors($this->Liquidacion, array('Util', 'Containable'));
        $this->__detachBehaviors($this->Liquidacion->Relacion, array('Containable'));
        $this->__detachBehaviors($this->Liquidacion->Relacion->Ausencia, array('Util', 'Containable'));
        $this->__detachBehaviors($this->Liquidacion->Relacion->Hora);

        /**
         * Get all vars.
         */
        $Variable = new VariableTest();
        $this->__detachBehaviors($Variable);
        $vars = Set::combine($Variable->find('all', array('order' => false, 'recursive' => -1)), '{n}.Variable.nombre', '{n}.Variable');


        /**
         * Set vars.
         */
        $this->Liquidacion->setVar($vars);

        
        /**
         * Search for a relation.
         */
        $contain = array(   'ConveniosCategoria.ConveniosCategoriasHistorico',
                            'Trabajador.ObrasSocial',
                            'Empleador');
        $this->Liquidacion->Relacion->contain($contain);
        $this->Liquidacion->setRelationship($this->Liquidacion->Relacion->findById(2));
        
        
        /**
        * Seteo las variables que vienen dadas por informacion del convenio.
        */
        $this->__detachBehaviors($this->Liquidacion->Relacion->ConveniosCategoria->Convenio->ConveniosInformacion);
        $informaciones = $this->Liquidacion->Relacion->ConveniosCategoria->Convenio->getInformacion(Set::extract('/ConveniosCategoria/convenio_id', $this->Liquidacion->getRelationship()));
        $this->Liquidacion->setVar($informaciones[2]);
        
        
        /**
         * Tests for normal receipt.
         */
        $this->Liquidacion->setVar('#tipo_liquidacion', 'normal');
        $this->Liquidacion->setPeriod('2008091Q');
        
        
        $result = $this->Liquidacion->getVarValue('#tipo_liquidacion');
        $expected = 'normal';
        $this->assertEqual($expected, $result);

        $result = $this->Liquidacion->getVarValue('#dias_corridos_periodo');
        $expected = '15';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_por_mes');
        $expected = '176';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#dia_del_gremio');
        $expected = '13';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#ausencias_injustificadas');
        $expected = '2';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#ausencias_justificadas');
        $expected = '4';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas');
        $expected = '10';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_extra_50');
        $expected = '65';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_extra_100');
        $expected = '31';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_ajuste');
        $expected = '32';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_ajuste_extra_50');
        $expected = '21.30';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_ajuste_extra_100');
        $expected = '24.30';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_nocturna');
        $expected = '3.70';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_extra_nocturna_50');
        $expected = '1.70';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_extra_nocturna_100');
        $expected = '0.70';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_ajuste_nocturna');
        $expected = '0.70';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_ajuste_extra_nocturna_50');
        $expected = '3.20';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#horas_ajuste_extra_nocturna_100');
        $expected = '5.30';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#anos_antiguedad');
        $expected = '0';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#meses_antiguedad');
        $expected = '11';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#dias_antiguedad');
        $expected = '330';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#mes_liquidacion');
        $expected = '09';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#ano_liquidacion');
        $expected = '2008';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#periodo_liquidacion');
        $expected = '1Q';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#periodo_liquidacion_completo');
        $expected = '2008091Q';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#fecha_desde_liquidacion');
        $expected = '2008-09-01';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#fecha_desde_liquidacion');
        $expected = '2008-09-01';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#fecha_hasta_liquidacion');
        $expected = '2008-09-15';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#dia_hasta_liquidacion');
        $expected = '2008-09-15';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#dia_desde_liquidacion');
        $expected = '2008-09-01';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#ano_egreso');
        $expected = '';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#ano_ingreso');
        $expected = '2007';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#mes_egreso');
        $expected = '';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#mes_ingreso');
        $expected = '10';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#mes_ingreso');
        $expected = '10';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#dia_egreso');
        $expected = '';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#dia_ingreso');
        $expected = '22';
        $this->assertEqual($expected, $result);
        
        $result = $this->Liquidacion->getVarValue('#fecha_actual');
        $expected = date('Y-m-d');
        $this->assertEqual($expected, $result);


        /**
         * Tests for vacation receipt.
         */
		$this->Liquidacion->setPeriod('2008');
		$this->Liquidacion->setVar('#fecha_ingreso', '2002-07-01');
		$this->Liquidacion->setVar('#fecha_egreso', '2009-01-5');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 21;
        $this->assertEqual($expected, $result);
		
		$this->Liquidacion->setPeriod('2008');
		$this->Liquidacion->setVar('#fecha_ingreso', '1970-07-01');
		$this->Liquidacion->setVar('#fecha_egreso', '2009-01-5');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 35;
        $this->assertEqual($expected, $result);
		
		$this->Liquidacion->setPeriod('2008');
		$this->Liquidacion->setVar('#fecha_ingreso', '2002-06-01');
		$this->Liquidacion->setVar('#fecha_egreso', '2009-01-5');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 21;
        $this->assertEqual($expected, $result);
		
        $this->Liquidacion->setPeriod('2008');
		$this->Liquidacion->setVar('#fecha_ingreso', '2007-06-12');
		$this->Liquidacion->setVar('#fecha_egreso', '2010-12-31');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 14;
        $this->assertEqual($expected, $result);

		$this->Liquidacion->setPeriod('2007');
		$this->Liquidacion->setVar('#fecha_ingreso', '2007-06-12');
		$this->Liquidacion->setVar('#fecha_egreso', '2007-12-31');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 14;
        $this->assertEqual($expected, $result);

		$this->Liquidacion->setPeriod('2007');
		$this->Liquidacion->setVar('#fecha_ingreso', '2007-07-12');
		$this->Liquidacion->setVar('#fecha_egreso', '2007-12-31');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 5;
        $this->assertEqual($expected, $result);

		$this->Liquidacion->setPeriod('2007');
		$this->Liquidacion->setVar('#fecha_ingreso', '2007-06-29');
		$this->Liquidacion->setVar('#fecha_egreso', '2007-12-31');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 14;
        $this->assertEqual($expected, $result);
		
		$this->Liquidacion->setPeriod('2007');
		$this->Liquidacion->setVar('#fecha_ingreso', '2007-07-01');
		$this->Liquidacion->setVar('#fecha_egreso', '2007-12-31');
		unset($this->Liquidacion->__variables['#dias_vacaciones']['valor']);
        $result = $this->Liquidacion->getVarValue('#dias_vacaciones');
        $expected = 6;
        $this->assertEqual($expected, $result);
    }
    
    
    function testGetReceiptSac() {
        $this->Liquidacion = ClassRegistry::init('Liquidacion');
        $this->Liquidacion->Relacion = ClassRegistry::init('Relacion');
        $this->__detachBehaviors($this->Liquidacion->Relacion);
        $this->Liquidacion->Relacion->Behaviors->attach('Containable');

        $relationships = $this->Liquidacion->Relacion->find('all', array('contain' => array('Trabajador', 'Empleador'), 'limit' => 1));

        /** Complete Half */
        $relationships[0]['Relacion']['ingreso'] = '2004-05-02';
        $relationships[0]['Relacion']['egreso'] = '2010-01-01';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2007;
        $options['january'] = 1538.50;
        $options['february'] = 2300.23;
        $options['march'] = 1450.85;
        $options['april'] = 900.55;
        $options['may'] = 2301.66;
        $options['june'] = 1256.25;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 1150.83);
        
        /** Complete Half (leap year) */
        $relationships[0]['Relacion']['ingreso'] = '2004-05-02';
        $relationships[0]['Relacion']['egreso'] = '2010-01-01';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2008;
        $options['january'] = 1538.50;
        $options['february'] = 2300.23;
        $options['march'] = 1450.85;
        $options['april'] = 900.55;
        $options['may'] = 2301.66;
        $options['june'] = 1256.25;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 1150.83);


        /** Start 2007-07-04 */
        $relationships[0]['Relacion']['ingreso'] = '2007-04-07';
        $relationships[0]['Relacion']['egreso'] = '2010-01-01';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2007;
        $options['april'] = 900.55;
        $options['may'] = 2301.66;
        $options['june'] = 1256.25;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 540.45);
        
        
        /** Start 2008-07-04 (leap year) */
        $relationships[0]['Relacion']['ingreso'] = '2008-04-07';
        $relationships[0]['Relacion']['egreso'] = '2010-01-01';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2008;
        $options['april'] = 900.55;
        $options['may'] = 2301.66;
        $options['june'] = 1256.25;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 537.48);
        
        
        /** End 2008-03-23 */
        $relationships[0]['Relacion']['ingreso'] = '2005-04-08';
        $relationships[0]['Relacion']['egreso'] = '2007-03-23';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2007;
        $options['january'] = 1538.50;
        $options['february'] = 2300.23;
        $options['march'] = 1450.85;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 521.05);
        

        /** End 2008-03-23 (leap year) */
        $relationships[0]['Relacion']['ingreso'] = '2005-04-08';
        $relationships[0]['Relacion']['egreso'] = '2008-03-23';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2008;
        $options['january'] = 1538.50;
        $options['february'] = 2300.23;
        $options['march'] = 1450.85;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 524.50);
        
        
        /** Start 2007-02-16, End 2007-05-28 */
        $relationships[0]['Relacion']['ingreso'] = '2007-02-16';
        $relationships[0]['Relacion']['egreso'] = '2007-05-28';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2007;
        $options['february'] = 2300.23;
        $options['march'] = 1450.85;
        $options['april'] = 900.55;
        $options['may'] = 2301.66;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 648.53);
        

        /** Start 2008-02-16, End 2008-05-28 (leap year) */
        $relationships[0]['Relacion']['ingreso'] = '2008-02-16';
        $relationships[0]['Relacion']['egreso'] = '2008-05-28';
        $options = null;
        $options['period'] = '1';
        $options['year'] = 2008;
        $options['february'] = 2300.23;
        $options['march'] = 1450.85;
        $options['april'] = 900.55;
        $options['may'] = 2301.66;
        $result = $this->Liquidacion->getReceipt($relationships, 'sac', $options);
        $this->assertEqual($result, 651.29);
    }

}

?>