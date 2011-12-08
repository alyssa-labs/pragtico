<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a la relacion que existe
 * entre los convenios colectivos y los conceptos.
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
 * La clase encapsula la logica de acceso a datos asociada a la relacion que existe
 * entre los convenios colectivos y los conceptos.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class ConveniosConcepto extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

    var $breadCrumb = array('format'    => '%s de %s',
                            'fields'    => array('Concepto.nombre', 'Convenio.nombre'));
    
    var $validate = array(
        'convenio_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar el convenio colectivo.')
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
	
	var $belongsTo = array('Convenio', 'Concepto');

}
?>