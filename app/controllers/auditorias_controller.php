<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a las auditorias.
 * Cada operacion de escritura (add/edit) o eliminacion (delete) deja un log.
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
 * La clase encapsula la logica de negocio asociada a las auditorias.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class AuditoriasController extends AppController {

/**
 * detalles.
 * Muestra via desglose los detalles de la auditoria (el registro modificado).
 */
	function detalles($id) {
		$this->data = $this->Auditoria->read(null, $id);
		$this->data['Auditoria']['data'] = unserialize($this->data['Auditoria']['data']);
	}
	
}
?>