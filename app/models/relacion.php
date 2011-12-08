<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a las relaciones
 * laborales existentes entre trabajadores y empleadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1381 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-06-27 21:28:59 -0300 (dom 27 de jun de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las relaciones.
 * Se refiere a las relaciones laborales que hay entre en un trabajador y un empleador.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Relacion extends AppModel {

    var $permissions = array('permissions' => 496, 'group' => 'default', 'role' => 'all');

	/**
	* Establece modificaciones al comportamiento estandar de app_controller.php
	*/
	var $modificadores = array('index' => array('contain' => array('Trabajador', 'Empleador')),
								'edit' => array('contain' => array(
											  	'Trabajador',
												'Empleador',
												'Situacion',
												'Modalidad',
												'Actividad',
												'Recibo',
												'Area',
												'ConveniosCategoria.Convenio')),
								'add' => array(								
										'valoresDefault' => array('horas' => '8')));

	var $breadCrumb = array('format' 	=> '%s %s (%s)',
							'fields' 	=> array('Trabajador.apellido', 'Trabajador.nombre', 'Empleador.nombre'));
	
	var $validate = array(
        'trabajador_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar un trabajador.')
        ),
        'empleador_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar un empleador.')
        ),
        'area_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar un area.')
        ),
        'horas' => array(
			array(
				'rule'		=> VALID_NUMBER,
				'message'	=> 'Debe ingresar un numero para las horas.')
        ),
        'ingreso' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe especificar la fecha inicio de la relacion laboral.'),
			array(
				'rule'		=> VALID_DATE,
				'message'	=> 'Debe especificar una fecha valida.')
        ),
        'egreso' => array(
            array(
                'rule'      => VALID_DATE,
                'message'   => 'Debe especificar una fecha valida.')
        ),
        'convenios_categoria_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar una categoria.')
        ),
        'situacion_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la situacion.')
        ),
        'modalidad_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la modalidad.')
        ),
        'actividad_id' => array(
			array(
				'rule'		=> VALID_NOT_EMPTY,
				'message'	=> 'Debe seleccionar la actividad.')
        )
	);

	var $belongsTo = array(	'Trabajador' =>
                        array('className'    => 'Trabajador',
                              'foreignKey'   => 'trabajador_id'),
							'Empleador' =>
                        array('className'    => 'Empleador',
                              'foreignKey'   => 'empleador_id'),
							'Area' =>
                        array('className'    => 'Area',
                              'foreignKey'   => 'area_id'),
                            'Recibo' =>
                        array('className'    => 'Recibo',
                              'foreignKey'   => 'recibo_id'),
							'Situacion' =>
                        array('className'    => 'Situacion',
                              'foreignKey'   => 'situacion_id'),
							'Actividad' =>
                        array('className'    => 'Actividad',
                              'foreignKey'   => 'actividad_id'),
							'Modalidad' =>
                        array('className'    => 'Modalidad',
                              'foreignKey'   => 'modalidad_id'),
							'ConveniosCategoria' =>
                        array('className'    => 'ConveniosCategoria',
                              'foreignKey'   => 'convenios_categoria_id'));

	var $hasMany = array(  'RelacionesHistorial',
                            'Ausencia' =>
                        array('className'    => 'Ausencia',
                              'foreignKey'   => 'relacion_id'),
							'Ropa' =>
                        array('className'    => 'Ropa',
                              'foreignKey'   => 'relacion_id'),
							'Novedad' =>
                        array('className'    => 'Novedad',
                              'foreignKey'   => 'relacion_id'),
							'Vacacion' =>
                        array('className'    => 'Vacacion',
                              'foreignKey'   => 'relacion_id'),
                            'Hora' =>
                        array('className'    => 'Hora',
                              'foreignKey'   => 'relacion_id'),
                            'RelacionesConcepto' =>
                        array('className'    => 'RelacionesConcepto',
                              'foreignKey'   => 'relacion_id'),
							'Liquidacion' =>
                        array('className'    => 'Liquidacion',
                              'foreignKey'   => 'relacion_id'),
							'Pago' =>
                        array('className'    => 'Pago',
                              'foreignKey'   => 'relacion_id'),
							'Descuento' =>
                        array('className'    => 'Descuento',
                              'foreignKey'   => 'relacion_id'));
	
	var $hasAndBelongsToMany = array('Concepto' =>
								array('with' => 'RelacionesConcepto'));


	function beforeSave() {

        /** When no record number is entered, assing same number as document */
        if (empty($this->data['Relacion']['legajo']) && !empty($this->data['Relacion']['trabajador_id'])) {
            $this->Trabajador->recursive = -1;
            $trabajador = $this->Trabajador->findById($this->data['Relacion']['trabajador_id']);
            $this->data['Relacion']['legajo'] = $trabajador['Trabajador']['numero_documento'];
        }

		if (!empty($this->data['Relacion']['id']) && !empty($this->data['Relacion']['recibo_id'])) {
			if (!$this->Empleador->Recibo->sync(
				$this->data['Relacion']['id'], $this->data['Relacion']['recibo_id'])) {
				return false;
			}
		}

        return parent::beforeSave();
    }



}
?>