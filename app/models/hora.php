<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociadaa las horas de una relacion laboral.
 * Las horas pueden ser horas extras, horas de ajuste, horas nocturnas, etc.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1329 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-27 14:11:26 -0300 (jue 27 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las horas de una relacion laboral.
 * Las horas pueden ser horas extras, horas de ajuste, horas nocturnas, etc.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Hora extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array(	'index'=>array(	'contain'=>array('Relacion' => array('Empleador', 'Trabajador'))),
								'edit'=>array(	'contain'=>array('Relacion' => array('Empleador', 'Trabajador'))));


	var $opciones = array('estado'=> array(		'Confirmada'=>	'Confirmada',
												'Pendiente'=>	'Pendiente'));
	
	var $validate = array(
        'relacion_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la relacion laboral.')
        ),
        'cantidad' => array(
			array(
				'rule'		=> VALID_NUMBER,
				'message'	=> 'Debe ingresar la cantidad de horas.'),
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe ingresar una cantidad.')
        ),
        'periodo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe ingresar el periodo.'),
			array(
				'rule'		=> VALID_PERIODO,
				'message'	=> 'El periodo no es valido, debe tener el formato AAAAMM(1Q|2Q|M).'),
        ),
        'tipo' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar el tipo.')
        ));

	var $belongsTo = array('Relacion');

    var $breadCrumb = array('format' => '%s %s (%s)', 
                            'fields' => array('Relacion.Trabajador.apellido', 'Relacion.Trabajador.nombre', 'Relacion.Empleador.nombre'));
	
/**
 * Before save callback
 *
 * @return boolean True if the operation should continue, false if it should abort
 */
    function beforeSave() {
    	if (!empty($this->data['Hora']['periodo'])) {
    		$this->data['Hora']['periodo'] = strtoupper($this->data['Hora']['periodo']);
    	}
    	return parent::beforeSave();
	}


    function getTotal($conditions = array()) {
        $result = $this->find('first', array(
            'conditions'    => $conditions,
            'callbacks'     => false,
            'fields'        => 'SUM(Hora.cantidad) as total',
            ));
        return $result[0]['total'];
    }


/**
 * Dada un ralacion y un periodo retorna las horas trabajadas de todos los tipos que esten pendientes de liquidar pero confirmadas.
 *
 * Los posibles estados son:
 *		- Pendiente: Cuando solamente fuen ingresada al sistema la hora.
 *		- Confirmada: Una vez ingresada y chequeada, se confirma y se deja disponible para ser liquidada.
 *		- Liquidada: Ya ha sido liquidada en alguna liquidacion.
 *
 * @param array $relacion La relacion.
 * @param array $periodo El periodo en el que buscare las horas.
 * @param String $receiptType The type of receipt to be ussed for.
 * @return 	array de tres componentes:
 *				conceptos => Los conceptos que dieron lugar el tipo horas encontrado.
 *				variables => Las variables que podran ser usadas desde las formulas del liquidador.
 *				auxiliar  => Los arrays serializados de los saves a ajecutarse para guardar en la tabla liquidaciones_auxiliares.
 *			array con las tres componentes vacias si no hay horas para el periodo y la relacion especificada.
 * @access public.			
 */
	function getHoras($relacion, $periodo, $receiptType) {
		
		$conditions = array(
			'conditions'=>	array(	'Hora.relacion_id' 		=> $relacion['Relacion']['id'],
									'Hora.liquidacion_id' 	=> null,
									'Hora.liquidacion_tipo' => $receiptType,
									'Hora.periodo' 			=> $periodo['periodoCompleto'],
									'Hora.estado'			=> 'Confirmada'),
			'fields'	=>	array(	'Hora.tipo', 'sum(Hora.cantidad) as total'),
			'recursive'	=>	-1,
			'group'		=> 	array('Hora.tipo')
		);
		
		/**
		* Cuando se trata de un trabajador mensual, por mas que las horas esten cargadas para una de las quincenas,
		* las busco indistintamente para ambas.
		*/
		if ($relacion['ConveniosCategoria']['jornada'] === 'Mensual') {
			$conditions['conditions']['Hora.periodo'] =	array	(	$periodo['ano'] . $periodo['mes'] . '1Q',
																	$periodo['ano'] . $periodo['mes'] . '2Q',
																	$periodo['ano'] . $periodo['mes'] . 'M');
		}
		$r = $this->find('all', $conditions);
		
		$map['Normal'] = '#horas';
		$map['Extra 50%'] = '#horas_extra_50';
		$map['Extra 100%'] = '#horas_extra_100';
		$map['Ajuste Normal'] = '#horas_ajuste';
		$map['Ajuste Extra 50%'] = '#horas_ajuste_extra_50';
		$map['Ajuste Extra 100%'] = '#horas_ajuste_extra_100';
		$map['Normal Nocturna'] = '#horas_nocturna';
		$map['Extra Nocturna 50%'] = '#horas_extra_nocturna_50';
		$map['Extra Nocturna 100%'] = '#horas_extra_nocturna_100';
		$map['Ajuste Normal Nocturna'] = '#horas_ajuste_nocturna';
		$map['Ajuste Extra Nocturna 50%'] = '#horas_ajuste_extra_nocturna_50';
		$map['Ajuste Extra Nocturna 100%'] = '#horas_ajuste_extra_nocturna_100';

		/** Array initialization */
		foreach ($map as $v) {
			$horas[$v] = 0;
		}
		$conceptos = $auxiliares = array();
		if (!empty($r)) {
			$Concepto = ClassRegistry::init('Concepto');
			foreach ($r as $hora) {
				if ($relacion['ConveniosCategoria']['jornada'] === 'Mensual' && ($hora['Hora']['tipo'] === 'Normal')) {
					continue;
				}
				$tipo = $map[$hora['Hora']['tipo']];
				$horas[$tipo] = $hora['Hora']['total'];

				/** Search the concept */
				$codigoConcepto = str_replace('#', '', $tipo);
				if ($codigoConcepto === 'horas') {
					$codigoConcepto = 'sueldo_basico';
				}
				$conceptos = array_merge($conceptos, $Concepto->findConceptos('ConceptoPuntual', array('relacion' => $relacion, 'codigoConcepto' => $codigoConcepto)));
			}
			
			/**
			* Busco los Ids de los registros que he seleccionado antes.
			* No lo hago en una sola query porque romperia el group by.
			* TODO: Deberia analizar si sera mejor hacerlo via php a la suma de las horas por tipo y no una query.
			*/
			$conditions['fields'] = array('Hora.id');
			unset($conditions['group']);
			$r = $this->find('all', $conditions);
			/**
			* Creo un registro en la tabla auxiliar que debera ejecutarse en caso de que se confirme la pre-liquidacion.
			* El registro es para cambiarle el estado a Liquidada, basicamente.
			*/
			foreach ($r as $v) {
				$auxiliar = null;
				$auxiliar['id'] = $v['Hora']['id'];
				$auxiliar['estado'] = 'Liquidada';
				$auxiliar['liquidacion_id'] = '##MACRO:liquidacion_id##';
                $auxiliar['permissions'] = '288';
				$auxiliares[] = array('save'=>serialize($auxiliar), 'model' => 'Hora');
			}
		}
		return array('conceptos' => $conceptos, 'variables' => $horas, 'auxiliar' => $auxiliares);
	}


}
?>