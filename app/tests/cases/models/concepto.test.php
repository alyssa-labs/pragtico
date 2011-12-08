<?php 
App::import('Model', 'Concepto');
App::import('Component', 'Session');

class ConceptoTestx extends Concepto {
    var $name = 'Concepto';
    var $useDbConfig = 'test_suite';
}

class ConceptoTestCase extends CakeTestCase {
    var $fixtures = array( 'concepto_test' );

var $usuario = 
Array
(
    'Usuario' => Array
        (
            'id' => "1",
            'nombre' => "root",
            'clave' => "x",
            'nombre_completo' => "Martin Radosta",
            'mail' => "",
            'ultimo_ingreso' => "0000-00-00 00:00:00",
            'estado' => "Activo",
            'created' => "2007-12-17 17:04:55",
            'modified' => "2008-05-12 16:03:59",
            'user_id' => "1",
            'group_id' => "1",
            'permissions' => "500",
            'grupo_id' => "13",
            'grupo_nombre' => "administradores",
            'grupos' => Array
                (
                    '0' => "1",
                    '1' => "4",
                    '2' => "8"
                ),
            'grupo_primario_id' => "1",
            'grupo_primario_nombre' => "administradores",
            'grupo_default_id' => "4",
            'grupo_default' => Array
                (
                    'id' => "4",
                    'empleador_id' => "11",
                    'nombre' => "Optima",
                    'tipo' => "De Grupos",
                    'estado' => "Activo",
                    'observacion' => "",
                    'created' => "2008-05-11 13:57:13",
                    'modified' => "2008-05-15 16:01:58",
                    'user_id' => "1",
                    'group_id' => "1",
                    'permissions' => "500",
                    'GruposUsuario' => Array
                        (
                            'id' => "28",
                            'grupo_id' => "4",
                            'usuario_id' => "1",
                            'tipo' => "Secundario",
                            'estado' => "Activo",
                            'created' => "2008-05-12 15:03:00",
                            'modified' => "2008-05-12 15:03:00",
                            'user_id' => "1",
                            'group_id' => "2",
                            'permissions' => "500"
                        ),
                    'Empleador' => Array
                        (
                            'id' => "11",
                            'localidad_id' => "1",
                            'actividad_id' => "",
                            'cuit' => "30-67755482-3",
                            'nombre' => "Optima SRL",
                            'direccion' => "",
                            'codigo_postal' => "",
                            'barrio' => "",
                            'ciudad' => "",
                            'pais' => "Argentina",
                            'telefono' => "",
                            'pagina_web' => "",
                            'fax' => "",
                            'email' => "",
                            'alta' => "2008-04-24",
                            'redondear' => "Si",
                            'facturar_por_area' => "No",
                            'corresponde_reduccion' => "No",
                            'observacion' => "",
                            'created' => "2008-04-24 10:52:06",
                            'modified' => "2008-04-24 10:52:06",
                            'user_id' => "1",
                            'group_id' => "1",
                            'permissions' => "480"
                        )
                ),
            'preferencias' => Array
                (
                    'buscadores_posteo' => "normal",
                    'busqueda_autoincremental' => "activado",
                    'busqueda_tipo' => "empiece",
                    'filas_por_pagina' => "15",
                    'lov_apertura' => "popup",
                    'navegacion' => "normal",
                    'paginacion' => "ajax"
                )
        ),
    'Grupo' => Array
        (
            '0' => Array
                (
                    'id' => "1",
                    'empleador_id' => "",
                    'nombre' => "administradores",
                    'tipo' => "De Usuarios",
                    'estado' => "Activo",
                    'observacion' => "",
                    'created' => "2007-12-17 17:04:26",
                    'modified' => "2007-12-17 17:04:26",
                    'user_id' => "1",
                    'group_id' => "1",
                    'permissions' => "500",
                    'GruposUsuario' => Array
                        (
                            'id' => "3",
                            'grupo_id' => "1",
                            'usuario_id' => "1",
                            'tipo' => "Primario",
                            'estado' => "Activo",
                            'created' => "2007-12-26 02:26:19",
                            'modified' => "2008-06-22 18:33:06",
                            'user_id' => "1",
                            'group_id' => "1",
                            'permissions' => "500"
                        ),
                    'Empleador' => Array
                        (
                        )
                ),
            '1' => Array
                (
                    'id' => "4",
                    'empleador_id' => "11",
                    'nombre' => "Optima",
                    'tipo' => "De Grupos",
                    'estado' => "Activo",
                    'observacion' => "",
                    'created' => "2008-05-11 13:57:13",
                    'modified' => "2008-05-15 16:01:58",
                    'user_id' => "1",
                    'group_id' => "1",
                    'permissions' => "500",
                    'GruposUsuario' => Array
                        (
                            'id' => "28",
                            'grupo_id' => "4",
                            'usuario_id' => "1",
                            'tipo' => "Secundario",
                            'estado' => "Activo",
                            'created' => "2008-05-12 15:03:00",
                            'modified' => "2008-05-12 15:03:00",
                            'user_id' => "1",
                            'group_id' => "2",
                            'permissions' => "500"
                        ),
                    'Empleador' => Array
                        (
                            'id' => "11",
                            'localidad_id' => "1",
                            'actividad_id' => "",
                            'cuit' => "30-67755482-3",
                            'nombre' => "Optima SRL",
                            'direccion' => "",
                            'codigo_postal' => "",
                            'barrio' => "",
                            'ciudad' => "",
                            'pais' => "Argentina",
                            'telefono' => "",
                            'pagina_web' => "",
                            'fax' => "",
                            'email' => "",
                            'alta' => "2008-04-24",
                            'redondear' => "Si",
                            'facturar_por_area' => "No",
                            'corresponde_reduccion' => "No",
                            'observacion' => "",
                            'created' => "2008-04-24 10:52:06",
                            'modified' => "2008-04-24 10:52:06",
                            'user_id' => "1",
                            'group_id' => "1",
                            'permissions' => "480"
                        )
                ),
            '2' => Array
                (
                    'id' => "8",
                    'empleador_id' => "",
                    'nombre' => "Consultores de Empresas",
                    'tipo' => "De Grupos",
                    'estado' => "Activo",
                    'observacion' => "",
                    'created' => "2008-05-12 16:31:11",
                    'modified' => "2008-05-12 16:31:11",
                    'user_id' => "1",
                    'group_id' => "2",
                    'permissions' => "500",
                    'GruposUsuario' => Array
                        (
                            'id' => "30",
                            'grupo_id' => "8",
                            'usuario_id' => "1",
                            'tipo' => "Secundario",
                            'estado' => "Activo",
                            'created' => "2008-05-12 16:31:25",
                            'modified' => "2008-05-12 16:31:25",
                            'user_id' => "1",
                            'group_id' => "2",
                            'permissions' => "500"
                        ),
                    'Empleador' => Array
                        (
                        )
                )
        )
);



    function testPublished() {
		$session = &new SessionComponent();
		$usuario = $session->write('__Usuario', $this->usuario);
    
        $this->ConceptoTest =& new ConceptoTest();

        $this->ConceptoTest->recursive = -1;
        $result = $this->ConceptoTest->xx();
        $expected = array(
        	array('Concepto' => array ('id' => 1, 'codigo' => 'concepto1', 'nombre' => 'Concepto 1', 'desde' => '2007-03-18', 'hasta' => '2007-03-18'))
        );
        
        $this->assertEqual($result, $expected);
    }
}
?>