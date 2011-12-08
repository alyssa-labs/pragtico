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
class CoeficienteFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Coeficiente';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '50', 'key' => 'index'),
        'tipo' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '15'),
        'valor' => array('type' => 'float', 'null' => false, 'default' => '', 'length' => '10,3'),
        'descripcion' => array('type' => 'text', 'null' => false, 'default' => ''),
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
            'id' => '6',
            'nombre' => 'Hs. normales',
            'tipo' => 'Remunerativo',
            'valor' => '1.810',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '2008-04-01 09:54:45',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '7',
            'nombre' => 'Hs. extras',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '2008-04-10 09:39:02',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '8',
            'nombre' => 'Adicionales',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '9',
            'nombre' => 'Vacaciones',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '10',
            'nombre' => 'SAC',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '11',
            'nombre' => 'Feriados',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '12',
            'nombre' => 'Accidentes',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '13',
            'nombre' => 'Licencias ',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '14',
            'nombre' => 'Premios',
            'tipo' => 'Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '15',
            'nombre' => 'Acuerdos por Convenio',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '16',
            'nombre' => 'Acuerdos por Ley',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '17',
            'nombre' => 'No Remunarativos-especial',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '18',
            'nombre' => 'Adicionales ',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '19',
            'nombre' => 'Viaticos',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '20',
            'nombre' => 'Ticket canasta',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '21',
            'nombre' => 'Ticket multiconsumo',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '22',
            'nombre' => 'Ticket restorant',
            'tipo' => 'No Remunerativo',
            'valor' => '1.250',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '2008-04-01 09:55:42',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '23',
            'nombre' => 'Vacaciones No gozadas',
            'tipo' => 'No Remunerativo',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '24',
            'nombre' => 'Ropa de Trabajo',
            'tipo' => 'Especial',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '25',
            'nombre' => 'Contribuciones Sindicales',
            'tipo' => 'Especial',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '26',
            'nombre' => 'Otros Conceptos',
            'tipo' => 'Especial',
            'valor' => '0.900',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '27',
            'nombre' => 'Sin Costo',
            'tipo' => 'No Facturable',
            'valor' => '0.000',
            'descripcion' => '',
            'created' => '2008-01-01 00:00:00',
            'modified' => '2008-04-01 10:01:38',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        )
    );
}

?>