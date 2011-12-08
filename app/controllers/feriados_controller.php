<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a los feriados.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 524 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-05-19 16:41:08 -0300 (Tue, 19 May 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

/**
 * La clase encapsula la logica de negocio asociada a los feriados.
 *
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class FeriadosController extends AppController {

    var $paginate = array(
        'order' => array(
            'Feriado.fecha_origen' => 'asc'
        )
    );


    function update_fron_ws() {
        $soapClient = new SoapClient('http://webservices.mininterior.gov.ar/feriados/Service.svc?wsdl',
            array('uri' => 'http://tempuri.org/'));
        try {
            $ano = date('Y');
            $d1 = mktime(0, 0, 0, 1, 1, $ano);
            $d2 = mktime(0, 0, 0, 12, 31, $ano);
            $nonWorkingDaysObject = $soapClient->FeriadosEntreFechasAsXml(array('d1' => $d1, 'd2' => $d2));

            /** Cargo desde un XML a un array */
            App::import('Core', 'Xml');
            $nonWorkingDays = Set::reverse(new Xml($nonWorkingDaysObject->FeriadosEntreFechasAsXmlResult));
            if (!empty($nonWorkingDays['FeriadoDS']['Feriado'])) {
                foreach ($nonWorkingDays['FeriadoDS']['Feriado'] as $nonWorkingDay) {
                    $save = null;
                    $save['fecha_origen'] = substr($nonWorkingDay['FechaOrigen'], 0, 10);
                    $save['fecha_efectiva'] = substr($nonWorkingDay['FechaEfectiva'], 0, 10);
                    $save['nombre'] = $nonWorkingDay['Nombre'];
                    $save['descripcion'] = $nonWorkingDay['Descripcion'];
                    $save['trasladable'] = ($nonWorkingDay['Trasladable'] === 'false')?'No':'Si';
                    $save['tipo'] = $nonWorkingDay['TipoNombre'];
                    $save['tipo_descripcion'] = (empty($nonWorkingDay['TipoDescripcion'])?'':$nonWorkingDay['TipoDescripcion']);

                    $exists = $this->Feriado->findByNombre($save['nombre']);
                    if (!empty($exists)) {
                        $save['id'] = $exists['Feriado']['id'];
                    }
                    $saveAll[] = array('Feriado' => $save);
                }
                if (!empty($saveAll)) {
                    if ($this->Feriado->saveAll($saveAll)) {
                        $this->Session->setFlash('Se actualizaron correctamente los feriados.', 'ok');
                    } else {
                        $this->Session->setFlash('Ocurrio un error durante la actualizacion.', 'error');
                    }
                }
            }
            
        } catch(SoapFault $soapFault) {
            $this->Session->setFlash('Ocurrio un error al conectarse al WS del Ministerio del Interior. Intente mas tarde.', 'error');
        }
        $this->redirect(array('action' => 'index'));
    }

}
?>