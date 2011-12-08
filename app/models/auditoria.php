<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las auditorias.
 * Cada operacion de escritura (add/edit) o eliminacion (delete) deja un log.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 811 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-31 15:56:04 -0300 (vie 31 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las auditorias.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Auditoria extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

/**
 * Retorna la direccion IP de un cliente.
 *
 * @return string La direccion IP del cliente conectado.
 * @access private
 */
    function __getIp() {
    	if (getenv('HTTP_CLIENT_IP')) {
            return getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            return getenv('HTTP_X_FORWARDED_FOR');
        } else {
            return getenv('REMOTE_ADDR');
        }
    }


/**
 * Crea un nuevo registro de auditoria.
 *
 * @param array $data El array para ser guardado.
 * @return boolean True si se puedo crear el nuevo registro correctamente, false en otro caso.
 * @access public
 */
    function auditar($data) {
		$session = &new SessionComponent();
		$usuario = $session->read('__Usuario');
		if (!empty($usuario)) {
			$save['usuario'] = $usuario['Usuario']['nombre'];
		} else {
			$save['usuario'] = 'publico';
		}
    	$save['ip'] = $this->__getIp();
		$save['model'] = $data['model'];
		$save['data'] = serialize($data['data']);
		$save['tipo'] = $data['tipo'];
		$save['user_id'] = '1';
		$save['role_id'] = '1';
		$save['group_id'] = '0';
		$save['permissions'] = '256';
		$saveAuditoria['Auditoria'] = $save;
		$this->create($saveAuditoria);
		return $this->save($saveAuditoria);
	}    
    
}
?>