<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a informaciones varias.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 761 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-27 16:28:23 -0300 (Mon, 27 Jul 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a informaciones varias.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Info extends AppModel {


    var $useTable = false;

	function findRelationErrors() {

		$Relacion = ClassRegistry::init('Relacion');
		return $Relacion->find('all', array(
				'checkSecurity'	=> false,
				'contain'		=> array('Empleador', 'Trabajador'),
				'order'			=> array('Trabajador.apellido', 'Trabajador.nombre'),
				'conditions' 	=> array(
					'Relacion.estado' => 'Activa',
					'(Relacion.group_id & ' . array_sum(array_keys(User::getUserGroups('all'))) . ') >' => 0,
					'OR' => array(
						'Trabajador.obra_social_id' => null,
						'Trabajador.localidad_id' 	=> null))
		));
	}


	function findInvoiceErrors() {

		$Liquidacion = ClassRegistry::init('Liquidacion');
		return $Liquidacion->find('all', array(
				'checkSecurity'	=> false,
				'contain'		=> 'Factura',
				'order'			=> array('Liquidacion.empleador_nombre'),
				'fields'		=> array(
					'Liquidacion.empleador_cuit',
					'Liquidacion.empleador_nombre',
					'SUM(Liquidacion.total) AS total'),
				'group'			=> array('Liquidacion.empleador_cuit', 'Liquidacion.empleador_nombre'),
				'conditions' 	=> array(
					'(Liquidacion.group_id & ' . array_sum(array_keys(User::getUserGroups('all'))) . ') >' => 0,
					array('OR' => array(
						'Liquidacion.factura_id' 	=> null,
						array('AND' => array(
							'Liquidacion.factura_id !=' => null,
							'Factura.estado !=' 		=> 'Confirmada')))),
					'Liquidacion.estado' 		=> 'Confirmada')));
	}

}	
?>