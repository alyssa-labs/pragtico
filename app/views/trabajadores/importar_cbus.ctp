<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.views
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 333 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-25 18:56:01 -0200 (Wed, 25 Feb 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Trabajador.planilla'] = array('type' => 'file');

$fieldset = $appForm->pintarFieldsets(array(array('campos' => $campos)), array('fieldset' => array('legend' => 'Importar Cbus', 'imagen' => 'excel.gif')));


$botonesExtra[] = $appForm->button('Cancelar', array('title'=>'Cancelar', 'class'=>'limpiar', 'onclick'=>'document.getElementById("accion").value="cancelar";form.submit();'));
$botonesExtra[] = $appForm->submit('Importar', array('title'=>'Importar la PLanilla', 'onclick'=>'document.getElementById("accion").value="importar"'));
$opcionesTabla['tabla']['omitirMensajeVacio'] = true;
echo $this->element('index/index', array('botonesExtra' => array('opciones' => array('botones' => $botonesExtra)), 'condiciones' => $fieldset, 'opcionesTabla' => $opcionesTabla, 'opcionesForm' => array('enctype'=>'multipart/form-data', 'action' => 'importar_cbus')));

?>