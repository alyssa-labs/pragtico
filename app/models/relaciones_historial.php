<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada al historial de las relaciones laborales..
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 812 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-31 16:14:15 -0300 (Fri, 31 Jul 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada al historial de las relaciones laborales.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class RelacionesHistorial extends AppModel {

    var $permissions = array('permissions' => 288, 'group' => 'default', 'role' => 'all');

    var $validate = array(
        'relacion_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar la relacion laboral.')
        ),
        'fin' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar la la fecha de fin.')
        ),
        'egresos_motivo_id' => array(
            array(
                'rule'      => VALID_NOT_EMPTY,
                'message'   => 'Debe seleccionar un motivo de egreso.')
        )
    );

    var $modificadores = array( 'index' =>
            array('contain' => array('Relacion' => array('Empleador', 'Trabajador'))),
                                'edit'  =>
            array('contain' => array('Relacion' => array('Empleador', 'Trabajador'))));

    var $belongsTo = array('Relacion', 'EgresosMotivo');


    function beforeValidate($options = array()) {


        $this->Relacion->contain(array('RelacionesHistorial' => array(
            'conditions'    => array('RelacionesHistorial.estado' => 'Confirmado'),
            'order'         => 'RelacionesHistorial.id DESC')));
        $relation = $this->Relacion->findById($this->data['RelacionesHistorial']['relacion_id']);

        if (!empty($this->data['RelacionesHistorial']['fin'])
            && !empty($relation['RelacionesHistorial'][0]['fin'])
            && $relation['RelacionesHistorial'][0]['fin'] >= $this->data['RelacionesHistorial']['fin']) {
            $this->invalidate('fin', 'La fecha de fin debe ser posterior al ultimo historial confirmado.');
            return false;
        } else {
            return parent::beforeValidate($options);
        }
    }

    function beforeSave($options = array()) {
        $this->Relacion->contain(array('RelacionesHistorial' => array(
            'conditions'    => array('RelacionesHistorial.estado' => 'Confirmado'),
            'order'         => 'RelacionesHistorial.id DESC')));
        $relation = $this->Relacion->findById($this->data['RelacionesHistorial']['relacion_id']);

        if (empty($relation['RelacionesHistorial'])) {
            $this->data['RelacionesHistorial']['inicio'] = $relation['Relacion']['ingreso'];
        } else {
            App::import('Vendor', 'dates', 'pragmatia');
            $this->data['RelacionesHistorial']['inicio'] = Dates::dateAdd($relation['RelacionesHistorial'][0]['fin'], 1, 'd', array('fromInclusive' => false));
        }

        if (!empty($this->data['RelacionesHistorial']['estado'])
            && $this->data['RelacionesHistorial']['estado'] == 'Pendiente') {
            $this->data['RelacionesHistorial']['permissions'] = '496';
        }
        return parent::beforeSave($options);
    }


    function afterSave($created) {
        if (!empty($this->data['RelacionesHistorial']['relacion_id'])
            && !empty($this->data['RelacionesHistorial']['liquidacion_final'])
			&& in_array($this->data['RelacionesHistorial']['liquidacion_final'], array('Suspender', 'No'))
            && !empty($this->data['RelacionesHistorial']['estado'])
            && $this->data['RelacionesHistorial']['estado'] == 'Confirmado') {

            if ($this->data['RelacionesHistorial']['liquidacion_final'] == 'Suspender') {
                $state = 'Suspendida';
            } elseif ($this->data['RelacionesHistorial']['liquidacion_final'] == 'No') {
                $state = 'Historica';
            }

            if (!$this->Relacion->save(array('Relacion' => array(
                'estado'    => $state,
                'id'        => $this->data['RelacionesHistorial']['relacion_id'])))) {
                return false;
            }
        }
        return parent::afterSave($created);
    }

}
?>