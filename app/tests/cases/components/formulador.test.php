<?php
/**
 * Este archivo contiene un caso de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.cases.components
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 906 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-01 13:42:38 -0300 (mar 01 de sep de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */


App::import('Component', 'Formulador');

require_once(APP . "tests" . DS . "cases" . DS . "controllers" . DS . "fake_test_controller.test.php");


/**
 * Caso de prueba para el Component Formulador.
 *
 * @package app.tests
 * @subpackage app.tests.cases.components
 */
class FormuladorComponentTestCase extends CakeTestCase {
	
/**
 * El component que probare.
 *
 * @var array
 * @access public
 */
    var $FormuladorComponentTest;

    
/**
 * Controller que usare en este caso de prueba.
 *
 * @var array
 * @access public
 */
    var $controller;
	
	
/**
 * startCase method.
 *
 * @access public
 */
	function startCase() {

		/*
		include 'PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setCellValue('A1', '=IF(1=2, 1/1, 2)');
		debug($objPHPExcel->getActiveSheet()->getCell('A1')->getCalculatedValue());

		$objPHPExcel->getActiveSheet()->setCellValue('B1', '=IF(1=2, 1/0, 1)');
		d($objPHPExcel->getActiveSheet()->getCell('B1')->getCalculatedValue());
		*/
		
    	$this->FormuladorComponentTest =& new FormuladorComponent();
    	$this->controller = new FakeTestController();
		$this->FormuladorComponentTest->startup(&$this->controller);

    }

    
	function testInformationFuncions() {
        
        $formula = "=if(isblank('2035-12-31'),'1', 2)";
        $result = $this->FormuladorComponentTest->resolver($formula);
        $expected = '2';
        $this->assertEqual($expected, $result);

		$formula = "=if(isblank(0000-00-00),'2035-12-31', 1)";
        $result = $this->FormuladorComponentTest->resolver($formula);
        $expected = '49674';
        $this->assertEqual($expected, $result);
        
		$formula = "=if(isblank(0000-00-00), 1, 2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if(isblank('0000-00-00'), 1, 2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
	}

	function testDivisionByZero() {

		$formula = "=if('mensual' = 'xxxx', 1/0, 1319)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1319';
		$this->assertEqual($expected, $result);
		
		$formula = "=if('mensual' = 'xxxx', if('a' = 'a', (1319.56 / 0)), 1319)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1319';
		$this->assertEqual($expected, $result);
		
		$formula = "=if('mensual' = 'xxxx', if('a' = 'a', (1319.56 / 0)), 1319)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1319';
		$this->assertEqual($expected, $result);
		
		$formula = "=if('mensual' = 'mensual', (1319.56 / 0), 1319.56)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '#DIV/0!';
		$this->assertEqual($expected, $result);
		
		$formula = "=1319   /    0";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '#DIV/0!';
		$this->assertEqual($expected, $result);
	}
	
	function testResolverNombreFormulas() {

		$formula = "=if('F.A.E.C. y S.'='N/A', 'Aporte Solidario', 'F.A.E.C. y S.')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'F.A.E.C. y S.';
		$this->assertEqual($expected, $result);
		
		$formula = "=if 	('mensual'       ='mensual1', 'Basico',         'Horas')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Horas';
		$this->assertEqual($expected, $result);

		$formula = "=if('mensual'= 'mensual', 'Basico', 'Horas')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Basico';
		$this->assertEqual($expected, $result);
		
		$formula = "if('mensual'='mensual',if ('test'='test1','test','test1'),'Horas')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'test1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('mensual' = 'mensual', 'Basico', 'Horas')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Basico';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('Fondo Social'='N/A', 'Aporte Solidario', 'Fondo Social')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Fondo Social';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('Fondo Social'='Fondo Social', 'Aporte Solidario', 'Fondo Social')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Aporte Solidario';
		$this->assertEqual($expected, $result);
	
		$formula = "=if ('N/A'='N/A', 'Aporte Solidario', 'Fondo Social')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'Aporte Solidario';
		$this->assertEqual($expected, $result);
	}
	
	
    function testResolverFechas() {

		$formula = "=datedif(if('2009-03-26' > '2009-03-01', '2009-03-26', '2009-03-01'), if('2010-02-28' < '2009-03-31', '2010-02-29', '2009-03-31')) + if(and(month('2009-03-31') = month('2009-03-01'), year('2009-03-31') = year('2009-03-01')), 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '6';
		$this->assertEqual($expected, $result);

		$formula = "=if('2009-03-31' = '2009-03-31', 1, datedif(if('2009-03-31' > '2009-03-01', '2009-03-31'), if('2010-02-28' < '2009-03-31', '2010-02-29', '2009-03-31')))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);

		$formula = "=datedif(if('2008-02-10' > '2008-02-01', '2008-02-10'), if('2010-02-28' < '2008-02-29', '2010-02-29', '2008-02-29'))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '19';
		$this->assertEqual($expected, $result);

        $formula = "= datedif(if(2009-07-20 > date(year(2009-08-27), 1, 1), 2009-07-20, date(year(2009-08-27), 1, 1)),2009-08-27)";
        $result = $this->FormuladorComponentTest->resolver($formula);
        $expected = '38';
        $this->assertEqual($expected, $result);
        
		$formula = "=datedif(if('2009-02-10' > '2009-02-01', '2009-02-10'), if('2010-02-28' < '2009-02-28', '2010-02-28', '2009-02-28'))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '18';
		$this->assertEqual($expected, $result);

        $formula = '=if(and(("2008-01-01" >= "2007-01-01"), ("2008-01-31" <= "2008-01-28")), 30, 1)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);

		$formula = 'if(and((2008-01-01 >= 2007-01-01), (2008-01-31 <= 2009-01-28)), 30, 1)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '30';
		$this->assertEqual($expected, $result);

		$formula = "=date ( '2008-11-01')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '39753';
		$this->assertEqual($expected, $result);

        $formula = '=if (month(date(2008, 11, 01)) = 11, 1, 0)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);

        $formula = 'date(2007, 12, 21)';
        $result = $this->FormuladorComponentTest->resolver($formula);
        $expected = '39437';
        $this->assertEqual($expected, $result);

		$formula = "=datedif ('2007-12-18', '2007-12-22')";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);

