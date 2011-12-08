<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al suss.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 804 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-30 21:34:38 -0300 (jue 30 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los bancos.
 *
 * Se refiere al Sistema Unificado de Seguridad Social.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class SussController extends AppController {

    var $paginate = array(
        'order' => array(
            'Suss.fecha' => 'desc'
        )
    );

	function save($data = array()) {
		
		if (!empty($this->data['Form']['accion']) && $this->data['Form']['accion'] === 'grabar') {

			$empleadores = $this->data['Suss']['empleador_id'];
			
			if (!empty($this->data['Suss']['empleador_id'])
				|| !empty($this->data['Suss']['grupo_id'])) {
				
				/** Search employers */
				if (!empty($this->data['Suss']['grupo_id'])) {
					$this->Suss->Empleador->recursive = -1;
					foreach ($this->Suss->Empleador->find('all', array(
							'recursive' 	=> -1,
							'conditions' 	=> array(
							'(Empleador.group_id & ' . $this->data['Suss']['grupo_id'] . ') >' => 0))
					) as $empleador) {
						$save[]['Suss'] = array_merge($this->data['Suss'], array('empleador_id' => $empleador['Empleador']['id']));
					}
					if (!empty($save)) {
						return parent::save($save);
					}
				} else {
					return parent::save($data);
				}
			} else {
				$this->Session->setFlash('Debe seleccionar un por lo menos un Empleador o un Grupo.', 'error');
			}
		}
	}
	

	function beforeRender() {
		$this->set('grupos', $this->Util->getUserGroups());
		return parent::beforeRender();
	}

}	
?>