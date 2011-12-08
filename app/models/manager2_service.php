<?php
/**
 * Este archivo contiene toda la logica de acceso a datos necesaria para la comunicacion con manager2.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 869 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-08-24 16:33:42 -0300 (lun 24 de ago de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a las necesidades de comunicacion via WebServices
 * entre Manager2 y Pragtico.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class Manager2Service extends AppModel {

    var $useTable = false;


/**
 * Facturacion.
 *
 * @param integer $id El ultimo id que Manager2 ha recibido.
 * @return string El xml con el formato establecido.
 */
    function facturacion($id) {

        if (is_numeric($id)) {
            $Factura = ClassRegistry::init('Factura');
            $Factura->Behaviors->detach('Permisos');
            $registros = $Factura->find('all', array(
                'conditions'    => array('Factura.modified >' => date('Y-m-d H:i:s', $id)),
                'contain'       => array('Empleador', 'Area')));


            $tmp = $registros;
            $ultimo = array_pop($tmp);
            $doc = new DomDocument('1.0');
            
            $root = $doc->createElement('datos');
            $root->setAttribute('firstId', $id);
            $root->setAttribute('initialTime', date('Y-m-d H:i:s', $id));
            $root->setAttribute('lastId', strtotime($ultimo['Factura']['modified']));
            $root->setAttribute('finalTime', $ultimo['Factura']['modified']);
            $root = $doc->appendChild($root);
            $empleadores = $root->appendChild($doc->createElement('empleadores'));
            
            $prevGroup = null;
            
            $names = array( 3  => 'Facturado Remunerativo',
                            4  => 'Facturado No Remunerativo',
                            5  => 'Facturado Beneficios',
                            49 => 'Liquidado No Remunerativo');
            foreach ($registros as $registro) {

                if ($registro['Factura']['group_id'] !== $prevGroup) {
                    $prevGroup = $registro['Factura']['group_id'];
                    $grupo = $doc->createElement('grupo');
                    if (!empty($registro['Area']['group_id'])) {
                        $grupo->setAttribute('codigo', $registro['Area']['group_id']);
                    } else {
                        $grupo->setAttribute('codigo', $registro['Empleador']['group_id']);
                    }
                    $empleadores->appendChild($grupo);
                }

                $empleador = $doc->createElement('empleador');
                $empleador->setAttribute('cuit', str_replace('-', '', $registro['Empleador']['cuit']));
                if (!empty($registro['Area']['identificador'])) {
                    $empleador->setAttribute('codigo', $registro['Area']['identificador']);
                } else {
                    $empleador->setAttribute('codigo', $registro['Empleador']['identificador']);
                }
                $empleador->setAttribute('periodo', $registro['Factura']['ano'] . str_pad($registro['Factura']['mes'], 2, '0', STR_PAD_LEFT) . $registro['Factura']['periodo']);
                $grupo->appendChild($empleador);
                
                $coeficientes = $empleador->appendChild($doc->createElement('coeficientes'));

                $totals = $Factura->report($registro['Factura']['id']);
                $totales[3]  = $totals['totals']['Facturado Remunerativo']; //Remunerativo
                $totales[4]  = $totals['totals']['Facturado No Remunerativo']; //No Remunerativo
                $totales[5]  = $totals['totals']['Facturado Beneficios']; //Beneficios
                $totales[49] = $totals['totals']['Liquidado Remunerativo']; //Liquidado Remunerativo
                
                foreach ($totales as $codigo => $valor) {
                    $child = $doc->createElement('coeficiente');
                    $child->setAttribute('nombre', $names[$codigo]);
                    $child->setAttribute('codigo', $codigo);
                    $child->setAttribute('importe', $valor);
                    $child->setAttribute('pagado', $valor);
                    $child->setAttribute('cantidad', '1');
                    $child->setAttribute('textoAdicional', '');
                    $child = $coeficientes->appendChild($child);
                }
            }
            return $doc->saveXML();
        } else {
            return '';
        }
    }

