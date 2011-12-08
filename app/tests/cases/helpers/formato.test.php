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
 * @subpackage      app.tests.cases.helpers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1023 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-26 22:11:03 -0300 (sáb 26 de sep de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

App::import('Helper', array('Formato', 'Number', 'Time'));

/**
 * Caso de prueba para el Helper Formato.
 *
 * @package app.tests
 * @subpackage app.tests.cases.helpers
 */

class FormatoTest extends CakeTestCase {

/**
 * Helper que usare en este caso de prueba.
 *
 * @var array
 * @access public
 */
	var $formato;

	
/**
 * Metodo que se ejecuta antes de cada test.
 *
 * @access public
 */
	function startTest() {
		$this->formato = new FormatoHelper();
		$this->formato->Number =& new NumberHelper();
		$this->formato->Time =& new TimeHelper();
	}


/**
 * Testing text replacements with conditionals.
 * 
 * @access public
 * @return void
 */
	function testReplaceWithConditionalsAndIterators() {
		
		$texto = null;
		$texto['A12'] = '#*LiquidacionesDetalle.{n}.concepto_nombre*#';
        $texto['A13'] = '#*LiquidacionesDetalle.{n}.concepto_nombre*#';
        $texto['A14'] = '#*LiquidacionesDetalle.{n}.concepto_nombre*#';
        $texto['A15'] = '#*LiquidacionesDetalle.{n}.concepto_nombre*#';
        $texto['A16'] = '#*LiquidacionesDetalle.{n}.concepto_nombre*#';
        $texto['D12'] = "#*if(LiquidacionesDetalle.{n}.valor_cantidad='0.01','')*#";
        $texto['D13'] = "#*if(LiquidacionesDetalle.{n}.valor_cantidad=0.00,'')*#";
        $texto['D14'] = "#*if(LiquidacionesDetalle.{n}.valor_cantidad=0.00,'')*#";
        $texto['D15'] = "#*if(LiquidacionesDetalle.{n}.valor_cantidad=0.00,'')*#";
        $texto['D16'] = "#*if(LiquidacionesDetalle.{n}.valor_cantidad=0.00,'')*#";
        $texto['E12'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['E13'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['E14'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['E15'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['E16'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['F12'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Deduccion',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['F13'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Deduccion',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['F14'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Deduccion',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['F15'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Deduccion',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['F16'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='Deduccion',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['G12'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='No Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['G13'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='No Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['G14'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='No Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['G15'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='No Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
        $texto['G16'] = "#*if(LiquidacionesDetalle.{n}.concepto_tipo='No Remunerativo',LiquidacionesDetalle.{n}.valor,'')*#";
		$reemplazos = array('LiquidacionesDetalle' => array(
			array('concepto_tipo' => 'Remunerativo', 'concepto_nombre' => 'Basico', 'valor' => '700.60', 'valor_cantidad' => '0.00'),
			array('concepto_tipo' => 'Remunerativo', 'concepto_nombre' => 'Antiguedad', 'valor' => '0.00', 'valor_cantidad' => '0.00'),
            array('concepto_tipo' => 'No Remunerativo', 'concepto_nombre' => 'No Rem Concepto', 'valor' => '11.00', 'valor_cantidad' => '0.00'),
            array('concepto_tipo' => 'Deduccion', 'concepto_nombre' => 'Ded Concepto', 'valor' => '18.00', 'valor_cantidad' => '0.00'),
            array('concepto_tipo' => 'No Remunerativo', 'concepto_nombre' => 'No Rem Concepto', 'valor' => '11.00', 'valor_cantidad' => '0.00')));
		$this->formato->setCount(0);
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A12'] = 'Basico';
		$expected['D12'] = '';
		$expected['E12'] = '700.60';
		$expected['F12'] = '';
		$expected['G12'] = '';
		$expected['A13'] = 'Antiguedad';
		$expected['D13'] = '';
		$expected['E13'] = '0.00';
		$expected['F13'] = '';
		$expected['G13'] = '';
        $expected['A14'] = 'No Rem Concepto';
        $expected['D14'] = '';
        $expected['E14'] = '';
        $expected['F14'] = '';
        $expected['G14'] = '11.00';
        $expected['A15'] = 'Ded Concepto';
        $expected['D15'] = '';
        $expected['E15'] = '';
        $expected['F15'] = '18.00';
        $expected['G15'] = '';
        $expected['A16'] = 'No Rem Concepto';
        $expected['D16'] = '';
        $expected['E16'] = '';
        $expected['F16'] = '';
        $expected['G16'] = '11.00';
		$this->assertEqual($expected, $result);

		
		$texto = null;
		$texto['A1'] = 'My name is #*Trabajador.{n}.nombre*#';
		$texto['C1'] = 'My last name is #*Trabajador.{n}.apellido*#.';
		$texto['A2'] = 'My name is #*Trabajador.{n}.nombre*#';
		$texto['C2'] = 'My last name is #*Trabajador.{n}.apellido*#.';
		$reemplazos = array('Trabajador' => array(array('nombre' => 'Martin', 'apellido' => 'Radosta'), array('nombre' => 'Juan', 'apellido' => 'Perez')));
		$this->formato->setCount(0);
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A1'] = 'My name is Martin';
		$expected['C1'] = 'My last name is Radosta.';
		$expected['A2'] = 'My name is Juan';
		$expected['C2'] = 'My last name is Perez.';
		$this->assertEqual($expected, $result);
		

		$texto = null;
		$texto['A1'] = 'My name is #*Trabajador.{n}.nombre*#';
		$texto['B1'] = 'My last name is #*Trabajador.{n}.apellido*#.';
		$texto['A2'] = 'My name is #*Trabajador.{n}.nombre*#';
		$texto['B2'] = 'My last name is #*Trabajador.{n}.apellido*#.';
		$reemplazos = array('Trabajador' => array(array('nombre' => 'Martin', 'apellido' => 'Radosta'), array('nombre' => 'Juan', 'apellido' => 'Perez')));
		$this->formato->setCount(0);
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A1'] = 'My name is Martin';
		$expected['B1'] = 'My last name is Radosta.';
		$expected['A2'] = 'My name is Juan';
		$expected['B2'] = 'My last name is Perez.';
		$this->assertEqual($expected, $result);
		
		
		/**
		* Different.
		*/
		$texto = "My first address was #*if(TrabajadoresDireccion.{n}.pais='Spain','',TrabajadoresDireccion.{n}.calle)*#. My second address was #*TrabajadoresDireccion.{n}.calle*#";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin'), 'TrabajadoresDireccion' => array(array('pais' => 'Argentina', 'calle' => 'O. Mercadillo'), array('pais' => 'Argentina', 'calle' => 'O. Oro')));
		$this->formato->setCount(0);
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = 'My first address was O. Mercadillo. My second address was O. Oro';
		$this->assertEqual($expected, $result);

		
		/**
		* Equal.
		*/
		$texto = "My first address was #*if(TrabajadoresDireccion.{n}.pais='Spain',TrabajadoresDireccion.{n}.calle,'')*#. My second address was #*TrabajadoresDireccion.{n}.calle*#";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin'), 'TrabajadoresDireccion' => array(array('pais' => 'Spain', 'calle' => 'O. Mercadillo'), array('pais' => 'Argentina', 'calle' => 'O. Oro')));
		$this->formato->setCount(0);
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = 'My first address was O. Mercadillo. My second address was O. Oro';
		$this->assertEqual($expected, $result);
		
		
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'My age is #*Trabajador.age*#. You are 18 years old.';
		$texto['C'] = "I am #*if(Trabajador.age<=20,'younger','older')*# than you.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'age' => '20'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'My age is 20. You are 18 years old.';
		$expected['C'] = "I am younger than you.";
		$this->assertEqual($expected, $result);
	}	

/**
 * Testing text replacements with conditionals.
 * 
 * @access public
 * @return void
 */
	function testReplaceWithConditionals() {
		
		/**
		* Minor or equal than.
		*/
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'My age is #*Trabajador.age*#. You are 18 years old.';
		$texto['C'] = "I am #*if(Trabajador.age<=20,'younger','older')*# than you.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'age' => '20'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'My age is 20. You are 18 years old.';
		$expected['C'] = "I am younger than you.";
		$this->assertEqual($expected, $result);
		

		/**
		* Mayor than.
		*/
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'My age is #*Trabajador.age*#. You are 18 years old.';
		$texto['C'] = "I am #*if(Trabajador.age>20,'older','younger')*# than you.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'age' => '20'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'My age is 20. You are 18 years old.';
		$expected['C'] = "I am younger than you.";
		$this->assertEqual($expected, $result);
		
		
		/**
		* False.
		*/
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'I work in #*Trabajador.pais*#.';
		$texto['C'] = "My prefered city is #*if(Trabajador.pais='Argentina','Cordoba','Madrid')*#.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Spain', 'ciudad' => 'Cordoba'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'I work in Spain.';
		$expected['C'] = "My prefered city is Madrid.";
		$this->assertEqual($expected, $result);

		
		/**
		* True.
		*/
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'I work in #*Trabajador.pais*#.';
		$texto['C'] = "My prefered city is #*if(Trabajador.pais='Spain','Madrid',Trabajador.ciudad)*#.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Spain', 'ciudad' => 'Cordoba'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'I work in Spain.';
		$expected['C'] = "My prefered city is Madrid.";
		$this->assertEqual($expected, $result);

		
		/**
		* True.
		*/
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'I work in #*Trabajador.pais*#.';
		$texto['C'] = "My prefered city is #*if(Trabajador.pais='Argentina',Trabajador.ciudad,'')*#.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ciudad' => 'Cordoba'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'I work in Argentina.';
		$expected['C'] = "My prefered city is Cordoba.";
		$this->assertEqual($expected, $result);

		
		/**
		* False.
		*/
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'I work in #*Trabajador.pais*#.';
		$texto['C'] = "My prefered city is #*if(Trabajador.pais='Argentina','',Trabajador.ciudad)*#.";
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ciudad' => 'Cordoba'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'I work in Argentina.';
		$expected['C'] = "My prefered city is .";
		$this->assertEqual($expected, $result);
		
	}
	
/**
 * Testing text replacements in Clear Text.
 * 
 * @access public
 * @return void
 */
	function testReplaceInArray() {
		
		/**
		* Iterations.
		*/
		$texto = null;
		$texto[] = 'My name is #*1*#';
		$texto[] = 'my first address was #*TrabajadoresDireccion.{n}.calle*#.';
		$texto[] = 'My second address was #*TrabajadoresDireccion.{n}.calle*#';
		$patrones = array('1:Trabajador.nombre', 'TrabajadoresDireccion.{n}.calle');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin'), 'TrabajadoresDireccion' => array(array('calle' => 'O. Mercadillo'), array('calle' => 'O. Oro')));
		$this->formato->setCount(0);
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = null;
		$expected[] = 'My name is Martin';
		$expected[] = 'my first address was O. Mercadillo.';
		$expected[] = 'My second address was O. Oro';
		$this->assertEqual($expected, $result);
		
		
		$texto = null;
		$texto[] = 'My name is #*1*##*1:Trabajador.nombre*#';
  		$texto[] = 'I work in #*2*#.#*2:Trabajador.pais*#';
		$texto[] = 'I work at #*Trabajador.work*#.';
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'work' => 'Pragmatia'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = null;
		$expected[] = 'My name is Martin';
		$expected[] = 'I work in Argentina.';
		$expected[] = 'I work at Pragmatia.';
		$this->assertEqual($expected, $result);
		
		
		$texto = null;
		$texto['A'] = 'My name is #*Trabajador.nombre*#';
		$texto['B'] = 'I work in #*Trabajador.pais*#. How are you?';
		$patrones = array('Trabajador.nombre', 'Trabajador.pais');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina'));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = null;
		$expected['A'] = 'My name is Martin';
		$expected['B'] = 'I work in Argentina. How are you?';
		$this->assertEqual($expected, $result);
		
		
		$texto = null;
		$texto[] = 'My name is #*Trabajador.nombre*#';
		$texto[] = 'I work in #*Trabajador.pais*#. How are you?';
		$patrones = array('Trabajador.nombre', 'Trabajador.pais');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina'));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = null;
		$expected[] = 'My name is Martin';
		$expected[] = 'I work in Argentina. How are you?';
		$this->assertEqual($expected, $result);
	}	


/**
 * Testing text replacements in Clear Text.
 * 
 * @access public
 * @return void
 */
	function testReplaceInClearText() {
		
		/**
		* Add format with options on fake field.
		*/
        $texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. My first working day was #*Bar.foo|date:default=>true;format=>d/m/Y*#';
        $patrones = null;
        $reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ingreso' => ''));
        $result = $this->formato->replace($patrones, $reemplazos, $texto);
        $expected = 'My name is Martin, I work in Argentina. My first working day was ' . date('d/m/Y');
        $this->assertEqual($expected, $result);

		
		/**
		* Mix of numeric patterns and common patterns within the text.
		*/
		$texto = 'My name is #*1*#, I work in #*2*#. I work at #*Trabajador.work*#.#*1:Trabajador.nombre*##*2:Trabajador.pais*#';
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'work' => 'Pragmatia'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina. I work at Pragmatia.';
		$this->assertEqual($expected, $result);
		
		/**
		* Iterations with numeric patterns.
		*/
		$texto = 'My name is #*1*#, my first address was #*2*#. My second address was #*2*#';
		$patrones = array('1:Trabajador.nombre', '2:TrabajadoresDireccion.{n}.calle');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin'), 'TrabajadoresDireccion' => array(array('calle' => 'O. Mercadillo'), array('calle' => 'O. Oro')));
		$this->formato->setCount(0);
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, my first address was O. Mercadillo. My second address was O. Oro';
		$this->assertEqual($expected, $result);
		
		/**
		* Iterations.
		*/
		$texto = 'My name is #*1*#, my first address was #*TrabajadoresDireccion.{n}.calle*#. My second address was #*TrabajadoresDireccion.{n}.calle*#';
		$patrones = array('1:Trabajador.nombre', 'TrabajadoresDireccion.{n}.calle');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin'), 'TrabajadoresDireccion' => array(array('calle' => 'O. Mercadillo'), array('calle' => 'O. Oro')));
		$this->formato->setCount(0);
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, my first address was O. Mercadillo. My second address was O. Oro';
		$this->assertEqual($expected, $result);
		
		/**
		* Add format with options and numeric patterns.
		*/
		$texto = 'My name is #*1*#, I work in #*Trabajador.pais*#. My first working day was #*2*#';
		$patrones = array('1:Trabajador.nombre', 'Trabajador.pais', '2:Trabajador.ingreso|date:default=>true;format=>d/m/Y');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ingreso' => ''));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina. My first working day was ' . date('d/m/Y');
		$this->assertEqual($expected, $result);
		
		/**
		* Numeric patterns within the text.
		*/
		$texto = 'My name is #*1*#, I work in #*2*#.#*1:Trabajador.nombre*##*2:Trabajador.pais*#';
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina.';
		$this->assertEqual($expected, $result);
		
		/**
		* Numeric patterns.
		*/
		$texto = 'My name is #*1*#, I work in #*2*#.';
		$patrones = array('1:Trabajador.nombre', '2:Trabajador.pais');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina'));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina.';
		$this->assertEqual($expected, $result);
		
		/**
		* Add format with options.
		*/
        $texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. My first working day was #*Trabajador.ingreso|date:default=>true;format=>d/m/Y*#';
        $patrones = null;
        $reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ingreso' => ''));
        $result = $this->formato->replace($patrones, $reemplazos, $texto);
        $expected = 'My name is Martin, I work in Argentina. My first working day was ' . date('d/m/Y');
        $this->assertEqual($expected, $result);
        
		$texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. My first working day was #*Trabajador.ingreso*#';
		$patrones = array('Trabajador.nombre', 'Trabajador.pais', 'Trabajador.ingreso|date:default=>true;format=>d/m/Y');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ingreso' => ''));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina. My first working day was ' . date('d/m/Y');
		$this->assertEqual($expected, $result);
		
		/**
		* Add format.
		*/
		$texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. My first working day was #*Trabajador.ingreso*#';
		$patrones = array('Trabajador.nombre', 'Trabajador.pais', 'Trabajador.ingreso|date');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina', 'ingreso' => '2008-05-10'));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina. My first working day was 2008-05-10';
		$this->assertEqual($expected, $result);
		
		/**
		* Pattern => Replacement.
		*/
		$texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. How are you?';
		$patrones = array('Trabajador.nombre' => 'Martin', 'Trabajador.pais' => 'Argentina');
		$result = $this->formato->replace($patrones, null, $texto);
		$expected = 'My name is Martin, I work in Argentina. How are you?';
		$this->assertEqual($expected, $result);
		
		/**
		* No Pattern, extract it from text.
		*/
		$texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. How are you?';
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina'));
		$result = $this->formato->replace(null, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina. How are you?';
		$this->assertEqual($expected, $result);
		
		$texto = 'My name is #*Trabajador.nombre*#, I work in #*Trabajador.pais*#. How are you?';
		$patrones = array('Trabajador.nombre', 'Trabajador.pais');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin', 'pais' => 'Argentina'));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin, I work in Argentina. How are you?';
		$this->assertEqual($expected, $result);
		
		$texto = 'My name is #*Trabajador.nombre*#. How are you?';
		$patrones = array('Trabajador.nombre');
		$reemplazos = array('Trabajador' => array('nombre' => 'Martin'));
		$result = $this->formato->replace($patrones, $reemplazos, $texto);
		$expected = 'My name is Martin. How are you?';
		$this->assertEqual($expected, $result);
	}
	

/**
 * Pruebo el formateo de datos.
 * 
 * @access public
 * @return void
 */
	function testformat() {

		Configure::write('Config.language', 'eng');
		
		$valor = '1000';
		$result = $this->formato->format($valor);
		$expected = '1000,00';
		$this->assertEqual($expected, $result);

		$valor = '1000.353';
		$result = $this->formato->format($valor);
		$expected = '1000,35';
		$this->assertEqual($expected, $result);

		$valor = '1';
		$result = $this->formato->format($valor, 'numero');
		$expected = '1,00';
		$this->assertEqual($expected, $result);

		$valor = '1301100.353';
		$result = $this->formato->format($valor, array('places' => '3'));
		$expected = '1301100,353';
		$this->assertEqual($expected, $result);

		$valor = '130.333';
		$result = $this->formato->format($valor, array('type' => 'currency', 'places' => 3));
		$expected = '$ 130,333';
		$this->assertEqual($expected, $result);

		$valor = '130.333';
		$result = $this->formato->format($valor, 'moneda');
		$expected = '$ 130,33';
		$this->assertEqual($expected, $result);
		
		$valor = '130.333';
		$result = $this->formato->format($valor, array('type' => 'percentage', 'places' => 3));
		$expected = '130,333 %';
		$this->assertEqual($expected, $result);
		
		$valor = '130.333';
		$result = $this->formato->format($valor, array('type' => 'percentage'));
		$expected = '130,33 %';
		$this->assertEqual($expected, $result);
		
		$valor = '';
		$result = $this->formato->format($valor, array('type' => 'date'));
		$expected = date('Y-m-d');
		$this->assertEqual($expected, $result);
		
		$valor = '0000-00-00';
		$result = $this->formato->format($valor, array('type' => 'date'));
		$expected = '';
		$this->assertEqual($expected, $result);

		$valor = '';
		$result = $this->formato->format($valor, array('type' => 'date', 'default'=>true));
		$expected = date('Y-m-d');
		$this->assertEqual($expected, $result);
		
		$valor = '';
		$result = $this->formato->format($valor, array('type' => 'date', 'format' => 'd/m/Y'));
		$expected = date('d/m/Y');
		$this->assertEqual($expected, $result);
		
		$valor = '1943-03-10';
		$result = $this->formato->format($valor, array('type' => 'date', 'default'=>false));
		$expected = '1943-03-10';
		$this->assertEqual($expected, $result);
		
		$valor = '';
		$result = $this->formato->format($valor, array('type' => 'date', 'default'=>false));
		$expected = '';
		$this->assertEqual($expected, $result);
		
		$valor = array('dia' => '15', 'mes' => '10', 'ano' => '2005');
		$result = $this->formato->format($valor, array('type' => 'date'));
		$expected = '2005-10-15';
		$this->assertEqual($expected, $result);
		
		$valor = '2005-10-15 10:54';
		$result = $this->formato->format($valor, array('type' => 'date'));
		$expected = '2005-10-15';
		$this->assertEqual($expected, $result);

		$valor = '2005-10-15';
		$result = $this->formato->format($valor, array('type' => 'dateTime'));
		$expected = '2005-10-15 00:00:00';
		$this->assertEqual($expected, $result);

		$valor = '2005-10-15 10:54:32';
		$result = $this->formato->format($valor, array('type' => 'dateTime'));
		$expected = '2005-10-15 10:54:32';
		$this->assertEqual($expected, $result);

		$valor = '2005-10-15 10:54:32';
		$result = $this->formato->format($valor, array('type' => 'dateTime', 'format' => 'H:i'));
		$expected = '2005-10-15 10:54';
		$this->assertEqual($expected, $result);
    
		$valor = '';
		$result = $this->formato->format($valor, array('type' => 'dateTime', 'default' => false));
		$expected = '';
		$this->assertEqual($expected, $result);
		
		$valor = '2005-10-15 10:54:32';
		$result = $this->formato->format($valor, array('type' => 'ano'));
		$expected = '2005';
		$this->assertEqual($expected, $result);
    
		$valor = '2005-10-15 10:54:32';
		$result = $this->formato->format($valor, array('type' => 'mes'));
		$expected = '10';
		$this->assertEqual($expected, $result);
    
		$valor = '2005-10-15 10:54:32';
		$result = $this->formato->format($valor, array('type' => 'dia'));
		$expected = '15';
		$this->assertEqual($expected, $result);

		$valor = '0000-00-00';
		$result = $this->formato->format($valor, array('type' => 'dia'));
		$expected = '';
		$this->assertEqual($expected, $result);
		
		$valor = '2004-02-15';
		$result = $this->formato->format($valor, array('type' => 'ultimoDiaDelMes'));
		$expected = '29';
		$this->assertEqual($expected, $result);
    
		$valor = '2008-12-15 13:34';
		$result = $this->formato->format($valor, array('type' => 'ultimoDiaDelMes'));
		$expected = '31';
		$this->assertEqual($expected, $result);
		
		$valor = '2004-03-01';
		$result = $this->formato->format($valor, array('type' => 'diaAnterior'));
		$expected = '29';
		$this->assertEqual($expected, $result);
		
		$valor = '2005-03-01';
		$result = $this->formato->format($valor, array('type' => 'diaAnterior'));
		$expected = '28';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-12-15';
		$result = $this->formato->format($valor, array('type' => 'diaAnterior'));
		$expected = '14';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-01';
		$result = $this->formato->format($valor, array('type' => 'diaAnterior'));
		$expected = '31';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-12-15';
		$result = $this->formato->format($valor, array('type' => 'mesAnterior'));
		$expected = '11';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-03-22';
		$result = $this->formato->format($valor, array('type' => 'anoAnterior'));
		$expected = '2007';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-15';
		$result = $this->formato->format($valor, array('type' => 'mesAnterior'));
		$expected = '12';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => '1SAnterior'));
		$expected = '20071S';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-08-22';
		$result = $this->formato->format($valor, array('type' => '1SAnterior'));
		$expected = '20081S';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => '2SAnterior'));
		$expected = '20072S';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => '1QAnterior'));
		$expected = '2008011Q';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-03-12';
		$result = $this->formato->format($valor, array('type' => '1QAnterior'));
		$expected = '2008021Q';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-12';
		$result = $this->formato->format($valor, array('type' => '1QAnterior'));
		$expected = '2007121Q';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => '2QAnterior'));
		$expected = '2007122Q';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-03-12';
		$result = $this->formato->format($valor, array('type' => '2QAnterior'));
		$expected = '2008022Q';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-03-22';
		$result = $this->formato->format($valor, array('type' => '2QAnterior'));
		$expected = '2008022Q';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-03-22';
		$result = $this->formato->format($valor, array('type' => 'mensualAnterior'));
		$expected = '200802M';
		$this->assertEqual($expected, $result);
		
		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => 'mensualAnterior'));
		$expected = '200712M';
		$this->assertEqual($expected, $result);
		
        $valor = '20091S';
        $result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'short' => true));
        $expected = '1s 09';
        $this->assertEqual($expected, $result);
        
    	$valor = '2008111Q';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'short' => true));
		$expected = '1q nov 08';
		$this->assertEqual($expected, $result);
		
    	$valor = '2008042Q';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'upper'));
		$expected = '2Q ABR 08';
		$this->assertEqual($expected, $result);
		
    	$valor = '200804';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'upper'));
		$expected = 'ABR 08';
		$this->assertEqual($expected, $result);
		
    	$valor = '200804M';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'short' => true, 'case' => 'upper'));
		$expected = 'ABR 08';
		$this->assertEqual($expected, $result);
		
    	$valor = '20081S';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'case' => 'ucfirst'));
		$expected = 'Enero a junio de 2008';
		$this->assertEqual($expected, $result);

    	$valor = '2008021Q';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'case' => 'ucfirst'));
		$expected = 'Primera quincena de febrero de 2008';
		$this->assertEqual($expected, $result);
    
    	$valor = '200802';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'case' => 'ucfirst'));
		$expected = 'Febrero de 2008';
		$this->assertEqual($expected, $result);
		
    	$valor = '20082';
		$result = $this->formato->format($valor, array('type' => 'periodoEnLetras', 'case' => 'upper'));
		$expected = 'FEBRERO DE 2008';
		$this->assertEqual($expected, $result);

    	$valor = '200811';
		$result = $this->formato->format($valor, 'periodoEnLetras');
		$expected = 'noviembre de 2008';
		$this->assertEqual($expected, $result);

		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => 'mesEnLetras', 'case' => 'ucfirst'));
		$expected = 'Enero';
		$this->assertEqual($expected, $result);

		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => 'mesEnLetras'));
		$expected = 'enero';
		$this->assertEqual($expected, $result);

		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => 'mesEnLetras', 'case' => 'upper'));
		$expected = 'ENERO';
		$this->assertEqual($expected, $result);

		$valor = '2008-01-22';
		$result = $this->formato->format($valor, array('type' => 'mesEnLetras', 'short' => true, 'case' => 'upper'));
		$expected = 'ENE';
		$this->assertEqual($expected, $result);

		$valor = 'all';
		$result = $this->formato->format($valor, array('type' => 'mesEnLetras', 'case' => 'ucfirst'));
		$expected = Array(
			'1' => 'Enero',
			'2' => 'Febrero',
			'3' => 'Marzo',
			'4' => 'Abril',
			'5' => 'Mayo',
			'6' => 'Junio',
			'7' => 'Julio',
			'8' => 'Agosto',
			'9' => 'Septiembre',
			'10' => 'Octubre',
			'11' => 'Noviembre',
			'12' => 'Diciembre');
		$this->assertEqual($expected, $result);


		$valor = 'all';
		$result = $this->formato->format($valor, array('type' => 'mesEnLetras', 'case' => 'ucfirst', 'keyStart' => 0));
		$expected = Array(
            '0' => 'Enero',
            '1' => 'Febrero',
            '2' => 'Marzo',
            '3' => 'Abril',
            '4' => 'Mayo',
            '5' => 'Junio',
            '6' => 'Julio',
            '7' => 'Agosto',
            '8' => 'Septiembre',
            '9' => 'Octubre',
            '10' => 'Noviembre',
            '11' => 'Diciembre');
		$this->assertEqual($expected, $result);
		

		$valor = '2008A';
		$result = $this->formato->format($valor, array('type' => 'periodo'));
		$expected = Array (
			'periodoCompleto' => '2008A',
			'ano' => '2008',
			'mes' => '00',
			'periodo' => 'A',
			'desde' => '2008-01-01',
			'hasta' => '2008-12-31');
		$this->assertEqual($expected, $result);
		
		$valor = '20081S';
		$result = $this->formato->format($valor, array('type' => 'periodo'));
		$expected = Array (
			'periodoCompleto' => '20081S',
			'ano' => '2008',
			'mes' => '00',
			'periodo' => '1S',
			'desde' => '2008-01-01',
			'hasta' => '2008-06-30');
		$this->assertEqual($expected, $result);
		
		$valor = '20082S';
		$result = $this->formato->format($valor, array('type' => 'periodo'));
		$expected = Array (
			'periodoCompleto' => '20082S',
			'ano' => '2008',
			'mes' => '00',
			'periodo' => '2S',
			'desde' => '2008-07-01',
			'hasta' => '2008-12-31');
		$this->assertEqual($expected, $result);
		
		$valor = '200808';
		$result = $this->formato->format($valor, array('type' => 'periodo'));
		$expected = Array (
			'periodoCompleto' => '200808',
			'ano' => '2008',
			'mes' => '08',
			'periodo' => array('M', '1Q', '2Q', 'F'),
			'desde' => '2008-08-01',
			'hasta' => '2008-08-31');
		$this->assertEqual($expected, $result);
		
		$valor = '200808M';
		$result = $this->formato->format($valor, array('type' => 'periodo'));
		$expected = Array (
			'periodoCompleto' => '200808M',
			'ano' => '2008',
			'mes' => '08',
			'periodo' => 'M',
			'desde' => '2008-08-01',
			'hasta' => '2008-08-31');
		$this->assertEqual($expected, $result);
		
		$valor = '2008082Q';
		$result = $this->formato->format($valor, array('type' => 'periodo'));
		$expected = Array (
			'periodoCompleto' => '2008082Q',
			'ano' => '2008',
			'mes' => '08',
			'periodo' => '2Q',
			'desde' => '2008-08-16',
			'hasta' => '2008-08-31');
		$this->assertEqual($expected, $result);
		
		$valor = '2008081Q';
		$result = $this->formato->format($valor, 'periodo');
		$expected = Array (
			'periodoCompleto' => '2008081Q',
			'ano' => '2008',
			'mes' => '08',
			'periodo' => '1Q',
			'desde' => '2008-08-01',
			'hasta' => '2008-08-15');
		$this->assertEqual($expected, $result);

		$valor = '2008081QQ';
		$result = $this->formato->format($valor, 'periodo');
		$expected =false;
		$this->assertFalse($result);
		
		$valor = '200';
		$result = $this->formato->format($valor, array('type' => 'numeroEnLetras', 'option' => 'moneda', 'case' => 'lower'));
		$expected = 'pesos doscientos';
		$this->assertEqual($expected, $result);
		
		$valor = '1';
		$result = $this->formato->format($valor, array('type' => 'numeroEnLetras', 'option' => 'palabras', 'case' => 'lower'));
		$expected = 'uno';
		$this->assertEqual($expected, $result);
		
		$valor = '234,00';
		$result = $this->formato->format($valor, array('type' => 'numeroEnLetras', 'option' => 'moneda', 'ceroCents'=>true, 'case' => 'upper'));
		$expected = 'PESOS DOSCIENTOS TREINTA Y CUATRO CON CERO CENTAVOS';
		$this->assertEqual($expected, $result);
		
		$valor = '103.21';
		$result = $this->formato->format($valor, array('type' => 'numeroEnLetras', 'option' => 'moneda'));
		$expected = 'pesos ciento tres con veintiun centavos';
		$this->assertEqual($expected, $result);
		
		$valor = '2008.34';
		$result = $this->formato->format($valor, array('type' => 'numeroEnLetras'));
		$expected = 'dos mil ocho con treinta y cuatro';
		$this->assertEqual($expected, $result);
		
		$valor = '1008.345';
		$result = $this->formato->format($valor, array('type' => 'numeroEnLetras', 'case' => 'lower'));
		$expected = 'mil ocho con treinta y cinco';
		$this->assertEqual($expected, $result);
		
		$valor = '1008.345';
		$result = $this->formato->format($valor, array('places' => 3, 'type' => 'numeroEnLetras', 'case' => 'lower'));
		$expected = 'mil ocho con trescientos cuarenta y cinco';
		$this->assertEqual($expected, $result);

    }
	

/**
 * Metodo que se ejecuta despues de cada test.
 *
 * @access public
 */
	function endTest() {
		unset($this->formato);
		ClassRegistry::flush();
	}
	
}


?>