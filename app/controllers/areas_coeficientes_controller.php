<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al manejo de los coefientes
 * relacionados a las areas de cada empleador.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 196 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2008-12-30 15:58:08 -0200 (mar, 30 dic 2008) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al manejo de los coefientes
 * relacionados a las areas de cada empleador.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class AreasCoeficientesController extends AppController {

    function add_rapido() {
        $areasCoeficientes = Set::combine($this->AreasCoeficiente->find('all', array(
          'recursive'   => -1,
          'conditions'  => array('AreasCoeficiente.area_id' => $this->params['named']['AreasCoeficiente.area_id']))), '{n}.AreasCoeficiente.coeficiente_id', '{n}.AreasCoeficiente');
        foreach ($this->AreasCoeficiente->Coeficiente->find('all', array(
            'order' => array('Coeficiente.tipo', 'Coeficiente.nombre'))) as $v) {
            $v['AreasCoeficiente']['id'] = null;
            $v['AreasCoeficiente']['porcentaje'] = 0;
            if (isset($areasCoeficientes[$v['Coeficiente']['id']])) {
                $v['AreasCoeficiente']['id'] = $areasCoeficientes[$v['Coeficiente']['id']]['id'];
                $v['AreasCoeficiente']['porcentaje'] = $areasCoeficientes[$v['Coeficiente']['id']]['porcentaje'];
            }
            $coefientes[] = $v;
        }
        $this->set('coefientes', $coefientes);
        $this->AreasCoeficiente->Area->recursive = -1;
        $this->set('area', $this->AreasCoeficiente->Area->findById($this->params['named']['AreasCoeficiente.area_id']));
    }


    function save() {
        if (!empty($this->data['Form']['accion']) && $this->data['Form']['accion'] === 'grabar') {

            foreach ($this->data['AreasCoeficiente'] as $k => $v) {
                if (!empty($v['delete']) && !empty($v['id'])) {
                    $delete[] = $v['id'];
                } elseif (!empty($v['porcentaje'])) {
                    $data[] = $v;
                }
            }
            if (!empty($delete)) {
                $this->AreasCoeficiente->deleteAll(array('AreasCoeficiente.id' => $delete));
            }
            $this->data['Form']['accion'] = 'grabar';
        }

        if (!empty($data)) {
            return parent::save($data);
        } else {
            return parent::save();
        }
    }
    
}
?>