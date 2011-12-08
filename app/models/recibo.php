<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los recibos.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1445 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2011-05-26 12:17:44 -0300 (jue 26 de may de 2011) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los recibos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Recibo extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	var $breadCrumb = array('format' 	=> '%s',
							'fields' 	=> array('Recibo.nombre'));

	var $validate = array(
        'nombre' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar un nombre para el recibo.'))
	);

	var $belongsTo = array('Empleador', 'Convenio');
	
	var $hasMany = array('RecibosConcepto');


/**
 * Sync Relation Concepts with Receipt Concepts.
 */
	function sync($relationId, $receiptId) {

		/** Search for concepts in receipt */
		$this->contain('RecibosConcepto');
		$receipt = $this->findById($receiptId);

		foreach ($receipt['RecibosConcepto'] as $v) {
			if ($v['estado'] == 'Activo') {
				$conceptsInReceipt[] = $v['concepto_id'];
			} else {
				$conceptsDeletedFromReceipt[] = $v['concepto_id'];
			}
		}

		if (!empty($conceptsInReceipt)) {

			/** Search for concepts in relation */
			$this->Empleador->Relacion->contain(array(
				'RelacionesConcepto'));
			$relation = $this->Empleador->Relacion->findById($relationId);

			$conceptsInRelaction =
				Set::combine($relation, 'RelacionesConcepto.{n}.id', 'RelacionesConcepto.{n}.concepto_id');

			$db = ConnectionManager::getDataSource($this->useDbConfig);
			if ($db->_transactionStarted) {
				$transactionInProgress = true;
			} else {
				$transactionInProgress = false;
				$db->begin($this);
			}
			$save = true;
			$delete = true;

			if (!empty($conceptsInRelaction)) {
				$toAdd = array_diff($conceptsInReceipt, $conceptsInRelaction);
			} else {
				$toAdd = $conceptsInReceipt;
			}

			foreach ($toAdd as $conceptId) {
				$save = $this->Empleador->Relacion->RelacionesConcepto->save(
					array('RelacionesConcepto' => array(
						'relacion_id' => $relationId,
						'concepto_id' => $conceptId))
				);
				if (!$save) {
					break;
				}
			}


			if (!empty($conceptsDeletedFromReceipt)) {
				$toDelete = array_diff($conceptsDeletedFromReceipt, $conceptsInReceipt);
				if (!empty($toDelete)) {
					$tmp = array_flip($conceptsInRelaction);
					$toDeleteIds = null;
					foreach ($toDelete as $conceptId) {
						if (!empty($tmp[$conceptId])) {
							$toDeleteIds[] = $tmp[$conceptId];
						}
					}

					if (!empty($toDeleteIds)) {
						$delete = $this->Empleador->Relacion->RelacionesConcepto->deleteAll(
							array('RelacionesConcepto.id' => $toDeleteIds),
							true,
							false,
							true
						);
					}
				}
			}

			if ($save && $delete) {
				if (!$transactionInProgress) {
					$db->commit($this);
				}
				return true;
			} else {
				if (!$transactionInProgress) {
					$db->rollback($this);
				}
				return false;
			}
		}

		return true;
	}
 
}
/*
	update relaciones rl inner join recibos r on (rl.recibo_id = r.id) inner join recibos_conceptos rc on (rc.recibo_id = r.id) inner join relaciones_conceptos rlc on (rl.id = rlc.relacion_id) set rlc.recibo_id = r.id where rlc.concepto_id = rc.concepto_id
	
	select rl.id, rc.concepto_id from relaciones rl inner join recibos r on (rl.recibo_id = r.id) inner join recibos_conceptos rc on (rc.recibo_id = r.id) inner join relaciones_conceptos rlc on (rl.id = rlc.relacion_id) and rlc.concepto_id = rc.concepto_id


*/
?>