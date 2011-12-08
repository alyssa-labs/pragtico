<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los conceptos
 * propios de las relaciones laborales existentes entre trabajadores y empleadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1379 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-25 13:50:36 -0300 (vie 25 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los conceptos propios de las
 * relaciones laborales que hay entre en un trabajador y un empleador.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class RelacionesConcepto extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

    var $validate = array(
        'relacion_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar la relacion laboral.')
        ),
        'concepto_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar el concepto.')
        ),
        'formula' => array(
            array(
                'rule'      => 'validFormulaStrings',
                'message'   => 'La formula utiliza valores de texto no encerrados entre comillas simples (\') y que tampoco han sido marcados como variable (#) o como concepto (@).'),
            array(
                'rule'      => 'validFormulaConcepts',
                'message'   => 'La formula utiliza conceptos que no existen en el sistema.'),
            array(
                'rule'      => 'validFormulaParenthesis',
                'message'   => 'La formula no abre y cierra la misma cantidad de parentesis.'),
            array(
                'rule'      => 'validFormulaBrackets',
                'message'   => 'La formula no abre y cierra la misma cantidad de corchetes.')
        )
    );

	var $modificadores = array(	'index'	=>
			array('contain'	=> array('Relacion'	=> array('Empleador', 'Trabajador', 'ConveniosCategoria'), 'Concepto')),
								'edit'	=>
			array('contain'	=> array('Relacion'	=> array('Empleador', 'Trabajador', 'ConveniosCategoria'), 'Concepto')));
	
	var $belongsTo = array('Relacion', 'Concepto');


	function afterFind($results, $primary = false) {
		if (!isset($results[0][0]) && $primary === true) {
			foreach ($results as $k => $result) {
				if (isset($result['Relacion']['ConveniosCategoria'])) {
					$options = null;
					$options['relacion'] = $result;
					$options['relacion']['ConveniosCategoria'] = $result['Relacion']['ConveniosCategoria'];
					$options['codigoConcepto'] = $result['Concepto']['codigo'];
					$tmp = $this->Concepto->findConceptos('Relacion', $options);
					$results[$k]['RelacionesConcepto']['jerarquia'] = $tmp[$result['Concepto']['codigo']]['jerarquia'];
					$results[$k]['RelacionesConcepto']['formula_aplicara'] = $tmp[$result['Concepto']['codigo']]['formula'];
				}
			}
		}
		return parent::afterFind($results, $primary);
	}
}
?>