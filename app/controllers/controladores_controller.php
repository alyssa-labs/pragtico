<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los controladores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1288 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-04-26 13:45:52 -0300 (lun 26 de abr de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los controladores.
 *
 * Se refiere a los controllers del framework cakephp.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class ControladoresController extends AppController {

    var $paginate = array(
        'order' => array(
            'Controlador.nombre'  => 'asc'
        )
    );

/**
 * actualiazar_controladores.
 * Actualiza la base de datos (agregando o eliminando controladores) de acuerdo a lo que hay en el codigo fuente. .
 */
	function actualizar_controladores() {
		$controllerClasses = Configure::listObjects('controller');
        
		$accionesPredefinidas[] = array('estado'	=> 'Activo',
										'nombre'	=> 'add',
										'etiqueta'	=> 'Agregar');
		$accionesPredefinidas[] = array('estado'	=> 'Activo',
										'nombre'	=> 'edit',
										'etiqueta'	=> 'Modificar');
		$accionesPredefinidas[] = array('estado'	=> 'Activo',
										'nombre'	=> 'delete',
										'etiqueta'	=> 'Eliminar');
		$accionesPredefinidas[] = array('estado'	=> 'Activo',
										'nombre'	=> 'permisos',
										'etiqueta'	=> 'Permisos');
										
		$accionesPredefinidas = null;
		$accionesPredefinidas[] = 'index';
		$accionesPredefinidas[] = 'add';
		$accionesPredefinidas[] = 'edit';
		$accionesPredefinidas[] = 'delete';
		$accionesPredefinidas[] = 'deleteMultiple';
		$accionesPredefinidas[] = 'permisos';

		$parentActions = get_class_methods('AppController');
        foreach ($controllerClasses as $controller) {
            if ($controller != 'App' && $controller != 'Pages') {
                $nombreArchivo = inflector::underscore($controller).'_controller.php';
                $archivo = CONTROLLERS . $nombreArchivo;
                require_once($archivo);
                $className = $controller . 'Controller';
                $actions = get_class_methods($className);

                foreach ($actions as $k => $v) {
                    if ($v{0} == '_') {
                        unset($actions[$k]);
                    }
                }
                $controllersFs[] = am(array('archivo'=>$nombreArchivo, 'nombre'=>str_replace('.php', '', $controller)), array('acciones'=>am($accionesPredefinidas, array_diff($actions, $parentActions))));
            }
        }
		
		$controllersBase = $this->Controlador->find('list', array('fields'=>array('Controlador.id', 'Controlador.archivo')));

		/**
		* En $controllersFs tengo todos los controladores que existen en el filesystem (codigo fuente).
		* En $controllersBase tengo todos los controladores que estan cargados en la DB.
		*/

		/**
		* Busco los que debo agregar (estan en el fs y no en la DB.
		*/
		$controladoresFs = array();
		foreach ($controllersFs as $controllerFs) {
			$controladoresFs[] = $controllerFs['archivo'];
			/**
			* Si no esta en la base debo agregar el controlador y sus acciones relacionadas.
			*/
			if (!(in_array($controllerFs['archivo'], $controllersBase))) {
				$controllerFs['estado'] = 'Activo';
				$controllerFs['etiqueta'] = inflector::humanize(inflector::underscore($controllerFs['nombre']));
				$acciones = array();
				foreach ($controllerFs['acciones'] as $accion) {
					$acciones[] = array('estado'	=> 'Activo',
										'nombre'	=> $accion,
										'etiqueta'	=> inflector::humanize(inflector::underscore($accion)));
				}
				unset($controllerFs['acciones']);
				
				$this->Controlador->create();
				$this->Controlador->save(array('Controlador'=>$controllerFs, 'Accion'=>$acciones));
			}
				
			/**
			* Si esta, debo verificar que las acciones esten correctamente actualizadas tambien.
			*/
			$controllersBaseFlip = array_flip($controllersBase);
			if (isset($controllersBaseFlip[$controllerFs['archivo']])) {
				$controlador_id = $controllersBaseFlip[$controllerFs['archivo']];
				$actionsBase = Set::combine($this->Controlador->Accion->find('all', array('conditions'=>array('Accion.controlador_id'=>$controlador_id))), '{n}.Accion.id', '{n}.Accion.nombre');
				foreach ($controllerFs['acciones'] as $accion) {
					/**
					* Si la accion esta en el FS y no en la DB, la agrego.
					*/
					if (!in_array($accion, $actionsBase)) {
						$accion = array('estado'			=> 'Activo',
										'nombre'			=> $accion,
										'controlador_id'	=> $controlador_id,
										'etiqueta'			=> inflector::humanize(inflector::underscore($accion)));
						$this->Controlador->Accion->create();
						$this->Controlador->Accion->save(array('Accion'=>$accion));
					}
				}
			
				/**
				* Si la accion esta en la DB y no en el FS, la borro.
				*/
				$accionesParaBorrar = array_keys(array_diff($actionsBase, $controllerFs['acciones']));
				if (!empty($accionesParaBorrar)) {
					foreach ($accionesParaBorrar as $id) {
						$this->Controlador->Accion->del($id);
					}
				}
			}
		}

		/**
		* Busco los que debo eliminar (no estan en el fs y si en la DB).
		*/
		foreach ($controllersBase as $id=>$controllerDb) {
			/**
			* Si no esta en el fs y si en la db, debo eliminarlo de la db.
			*/
			if (!(in_array($controllerDb, $controladoresFs))) {
				$this->Controlador->del($id);
			}
		}
		
		$this->History->goBack();
	}


/**
 * acciones.
 * Muestra via desglose las acciones relaiconadas al controlador.
 */
	function acciones($id) {
		$this->data = $this->Controlador->read(null, $id);
	}



}
?>