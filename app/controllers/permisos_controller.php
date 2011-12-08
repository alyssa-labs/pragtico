<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los permisos de los registros.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 643 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-06-26 13:10:45 -0300 (vie 26 de jun de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los permisos de los registros.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class PermisosController extends AppController {

	var $uses = array("Usuario");

	function asignar() {


		$modelsListTmp = Configure::listObjects('model', APP . 'models');
		asort($modelsListTmp);
		foreach ($modelsListTmp as $model) {
            if (substr($model, -7) === 'Service') {
                continue;
            }
			$modelsList[$model] = $model;
		}
		$modelsList['Todos'] = 'Todos';
		$this->set("models", $modelsList);

		if (!empty($this->data['Permisos']['model_id'])) {

			$total = 0;
			$mensaje = array();
			if (!empty($this->data['Permisos']['dl']) && $this->data['Permisos']['dl'] == 1) {
				$total += 256;
				$mensaje[] = "Permitir lectura al dueño";
			} else {
				$mensaje[] = "Denegar lectura al dueño";
			}
			if (!empty($this->data['Permisos']['gl']) && $this->data['Permisos']['gl'] == 1) {
				$total += 32;
				$mensaje[] = "Permitir lectura al grupo";
			} else {
				$mensaje[] = "Denegar lectura al grupo";
			}
			if (!empty($this->data['Permisos']['ol']) && $this->data['Permisos']['ol'] == 1) {
				$total += 4;
				$mensaje[] = "Permitir lectura a los otros";
			} else {
				$mensaje[] = "Denegar lectura a los otros";
			}
			if (!empty($this->data['Permisos']['de']) && $this->data['Permisos']['de'] == 1) {
				$total += 128;
				$mensaje[] = "Permitir escritura al dueño";
			} else {
				$mensaje[] = "Denegar escritura al dueño";
			}
			if (!empty($this->data['Permisos']['ge']) && $this->data['Permisos']['ge'] == 1) {
				$total += 16;
				$mensaje[] = "Permitir escritura al grupo";
			} else {
				$mensaje[] = "Denegar escritura al grupo";
			}
			if (!empty($this->data['Permisos']['oe']) && $this->data['Permisos']['oe'] == 1) {
				$total += 2;
				$mensaje[] = "Permitir escritura a los otros";
			} else {
				$mensaje[] = "Denegar escritura a los otros";
			}
			if (!empty($this->data['Permisos']['dd']) && $this->data['Permisos']['dd'] == 1) {
				$total += 64;
				$mensaje[] = "Permitir eliminar al dueño";
			} else {
				$mensaje[] = "Denegar eliminar al dueño";
			}
			if (!empty($this->data['Permisos']['gd']) && $this->data['Permisos']['gd'] == 1) {
				$total += 8;
				$mensaje[] = "Permitir eliminar al grupo";
			} else {
				$mensaje[] = "Denegar eliminar al grupo";
			}
			if (!empty($this->data['Permisos']['od']) && $this->data['Permisos']['od'] == 1) {
				$total += 1;
				$mensaje[] = "Permitir eliminar a los otros";
			} else {
				$mensaje[] = "Denegar eliminar a los otros";
			}

			if (!empty($this->data['Permisos']['usuario_id'])) {
				$update['user_id'] = $this->data['Permisos']['usuario_id'];
			}
			
			if (!empty($this->data['Permisos']['grupo_id'])) {
				$update['group_id'] = array_sum($this->data['Permisos']['grupo_id']);
			}
			
			if (!empty($this->data['Permisos']['rol_id'])) {
				$update['role_id'] = array_sum($this->data['Permisos']['rol_id']);
			}
			
			$update['permissions'] = $total;

			$model = $this->data['Permisos']['model_id'];
			if ($this->data['Formulario']['accion'] === "confirmado") {
				if ($model === 'Todos') {
					$c = 0;
                    unset($modelsList['Todos']);
					foreach ($modelsList as $v) {
						App::import('Model', $v);
						$modelParaUpdate = new $v();
                        if ($modelParaUpdate->useTable === false) {
                            continue;
                        }
                        
                        $modelParaUpdate->unbindModel(array('belongsTo' => array_keys($modelParaUpdate->belongsTo)));

						if ($c === 0) {
							$modelParaUpdate->begin();
						}
						if ($modelParaUpdate->updateAll($update)) {
							$c++;
						}
					}
					
					if ($c === count($modelsList)) {
						$modelParaUpdate->commit();
						$this->Session->setFlash('Los cambios a los registros se realizaron correctamente.', 'ok');
					} else {
						$modelParaUpdate->rollback();
						$this->Session->setFlash('No se pudieron realizar los cambios en los permisos.', 'error');
					}
				} else {
					App::import('Model', $model);
					$modelParaUpdate = new $model();
                    $modelParaUpdate->unbindModel(array('belongsTo' => array_keys($modelParaUpdate->belongsTo)));
					if ($modelParaUpdate->updateAll($update)) {
						$this->Session->setFlash("Los cambios a los registros se realizaron correctamente.", "ok");
					} else {
						$this->Session->setFlash("No se pudieron realizar los cambios en los permisos.", "error");
					}
				}
				$this->redirect("asignar");
			}
		}

		if ($this->data['Formulario']['accion'] === "falta_confirmacion") {
			$this->set("accion", "falta_confirmacion");
			$this->set("mensaje", $mensaje);
			$this->set("model", $model);
		}
	}
}
?>