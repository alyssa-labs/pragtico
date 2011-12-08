<?php
/**
 * Este archivo contiene los datos de un fixture para los casos de prueba.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.fixtures
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase para un fixture para un caso de prueba.
 *
 * @package app.tests
 * @subpackage app.tests.fixtures
 */
class EmpleadorFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Empleador';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'localidad_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'actividad_id' => array('type' => 'integer', 'null' => '1', 'default' => '', 'length' => '11', 'key' => 'index'),
        'cuit' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '13', 'key' => 'unique'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'direccion' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'codigo_postal' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '6'),
        'barrio' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'ciudad' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'pais' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'telefono' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'pagina_web' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'fax' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50'),
        'email' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '150'),
        'alta' => array('type' => 'date', 'null' => false, 'default' => '0000-00-00'),
        'redondear' => array('null' => false, 'default' => 'Si', 'length' => '2'),
        'facturar_por_area' => array('null' => false, 'default' => 'No', 'length' => '2'),
        'corresponde_reduccion' => array('null' => false, 'default' => 'No', 'length' => '2'),
        'observacion' => array('type' => 'text', 'null' => false, 'default' => ''),
        'created' => array('type' => 'datetime', 'null' => false),
        'modified' => array('type' => 'datetime', 'null' => false),
        'user_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'role_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'group_id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
        'permissions' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'index'),
  );


/**
 * Los registros.
 *
 * @var array
 * @access public
 */
    var $records = array(
        array(
            'id' => '1',
            'localidad_id' => '45',
            'actividad_id' => '2301',
            'cuit' => '30-70981114-9',
            'nombre' => 'Empleador 1 de Comercio',
            'direccion' => 'Obispo Oro N° 344',
            'codigo_postal' => '5000',
            'barrio' => 'Nueva Cordoba',
            'ciudad' => 'Cordoba',
            'pais' => 'Argentina',
            'telefono' => '',
            'pagina_web' => '',
            'fax' => '',
            'email' => '',
            'alta' => '2006-10-01',
            'redondear' => 'No',
            'facturar_por_area' => 'No',
            'corresponde_reduccion' => 'No',
            'observacion' => '',
            'created' => '2007-11-14 22:26:46',
            'modified' => '2008-10-14 19:13:56',
            'user_id' => '1',
            'role_id' => '7',
            'group_id' => '1',
            'permissions' => '496',
    	),
        array(
            'id' => '2',
            'localidad_id' => '1',
            'actividad_id' => '1',
            'cuit' => '20-05092511-1',
            'nombre' => 'Empleador 2 de la Construccion',
            'direccion' => 'C.DE REMEDIOS 696',
            'codigo_postal' => '',
            'barrio' => '',
            'ciudad' => 'CORDOBA',
            'pais' => 'Argentina',
            'telefono' => '0351 4972136',
            'pagina_web' => '',
            'fax' => '',
            'email' => '',
            'alta' => '2007-05-07',
            'redondear' => 'Si',
            'facturar_por_area' => 'No',
            'corresponde_reduccion' => 'No',
            'observacion' => '',
            'created' => '2008-10-06 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
    	),
        array(
            'id' => '4',
            'localidad_id' => '1',
            'actividad_id' => '1',
            'cuit' => '20-06072850-0',
            'nombre' => 'Enrici muguel angel',
            'direccion' => 'PUEYRREDON 5391',
            'codigo_postal' => '',
            'barrio' => '',
            'ciudad' => 'ROSARIO',
            'pais' => 'Argentina',
            'telefono' => '0341 4635038',
            'pagina_web' => '',
            'fax' => '',
            'email' => '',
            'alta' => '2007-11-26',
            'redondear' => 'Si',
            'facturar_por_area' => 'No',
            'corresponde_reduccion' => 'No',
            'observacion' => '',
            'created' => '2008-10-06 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
   		),
        array(
            'id' => '5',
            'localidad_id' => '1',
            'actividad_id' => '1',
            'cuit' => '20-06502376-9',
            'nombre' => 'Sucesion de valotto hugo antonio',
            'direccion' => 'RINCON N',
            'codigo_postal' => '',
            'barrio' => '',
            'ciudad' => 'CORDOBA',
            'pais' => 'Argentina',
            'telefono' => '0351 4517015',
            'pagina_web' => '',
            'fax' => '',
            'email' => '',
            'alta' => '2006-11-16',
            'redondear' => 'Si',
            'facturar_por_area' => 'No',
            'corresponde_reduccion' => 'No',
            'observacion' => '',
            'created' => '2008-10-06 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
    	),
        array(
            'id' => '6',
            'localidad_id' => '1',
            'actividad_id' => '1',
            'cuit' => '20-06515548-7',
            'nombre' => 'Valotto luis celestino',
            'direccion' => 'RINCON 2081',
            'codigo_postal' => '',
            'barrio' => 'GENERAL PAZ',
            'ciudad' => 'CORDOBA',
            'pais' => 'Argentina',
            'telefono' => '0351 4516475',
            'pagina_web' => '',
            'fax' => '',
            'email' => '',
            'alta' => '2007-08-29',
            'redondear' => 'Si',
            'facturar_por_area' => 'No',
            'corresponde_reduccion' => 'No',
            'observacion' => '',
            'created' => '2008-10-06 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '7',
            'group_id' => '4',
            'permissions' => '496',
    	)
	);
}

?>