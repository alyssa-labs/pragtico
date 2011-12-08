<?php
/**
 * Este archivo contiene la presentacion.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.views
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 312 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-24 13:28:41 -0200 (mar 24 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Documento.id'] = array('type' => 'radio', 'options' => $documentos, 'label' => __('Document', true));
$campos['Model.id'] = array('type' => 'hidden', 'value' => $id);
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('imagen' => 'bancos.gif')));

//$botonesExtra[] = $appForm->button(__('Cancel', true), array("class"=>"cancelar", "onclick"=>"document.getElementById('accion').value='cancelar';form.submit();"));
$accionesExtra[] = 'cancelar';
$accionesExtra[] = $appForm->submit('Generar', array('title'=>'Genera una Pre-liquidacion', 'onclick'=>'document.getElementById("accion").value="generar"'));
//echo $this->element('index/index', array('botonesExtra'=>array('opciones' => array('botones'=>$botonesExtra)), 'accionesExtra'=>$accionesExtra, 'condiciones'=>$fieldset, 'cuerpo' => $cuerpo, 'opcionesTabla'=>$opcionesTabla, 'opcionesForm'=>array('action'=>'preliquidar')));

echo $this->element('add/add', array('opcionesForm' => array('action' => 'generar'), 'fieldset' => $fieldset, 'accionesExtra' => array('opciones' => array('acciones' => $accionesExtra)), 'opcionesForm' => array('action' => 'generar')));
?>