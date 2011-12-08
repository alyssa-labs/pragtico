<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los familiares de los trabajadores.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 996 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-09-19 14:06:21 -0300 (Sat, 19 Sep 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a los familiares de los trabajadores.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class FamiliaresController extends AppController {

    var $paginate = array(
        'order' => array(
            'Familiar.trabajador_id'    => 'asc',
            'Familiar.apellido'         => 'asc',
            'Familiar.nombre'           => 'asc'
        )
    );


    var $helpers = array('Documento');

    function reporte_familiares() {

        if (!empty($this->data['Formulario']['accion']) && $this->data['Formulario']['accion'] === 'generar') {

            if (!empty($this->data['Condicion']['Bar-empleador_id'])) {
                $this->Familiar->Trabajador->Relacion->Behaviors->detach('Permisos');
                $this->Familiar->Trabajador->Relacion->recursive = -1;;
                $conditions['Familiar.trabajador_id'] = Set::extract('/Relacion/trabajador_id', $this->Familiar->Trabajador->Relacion->find('all', array('conditions' => array('Relacion.empleador_id' => explode('**||**', $this->data['Condicion']['Bar-empleador_id'])))));
            }
            
            if (!empty($this->data['Condicion']['Bar-trabajador_id'])) {
                $conditions['Familiar.trabajador_id'] = explode('**||**', $this->data['Condicion']['Bar-trabajador_id']);
            }

            if (empty($conditions)) {
                $this->Session->setFlash('Debe seleccionar por lo menos un empleador o un trabajador.', 'error');
                $this->History->goBack();
            }
            
            $this->Familiar->Behaviors->detach('Permisos');
            $this->set('data', $this->Familiar->find('all', array(
                'contain'       => 'Trabajador',
                'order'         => array('Trabajador.cuil', 'Familiar.nombre'),
                'conditions'    => $conditions)));
                        
            $this->set('fileFormat', $this->data['Condicion']['Bar-file_format']);
        }
    }
    

}
?>