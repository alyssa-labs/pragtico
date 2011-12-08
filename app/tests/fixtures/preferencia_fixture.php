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
class PreferenciaFixture extends CakeTestFixture {


/**
 * El nombre de este Fixture.
 *
 * @var array
 * @access public
 */
    var $name = 'Preferencia';


/**
 * La definicion de la tabla.
 *
 * @var array
 * @access public
 */
    var $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => '', 'length' => '11', 'key' => 'primary'),
        'nombre' => array('type' => 'string', 'null' => false, 'default' => '', 'length' => '100', 'key' => 'unique'),
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
            'id' => '1',
            'nombre' => 'lov_apertura',
            'descripcion' => 'Especifica como debe mostrarse un control de tipo lov:
- div: Se usa para mostrarlo como un div dentro de la pagina actual.
- popup: abre una nueva ventana. Esta opcion normalmente no se recomienda porque  los bloqueadores de popup de los browsers la bloquean.',
            'created' => '0000-00-00 00:00:00',
            'modified' => '2008-05-09 00:05:27',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '2',
            'nombre' => 'buscadores_posteo',
            'descripcion' => 'Indica de que forma se realizaran las busquedas.',
            'created' => '0000-00-00 00:00:00',
            'modified' => '2008-09-04 12:48:08',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '3',
            'nombre' => 'filas_por_pagina',
            'descripcion' => '',
            'created' => '2008-01-31 18:55:33',
            'modified' => '2008-04-23 21:41:25',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '4',
            'nombre' => 'navegacion',
            'descripcion' => 'Se refiere a la navegacion general del sistema.',
            'created' => '2008-01-31 18:56:42',
            'modified' => '2008-06-11 18:55:05',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '5',
            'nombre' => 'paginacion',
            'descripcion' => 'Define si la paginacion debe hacerse via ajax o de la manera tradicional.',
            'created' => '2008-02-01 09:54:19',
            'modified' => '2008-05-09 00:04:39',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '6',
            'nombre' => 'busqueda_autoincremental',
            'descripcion' => '',
            'created' => '2008-02-01 09:57:52',
            'modified' => '2008-07-14 13:45:12',
            'user_id' => '1',
            'role_id' => '1',
            'group_id' => '0',
            'permissions' => '500',
        ),
        array(
            'id' => '7',
            'nombre' => 'busqueda_tipo',
            'descripcion' => '',
            'created' => '2008-07-31 18:36:06',
            'modified' => '2008-07-31 18:36:06',
            'user_id' => '1',
            'role_id' => '13',
            'group_id' => '0',
            'permissions' => '500',
        ),
    );
}

?>