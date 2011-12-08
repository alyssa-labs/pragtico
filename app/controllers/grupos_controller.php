<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los grupos de usuarios.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1296 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-15 23:05:59 -0300 (sáb 15 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

/**
 * La clase encapsula la logica de negocio asociada a los grupos de usuarios.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class GruposController extends AppController {


    var $paginate = array(
        'order' => array(
            'Grupo.nombre' => 'asc'
        )
    );


/**
 * usuarios.
 * Muestra via desglose usuarios pertenecientes a este grupo.
 */
	function usuarios($id) {
		$this->Grupo->contain(array('Usuario'));
		$this->data = $this->Grupo->read(null, $id);
	}


/**
 * parametros.
 * Muestra via desglose menus parametros a este grupo.
 */
	function parametros($id) {
		$this->Grupo->contain(array('GruposParametro.Parametro'));
		$this->data = $this->Grupo->read(null, $id);
	}	
	
	
/**
 * Setea el grupo por defecto con el que trabajara un usuario y lo guarda en la sesion.
 *
 * @param integer $id El identificador unico del grupo a setear como grupo por defecto.
 * @return void.
 * @access public
 */
	function setear_grupo_default($id, $background = false) {
		$usuario = $this->Session->read('__Usuario');
        if ($usuario['Usuario']['grupos'] & (int)$id) {
            
            if (!empty($usuario['Usuario']['preferencias']['grupo_default_id']) && !empty($usuario['Usuario']['preferencias']['grupos_seleccionados']) && $usuario['Usuario']['preferencias']['grupos_seleccionados'] & (int)$usuario['Usuario']['preferencias']['grupo_default_id']) {
                $usuario['Usuario']['preferencias']['grupos_seleccionados'] -= $usuario['Usuario']['preferencias']['grupo_default_id'];
            }
            
			$usuario['Usuario']['preferencias']['grupo_default_id'] = $id;
            if (!empty($usuario['Usuario']['preferencias']['grupos_seleccionados'])) {
                if (!($usuario['Usuario']['preferencias']['grupos_seleccionados'] & (int)$id)) {
                    $usuario['Usuario']['preferencias']['grupos_seleccionados'] += $id;
                }
            } else {
                $usuario['Usuario']['preferencias']['grupos_seleccionados'] = $id;
            }
			$this->Session->write('__Usuario', $usuario);

            /** Clear current filters */
            $this->Session->delete('filtros');
            
            if ($background === false) {
                $this->Session->setFlash('El nuevo grupo por defecto se seteo correctamente.', 'ok');
            }
		} else if ($background === false) {
			$this->Session->setFlash('Usted no tiene autorizacion para cambiar el grupo.', 'error');
		}
        
        if ($background === false) {
            $this->History->goBack();
        } else {
            $this->autoRender = false;
        }
	}

	
/**
 * Permite agregar o quitar un grupo a los grupos preseleccionados del usuario.
 * Utilizara estos grupos para filtras las busquedas o al momento de crear un nuevo registro.
 *
 * @return void.
 * @access public 
 */
	function cambiar_grupo_activo() {
		if (!empty($this->params['named']['accion']) && !empty($this->params['named']['grupo_id']) && is_numeric($this->params['named']['grupo_id'])) {
			$usuario = $this->Session->read('__Usuario');
			if ($this->params['named']['accion'] === 'agregar') {
				$usuario['Usuario']['preferencias']['grupos_seleccionados'] = $usuario['Usuario']['preferencias']['grupos_seleccionados'] + $this->params['named']['grupo_id'];
			} elseif ($this->params['named']['accion'] === 'quitar') {
				$usuario['Usuario']['preferencias']['grupos_seleccionados'] = $usuario['Usuario']['preferencias']['grupos_seleccionados'] - $this->params['named']['grupo_id'];
			}
			$this->Session->write('__Usuario', $usuario);
		}
		$this->History->goBack();
	}
		
}
?>