/**
 * Empleadores.
 *
 * @param integer $id El ultimo id que Manager2 ha recibido.
 * @return string El xml con el formato establecido.
 */
    function empleadores($id) {
    
        if (is_numeric($id)) {
            $Area = ClassRegistry::init('Area');
            $Area->Behaviors->detach('Permisos');
            $registros = $Area->find('all',
                array(  'conditions'    => array('Area.modified >' => date('Y-m-d H:i:s', $id)),
                        'fields'        =>  array(  'Area.group_id',
                                                    'Area.identificador',
                                                    'Area.nombre',
                                                    'Area.modified',
                                                    'Empleador.cuit',
                                                    'Empleador.nombre',
                                                    'Empleador.direccion',
                                                    'Empleador.barrio',
                                                    'Empleador.ciudad',
                                                    'Empleador.pais',
                                                    'Empleador.telefono',
                                                    'Empleador.fax',
                                                    'Empleador.pagina_web',
                                                    'Empleador.group_id'),
                        'contain'       => array('Empleador'),
                        'order'         => array('Area.group_id')));
            /*
            d($registros);
            
            $Empleador = ClassRegistry::init('Empleador');
            $Empleador->Behaviors->detach('Permisos');
            $registros = $Empleador->find('all',
                array(  'conditions'    => array('Empleador.modified >' => date('Y-m-d H:i:s', $id)),
                        'contain'       => array('Area'),
                        'fields'        =>  array(  'Empleador.cuit',
                                                    'Empleador.nombre',
                                                    'Empleador.direccion',
                                                    'Empleador.barrio',
                                                    'Empleador.ciudad',
                                                    'Empleador.pais',
                                                    'Empleador.telefono',
                                                    'Empleador.fax',
                                                    'Empleador.pagina_web',
                                                    'Empleador.group_id'),
                        'order'         => array('Empleador.id', 'Empleador.group_id')));
            */
            $tmp = $registros;
            $ultimo = array_pop($tmp);
            $doc = new DomDocument('1.0');
            
            $root = $doc->createElement('datos');
            $root->setAttribute('firstId', $id);
            $root->setAttribute('initialTime', date('Y-m-d H:i:s', $id));
            $root->setAttribute('lastId', strtotime($ultimo['Area']['modified']));
            $root->setAttribute('finalTime', $ultimo['Area']['modified']);
            $root = $doc->appendChild($root);
            $empleadores = $root->appendChild($doc->createElement('empleadores'));
            
            $prevGroup = null;
            foreach ($registros as $registro) {
                if ($registro['Area']['group_id'] !== $prevGroup) {
                    $prevGroup = $registro['Area']['group_id'];
                    $grupo = $doc->createElement('grupo');
                    $grupo->setAttribute('codigo', $registro['Area']['group_id']);
                    $grupo = $empleadores->appendChild($grupo);
                    /*
                    $Grupo = ClassRegistry::init('Grupo');
                    $Grupo->contain(array('GruposParametro.Parametro'));
                    $Grupo->Behaviors->detach('Permisos');
                    $tmpGrupo = $Grupo->findById($registro['Area']['group_id']);
                    foreach ($tmpGrupo['GruposParametro'] as $parametro) {
                        if ($parametro['Parametro']['nombre'] === 'cuit') {
                            $grupo->setAttribute('codigo', $parametro['valor']);
                            break;
                        }
                    }
                    $grupo = $empleadores->appendChild($grupo);
                    */
                }

                $child = $doc->createElement('empleador');
                $child->setAttribute('codigo', $registro['Area']['identificador']);
                foreach ($registro['Empleador'] as $k => $v) {
                    if ($k === 'cuit') {
                        $v = str_replace('-', '', $v);
                    } elseif ($k === 'pagina_web') {
                        $k = 'paginaWeb';
                    } elseif ($k === 'group_id') {
                        continue;
                    }
                    $child->setAttribute($k, $v);
                }
                $grupo->appendChild($child);
            }
            return $doc->saveXML();
        } else {
            return '';
        }
    }



