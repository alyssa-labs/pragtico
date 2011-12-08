<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada al seguimiento de una ausencia.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1291 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-03 09:17:47 -0300 (lun 03 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada al seguimiento de una ausencia.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class AusenciasSeguimiento extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	/**
	* Estados:
	* 		Pendiente: Se cargo pero no se confirmo. No se tendra en cuenta para la liquidacion.
	*		Confirmado: Se cargo y se confirmo. Se liquidara.
	* 		Liquidado: Se cargo, se confirmo y se liquido. No se volvera a liquidar.
	var $opciones = array('estado'=> array(		'Confirmado'	=> 'Confirmado',
												'Pendiente'		=> 'Pendiente'));
    */
	

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array('edit'=>array('contain'	=> array('Ausencia.AusenciasMotivo')),
								'add'  	=> array(								
										'valoresDefault'=> array('dias' => '1')));

	var $validate = array(
        'dias' => array(
			array(
				'rule'		=> VALID_NUMBER, 
				'message'	=> 'Debe especificar un numero valido de dias.'),
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la cantidad de dias.'),
			array(
				'rule'		=> 'overlay',
				'message'	=> 'Existe una ausencia cargada cuya duracion se superpondria con la cantidad de dias cargadas en seguimiento actual.')
        )
	);

	var $belongsTo = array('Ausencia', 'Liquidacion');



	function overlay($rule, $ruleParams) {

		/** Avoid overlay of absences */
		if (!empty($this->data['AusenciasSeguimiento']['ausencia_id'])
			&& empty($this->data['AusenciasSeguimiento']['id'])) {

			$sql = '
				SELECT 		1 FROM (
					SELECT 		Ausencia.id,
								Ausencia.desde,
								MIN(AusenciaAux.desde) AS inicio_proxima,
								ADDDATE(Ausencia.desde,
									(SELECT 	SUM(dias) + ' . $this->data['AusenciasSeguimiento']['dias'] . '
									FROM 		ausencias_seguimientos AusenciasSeguimiento
									WHERE		AusenciasSeguimiento.ausencia_id = ' . $this->data['AusenciasSeguimiento']['ausencia_id'] . ')) AS fin
					FROM		ausencias Ausencia INNER JOIN ausencias_seguimientos AusenciasSeguimiento
						ON (Ausencia.id = AusenciasSeguimiento.ausencia_id) LEFT JOIN ausencias AusenciaAux ON (Ausencia.relacion_id = AusenciaAux.relacion_id AND AusenciaAux.desde > Ausencia.desde)
					WHERE 		Ausencia.id = ' . $this->data['AusenciasSeguimiento']['ausencia_id'] . '
					GROUP BY	Ausencia.id) AS sq
				WHERE			sq.fin >= sq.inicio_proxima';

			$r = $this->query($sql);
			if (!empty($r)) {
				return false;
			}
		}

		return true;
	}

}
?>