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
 * @subpackage      app.tests
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 711 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-17 12:47:39 -0300 (vie 17 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

App::import('Component', 'Util');
App::import('Vendor', 'dates', 'pragmatia');
/**
 * La clase encapsula el caso de prueba.
 *
 * @package     pragtico
 * @subpackage  app.tests.cases.behaviors
 */
class UtilComponentTestCase extends CakeTestCase {

/**
 * El component que probare.
 *
 * @var array
 * @access private
 */
    var $UtilComponentTest;


/**
 * En el contructor de la clase, instancio el objeto.
 *
 * @return void.
 * @access private.
 */
    function __construct() {
    	$this->UtilComponentTest =& new UtilComponent();
    }


    function testGetNonWorkingDays() {
        $result = $this->UtilComponentTest->getNonWorkingDays('2009-07-10', '2009-07-20');
        $expected = '4';
        $this->assertEqual($expected, $result);
        
        $result = $this->UtilComponentTest->getNonWorkingDays('2009-07-7', '2009-07-10');
        $expected = '0';
        $this->assertEqual($expected, $result);
    }
    
	function testDateAddWorkingDays() {
        $result = $this->UtilComponentTest->dateAddWorkingDays('2008-12-23', 2, array('2008-05-25'));
        $expected = '2008-12-25';
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateAddWorkingDays('2008-12-23', 2);
        $expected = '2008-12-26';
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateAddWorkingDays('2009-03-27', 2);
        $expected = '2009-03-31';
        $this->assertEqual($expected, $result);

        $result = $this->UtilComponentTest->dateAddWorkingDays('2009-04-15', 2);
        $expected = '2009-04-17';
        $this->assertEqual($expected, $result);

        $result = $this->UtilComponentTest->dateAddWorkingDays('2009-04-15', 0);
        $expected = '2009-04-15';
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateAddWorkingDays('2009-04-16', 2);
        $expected = '2009-04-20';
        $this->assertEqual($expected, $result);

        $result = $this->UtilComponentTest->dateAddWorkingDays('2008-02-28', 1);
        $expected = '2008-02-29';
        $this->assertEqual($expected, $result);

        $result = $this->UtilComponentTest->dateAddWorkingDays('2008-02-28', 2);
        $expected = '2008-03-03';
        $this->assertEqual($expected, $result);
	}

	
	function testDateAdd() {

        $result = Dates::dateAdd('2008-05-18', -365, 'd', array('fromInclusive' => false));
        $expected = '2007-05-19';
        $this->assertEqual($expected, $result);

        $result = Dates::dateAdd('2009-05-18', -365);
        $expected = '2008-05-17';
        $this->assertEqual($expected, $result);

        $result = Dates::dateAdd('2008-03-10', 2, 'd', array('fromInclusive' => false));
        $expected = '2008-03-12';
        $this->assertEqual($expected, $result);

        $result = Dates::dateAdd('1990-11-04', 4);
        $expected = '1990-11-07';
        $this->assertEqual($expected, $result);
	}


    function testDateDiff() {
		
        $result = $this->UtilComponentTest->dateDiff('2007-01-01', '2007-12-31');
        $expected = array('dias' => 365, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateDiff('2007-01-01', '2007-01-02');
        $expected = array('dias' => 2, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateDiff('2007-12-01', '2008-01-02');
        $expected = array('dias' => 33, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);

        $result = $this->UtilComponentTest->dateDiff('2008-12-01', '2009-01-02');
        $expected = array('dias' => 33, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateDiff('2007-01-01', '2007-12-30');
        $expected = array('dias' => 364, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);
		
        $result = $this->UtilComponentTest->dateDiff('2009-01-01', '2009-12-31');
		$expected = array('dias' => 365, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);

        $result = $this->UtilComponentTest->dateDiff('2008-01-01', '2008-12-31');
        $expected = array('dias' => 366, 'horas' => 0, 'minutos' => 0, 'segundos' => 0);
        $this->assertEqual($expected, $result);

    }


/**
 * Compruebo que formatee correctamente.
 * Como ya esta desarrollado el caso de prueba del helper Formato, solo compruebo un caso.
 *
 * @return void.
 * @access public.
 */
	function testFormat() {
	
		$valor = '1000';
		$result = $this->UtilComponentTest->format($valor);
		$expected = '1000,00';
		$this->assertEqual($expected, $result);

        /*
		$valor = 'all';
		$expected = array(	'1' 	=> 'Enero',
							'2' 	=> 'Febrero',
							'3' 	=> 'Marzo',
							'4' 	=> 'Abril',
							'5' 	=> 'Mayo',
							'6' 	=> 'Junio',
							'7' 	=> 'Julio',
							'8' 	=> 'Agosto',
							'9' 	=> 'Setiembre',
							'10' 	=> 'Octubre',
							'11' 	=> 'Noviembre',
							'12' 	=> 'Diciembre');
	    $result = $this->UtilComponentTest->format($valor, array('type' => 'mesEnLetras', 'case' => 'ucfirst'));
	    $this->assertEqual($result, $expected);
        */
	}
	

/**
 * Compruebo la funcion de extraer ids desde un array.
 *
 * @return void.
 * @access public.
 */
    function testExtraerIds() {
    	$expected = array(1, 2, 6, 8, 9);
    	$data = array(	'id_1'	=> 1,
    					'id_2'	=> 1,
    					'id_3'	=> 0,
    					'xid_4'	=> 1,
    					'id_5x'	=> 1,
    					'id_6'	=> '1',
    					'id_7'	=> 'a',
    					'id_8'	=> true,
    					'id_9'	=> 'true',
    					'id_10'	=> false,
    					'id_11'	=> 'false',
    					'id'	=> 1);
	    $result = $this->UtilComponentTest->extraerIds($data);
	    $this->assertEqual($result, $expected);
	}
	
}


?>