		$formula = '=datedif (date(2007, 12, 18), date(2007, 12, 22), "D")';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
	}

	
    function testResolverMisc() {
		
		$formula = "=int(4.6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
	}
	
    function testResolverAlgebraica() {

		$formula = "=if(5 > 0, ((700.6 + 0) * 5 / 100) * 0, 0 * 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '0';
		$this->assertEqual($expected, $result);
		
		$formula = "=if     ('ax'='ak', if ('j'='j', 3, 4), min(6,3)) + if    (  	5 >    4, 1, 2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
    
		$formula = "=if ('ax'='ak', if ('j'='j', 3, 4), min(6,3)) + if ('uz'='uz', 1, 2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('1z'='2z', min(10,20), max(3,5))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '5';
		$this->assertEqual($expected, $result);

		$formula = "=min(2, if ('ax'='ax', 1, 8), 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
        
		$formula = "=1";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=1+1";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);
		
		$formula = "=(1+1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);

		$formula = "=(1+1)+2";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = "=(1+1)*10";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '20';
		$this->assertEqual($expected, $result);
		
		$formula = "=(2*3)+10";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '16';
		$this->assertEqual($expected, $result);
		
		$formula = "=(2*3)+(4*4)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '22';
		$this->assertEqual($expected, $result);
		
		$formula = "=(10/2)+5+(2*3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '16';
		$this->assertEqual($expected, $result);
		
		$formula = "=((1+1)*5)/((2*2)+1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);
	}

	function testResolverCondicional() {
		$formula = "=if ('9aaBB11'='9aaBB22', if ('s'='s', 1, 2), if ('s'='s', if (1=2, 2, 5), 10))";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '5';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('9aaBB11'='9aaBB11', if ('s'='s', 1, 2), 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
	
		$formula = "=if ('aaBB11'='AAbb22', 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '0';
		$this->assertEqual($expected, $result);
		
		$formula = "=if ('aaBB11'='aaBB11', 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2<>3, 1, 1+1+2*2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2<>2, 1, 1+1+2*2)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '6';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2=2, (1+1+2)*2, 3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '8';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2<4, 1, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2>2, 1, 3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '3';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2=3, 1, 3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '3';
		$this->assertEqual($expected, $result);
		
		$formula = "=if (2=2, 1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '1';
		$this->assertEqual($expected, $result);
		
	}		
	
	function testResolverStrings() {
		$formula = '=left("mi casa es verde", 2)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'mi';
		$this->assertEqual($expected, $result);
		
		$formula = '=right("mi casa es verde", 5)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'verde';
		$this->assertEqual($expected, $result);
	
		$formula = '=mid("mi casa es verde", 4, 4)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = 'casa';
		$this->assertEqual($expected, $result);
	
	}	

	function testResolverFuncionesDeGrupo() {

		$formula = '=max(2, 4, 6)';
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '6';
		$this->assertEqual($expected, $result);
		
		$formula = "=max(-2, -4, -6, 0)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '0';
		$this->assertEqual($expected, $result);
		
		$formula = "=max(-2, -4, -6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '-2';
		$this->assertEqual($expected, $result);
		
		$formula = "=average(2, 4, 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '4';
		$this->assertEqual($expected, $result);
		
		$formula = "=min(2, 4, 6)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '2';
		$this->assertEqual($expected, $result);
		
		$formula = "=min(0, 2, 4, 6, -1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '-1';
		$this->assertEqual($expected, $result);
		
		$formula = "=sum(0, 2, 4, 6, -1)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '11';
		$this->assertEqual($expected, $result);
		
		$formula = "=min(10,20,30,2) + min(100,200,300) + min(200,400,600)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '302';
		$this->assertEqual($expected, $result);
		
		$formula = "=2 + min(10,20,30,2) + max(3,5,7) + sum(5,6,1) -3";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '20';
		$this->assertEqual($expected, $result);
	
		$formula = "=2 + min(10,20,30,2) + max(3,5,7) + sum(5,6,1) -2 + min(2,3)";
		$result = $this->FormuladorComponentTest->resolver($formula);
		$expected = '23';
		$this->assertEqual($expected, $result);
	}	
	
}


?>