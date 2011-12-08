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
 * @version         $Revision: 236 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-01-27 11:26:49 -0200 (Tue, 27 Jan 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
 


/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Soporte.identificador'] = array();
$condiciones['Soporte.pago_id'] = array("type"=>"hidden", "value"=>$ids);


$fieldsets[] = array('campos' => $condiciones);
$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Seleccione la cuenta",'imagen' => 'bancos.gif')));

$accionesExtra['opciones'] = array("acciones"=>array());
$botonesExtra[] = $appForm->button("Cancelar", array("title"=>"Cancelar", "class"=>"limpiar", "onclick"=>"document.getElementById('accion').value='cancelar';form.submit();"));
$botonesExtra[] = $appForm->submit("Confirmar", array("title"=>"Importar la PLanilla", "onclick"=>"document.getElementById('accion').value='generar'"));

$miga = 'Confirmar soporte magnetico';
echo $this->element("index/index", array("opcionesTabla"=>array("tabla"=>array("omitirMensajeVacio"=>true)), "botonesExtra"=>array('opciones' => array("botones"=>$botonesExtra)), "accionesExtra"=>$accionesExtra, "opcionesForm"=>array("action"=>"confirmar_soporte_magnetico"), "condiciones"=>$fieldset, "cuerpo"=>null, 'miga'=>$miga));




//$opciones .= $appForm->input("Soporte.modo", array("options"=>$modos, "type"=>"radio"));
//$opciones .= $appForm->input("Soporte.modo", array("options"=>$modos, "empty"=>true));
//$opciones .= $appForm->input("Soporte.grupo_id", array("options"=>$grupos, "empty"=>true));

//$codigoHtml = $appForm->bloque($opciones, array('fieldset' => array('legend' => "Opciones", 'imagen' => 'ok.gif')));



/*
$bancos = "";
$bancos .= $appForm->bloque(
    $appForm->link($appForm->image('propios" . DS . "santander-rio.jpg",
    array("title"=>"Generar archivo para envio el Banco Santader-Rio",
            'alt' => "Generar archivo para envio el Banco Santader-Rio")), "#", array("title"=>"Santander-Rio", "class"=>"seleccion_bancos_link")),
                array('div' => array("class"=>"seleccion_bancos")));
$bancos .= $appForm->bloque(
    $appForm->link($appForm->image('propios" . DS . "galicia.jpg",
    array("title"=>"Generar archivo para envio el Banco Galicia",
            'alt' => "Generar archivo para envio el Banco Galicia")), "#", array("title"=>"Galicia", "class"=>"seleccion_bancos_link")),
                array('div' => array("class"=>"seleccion_bancos")));
$bancos .= $appForm->bloque(
    $appForm->link($appForm->image('propios" . DS . "nacion.jpg",
    array("title"=>"Generar archivo para envio el Banco Nacion",
    'alt' => "Generar archivo para envio el Banco Nacion")), "#", array("title"=>"Nacion", "class"=>"seleccion_bancos_link")),
        array('div' => array("class"=>"seleccion_bancos")));
*/


//$bancos = $appForm->input("Banco.id", array("options"=>$bancos, "type"=>"radio", "label"=>"Banco"));
//$bancos = $appForm->input("Banco.id", array("options"=>$bancos, "label"=>"Banco", "empty"=>true));
/*
$opciones .= $appForm->input("Soporte.empleador_id", array("aclaracion"     =>  "Solo se tendra en cuenta este campo si el tipo es 'Por Empleador'.",
                                        "lov"   =>array("controller"        =>  "empleadores",
                                                        "seleccionMultiple" =>  0,
                                                        "camposRetorno"     =>  array(  "Empleador.cuit",
                                                                                        "Empleador.nombre"))));
$opciones .= $appForm->input('Banco.id', array("label"=>"Cuenta", "type"=>"relacionado", "relacion"=>"Soporte.empleador_id", "url"=>"pagos/cuentas_relacionado"));
$codigoHtml = $appForm->bloque($opciones, array('fieldset' => array('legend' => "Seleccione la cuenta", 'imagen' => 'bancos.gif')));


*/
/*
echo $appForm->create(null, array("id"=>"form", 'action' => "generar_soporte_magnetico"));
if(!empty($ids)) {
    echo $appForm->input("Pago.ids", array("type"=>"hidden", "value"=>$ids));
}
echo $appForm->bloque($codigoHtml, array('fieldset' => array('legend' => "Generar archivo para deposito de haberes", 'imagen' => 'archivo.gif')));
echo $appForm->end();
*/
?>