/**
 * Anulaciones de Pagos.
 *
 * @param integer $id El ultimo id que Manager2 ha recibido.
 * @return string El xml con el formato establecido.
 */
    function anulaciones_pagos($id) {

        if (is_numeric($id)) {
            $PagosForma = ClassRegistry::init('PagosForma');
            $PagosForma->Behaviors->detach('Permisos');
            $registros = $PagosForma->find('all', array(    'contain'       => 'Pago.Relacion.Trabajador',
                                                            'conditions'=>array(    'PagosForma.monto <'    => 0,
                                                                                    'PagosForma.modified >'       => date('Y-m-d H:i:s', $id))));
            $tmp = $registros;
            $ultimo = array_pop($tmp);
            $doc = new DomDocument('1.0');
            $root = $doc->createElement('datos');
            $root->setAttribute('firstId', $id);
            $root->setAttribute('initialTime', date('Y-m-d H:i:s', $id));
            $root->setAttribute('lastId', strtotime($ultimo['PagosForma']['modified']));
            $root->setAttribute('finalTime', $ultimo['PagosForma']['modified']);
            $root = $doc->appendChild($root);
            
            $pagos = $doc->createElement('pagos');
            $pagos = $root->appendChild($pagos);
            
            $prevGroup = null;
            foreach ($registros as $registro) {
                if (!empty($registro['PagosForma'])) {

                    if ($registro['PagosForma']['group_id'] !== $prevGroup) {
                        $prevGroup = $registro['PagosForma']['group_id'];
                        $grupo = $doc->createElement('grupo');
                        $grupo->setAttribute('codigo', $registro['Pago']['Relacion']['group_id']);
                        $grupo = $pagos->appendChild($grupo);
                    }

                    $pago = $doc->createElement('pago');
                    $pago->setAttribute('cuil', str_replace('-', '', $registro['Pago']['Relacion']['Trabajador']['cuil']));
                    $pago->setAttribute('nombre', $registro['Pago']['Relacion']['Trabajador']['apellido'] . ' ' . $registro['Pago']['Relacion']['Trabajador']['nombre']);
                    $pago->setAttribute('cuenta', $registro['Pago']['Relacion']['Trabajador']['cbu']);
                    $pago = $grupo->appendChild($pago);
                    $forma_pago = $doc->createElement('medio');
                    $forma_pago->setAttribute('comprobante', $registro['PagosForma']['cheque_numero']);
                    $forma_pago->setAttribute('tipo', $registro['PagosForma']['forma']);
                    if (!empty($forma['Cuenta']['cbu'])) {
                        $forma_pago->setAttribute('cbuOrigen', $registro['PagosForma']['Cuenta']['cbu']);
                    } else {
                        $forma_pago->setAttribute('cbuOrigen', '');
                    }
                    $forma_pago->setAttribute('monto', $registro['PagosForma']['monto'] * -1);
                    $forma_pago->setAttribute('fechaEmision', $registro['PagosForma']['fecha']);
                    $forma_pago->setAttribute('fechaPago', $registro['PagosForma']['fecha_pago']);
                    $pago->appendChild($forma_pago);
                }
            }
            return $doc->saveXML();
        } else {
            return '';
        }
    }


/**
 * Anulaciones de Pagos.
 *
 * @param integer $id El ultimo id que Manager2 ha recibido.
 * @return string El xml con el formato establecido.
 */
    function pagos($id) {

        if (is_numeric($id)) {
            $PagosForma = ClassRegistry::init('PagosForma');
            $PagosForma->Behaviors->detach('Permisos');
            $registros = $PagosForma->find('all', array(    'contain'       => array('Cuenta', 'Pago.Relacion.Trabajador'),
                                                            'conditions'=>array(    'PagosForma.monto >'    => 0,
                                                                                    'PagosForma.modified >'       => date('Y-m-d H:i:s', $id))));
            $tmp = $registros;
            $ultimo = array_pop($tmp);
            $doc = new DomDocument('1.0');
            $root = $doc->createElement('datos');
            $root->setAttribute('firstId', $id);
            $root->setAttribute('initialTime', date('Y-m-d H:i:s', $id));
            $root->setAttribute('lastId', strtotime($ultimo['PagosForma']['modified']));
            $root->setAttribute('finalTime', $ultimo['PagosForma']['modified']);
            $root = $doc->appendChild($root);
            
            $pagos = $doc->createElement('pagos');
            $pagos = $root->appendChild($pagos);
            
            $prevGroup = null;
            foreach ($registros as $registro) {
                if (!empty($registro['PagosForma'])) {

                    if ($registro['PagosForma']['group_id'] !== $prevGroup) {
                        $prevGroup = $registro['PagosForma']['group_id'];
                        $grupo = $doc->createElement('grupo');
                        $grupo->setAttribute('codigo', $registro['Pago']['Relacion']['group_id']);
                        $grupo = $pagos->appendChild($grupo);
                    }

                    $pago = $doc->createElement('pago');
                    $pago->setAttribute('cuil', str_replace('-', '', $registro['Pago']['Relacion']['Trabajador']['cuil']));
                    $pago->setAttribute('nombre', $registro['Pago']['Relacion']['Trabajador']['apellido'] . ' ' . $registro['Pago']['Relacion']['Trabajador']['nombre']);
                    $pago->setAttribute('cuenta', $registro['Pago']['Relacion']['Trabajador']['cbu']);
                    $pago = $grupo->appendChild($pago);
                    $forma_pago = $doc->createElement('medio');
                    $forma_pago->setAttribute('comprobante', $registro['PagosForma']['cheque_numero']);
                    $forma_pago->setAttribute('tipo', $registro['PagosForma']['forma']);
                    if (!empty($registro['Cuenta']['cbu'])) {
                        $forma_pago->setAttribute('cbuOrigen', $registro['Cuenta']['cbu']);
                    } else {
                        $forma_pago->setAttribute('cbuOrigen', '');
                    }
                    $forma_pago->setAttribute('monto', $registro['PagosForma']['monto']);
                    $forma_pago->setAttribute('fechaEmision', $registro['PagosForma']['fecha']);
                    $forma_pago->setAttribute('fechaPago', $registro['PagosForma']['fecha_pago']);
                    $pago->appendChild($forma_pago);
                }
            }
            return $doc->saveXML();
        } else {
            return '';
        }
    }

    
}
?>