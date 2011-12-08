<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las preferencias de los usuarios.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 196 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-12-30 15:58:08 -0200 (mar 30 de dic de 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

/**
 * La clase encapsula la logica de negocio asociada a las preferencias de los usuarios.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class PreferenciasUsuariosController extends AppController {

/**
* Me aseguro de actualizar en la session los cambios que pudo haber realizado el usuario.
	function beforeFilter() {
		$usuario = $this->Session->read("__Usuario");
		$usuario['Usuario']['preferencias'] = $this->PreferenciasUsuario->Preferencia->findPreferencias($usuario['Usuario']['id']);
		$this->Session->write("__Usuario", $usuario);
		return parent::beforeFilter();
	}
*/


	function valores_relacionado($id) {

		if (is_numeric($id)) {
			$this->PreferenciasUsuario->Preferencia->contain("PreferenciasValor");
			$preferencia = $this->PreferenciasUsuario->Preferencia->findById($id);
			foreach ($preferencia['PreferenciasValor'] as $k=>$v) {
				$valores[$k]['optionValue'] = $v['id'];
				$valores[$k]['optionDisplay'] = $v['valor'];
			}
			$this->set("data", $valores);
			$this->render("../elements/json");
		}
	}	


}
?>