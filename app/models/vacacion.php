<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las vacaciones.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1218 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-02-07 23:37:01 -0300 (dom 07 de feb de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las vacaciones.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Vacacion extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

/**
 * Los modificaciones al comportamiento estandar de app_controller.php
 *
 * @var array
 * @access public
*/
    var $modificadores = array( 'index' => 
            array('contain' => array('VacacionesDetalle', 'Relacion' => array('Empleador', 'Trabajador'))),
                                'edit'  =>
            array('contain' => array('Relacion' => array('Empleador', 'Trabajador'))));

    var $validate = array(
        'relacion_id' => array(
			array(
				'rule'      => VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la relacion laboral que toma las vacaciones.')
        )        
	);


	var $belongsTo = array('Relacion');

    var $hasMany = array('VacacionesDetalle');



    function afterFind($results, $primary = false) {
        if ($primary) {
            foreach ($results as $k => $vacacion) {
                if (isset($vacacion['Vacacion']['id'])) {
                    if (isset($vacacion['VacacionesDetalle'])) {
                        $results[$k]['Vacacion']['dias'] = array_sum(Set::extract('/VacacionesDetalle[estado!=Pendiente]/dias', $vacacion));
                    }
                }
            }
        } else {
            if (!empty($results[0]['Vacacion'][0])) {
                foreach ($results as $k => $v) {
                    foreach ($v as $k1 => $v1) {
                        foreach ($v1 as $k2 => $vacacion) {
                            if (!isset($vacacion['VacacionesDetalle'])) {
                                $vacacionesDetalle = $this->VacacionesDetalle->find('all',
                                                                array(  'recursive' => -1, 
                                                                        'conditions'=> 
                                                                                array(  'VacacionesDetalle.Vacacion_id'  => $vacacion['id'],
                                                                                        'VacacionesDetalle.estado'       => array('Confirmado', 'Liquidado'))));
                            }
                            $results[$k]['Vacacion'][$k2]['dias'] = array_sum(Set::extract('/VacacionesDetalle/dias', $vacacionesDetalle));
                        }
                    }
                }
            }
        }
        return parent::afterFind($results, $primary);
    }


/**
 * Dias de vacaciones del periodo Confirmados o Liquidados.
 */
    function getDiasVacaciones($relacion, $periodo) {

		if ($periodo['mes'] < 6) {
			$anos = array($periodo['ano'], ($periodo['ano'] - 1));
		} else {
			$anos = $periodo['ano'];
		}

        $vacaciones = $this->VacacionesDetalle->find('all',
                array('conditions'  => array(
							'Vacacion.periodo'				=> $anos,
                            'VacacionesDetalle.desde <='    => $periodo['hasta'],
                            'VacacionesDetalle.estado'      => array('Confirmado', 'Liquidado'),
                            'Vacacion.relacion_id'          => $relacion['Relacion']['id']),
                      'contain'   => 'Vacacion'));

		App::import('Vendor', 'dates', 'pragmatia');
		$diasPeriodo = 0;
		foreach ($vacaciones as $vacacion) {
			$date = Dates::dateAdd($vacacion['VacacionesDetalle']['desde'], $vacacion['VacacionesDetalle']['dias']);
			if ($date >= $periodo['desde']) {
				if ($date > $periodo['hasta']) {
					$diff = Dates::dateDiff($vacacion['VacacionesDetalle']['desde'], $periodo['hasta']);
					$diasPeriodo = $diff['dias'];
				} elseif ($vacacion['VacacionesDetalle']['desde'] < $periodo['desde']) {
					$diff = Dates::dateDiff($periodo['desde'], $date);
					$diasPeriodo = $diff['dias'];
				} else {
					$diasPeriodo += $vacacion['VacacionesDetalle']['dias'];
				}
			}
		}
		return $diasPeriodo;
	}

    function getVacaciones($relacion, $periodo) {

        $vacaciones = $this->VacacionesDetalle->find('all',
                array('conditions'  => array(
                            'VacacionesDetalle.desde >='    => $periodo['periodo']['desde'],
                            'VacacionesDetalle.desde <='    => $periodo['periodo']['hasta'],
                            'VacacionesDetalle.estado'      => 'Confirmado',
                            'Vacacion.relacion_id'          => $relacion['Relacion']['id']),
                      'contain'   => 'Vacacion'));

        $variables = $conceptos = $auxiliares = array();
        $days = 0;
        if (!empty($vacaciones)) {
            
            foreach ($vacaciones as $vacacion) {
                
                $days += $vacacion['VacacionesDetalle']['dias'];
                $auxiliar = null;
                $auxiliar['id'] = $vacacion['VacacionesDetalle']['id'];
                $auxiliar['estado'] = 'Liquidado';
                $auxiliar['permissions'] = '288';
                $auxiliar['liquidacion_id'] = '##MACRO:liquidacion_id##';
                $auxiliares[] = array('save'=>serialize($auxiliar), 'model' => 'VacacionesDetalle');

                $auxiliar = null;
                $auxiliar['id'] = $vacacion['Vacacion']['id'];
                $auxiliar['permissions'] = '288';
                $auxiliares[] = array('save'=>serialize($auxiliar), 'model' => 'Vacacion');
            }

            $variables = array(
                '#dias_vacaciones' => $vacacion['Vacacion']['corresponde'],
                '#dias_vacaciones_confirmados' => $days);

            $conceptos[] = ClassRegistry::init('Concepto')->findConceptos('ConceptoPuntual',
                    array(  'relacion'          => $relacion,
                            'codigoConcepto'    => 'vacaciones'));

        }
        return array('conceptos' => $conceptos, 'variables' => $variables, 'auxiliar' => $auxiliares);
    }

}
?>