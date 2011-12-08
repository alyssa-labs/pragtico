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
 * @version			$Revision: 997 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-09-21 11:42:41 -0300 (lun 21 de sep de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
$usuario = $appForm->input('Usuario.loginNombre', array('label' => 'Usuario', 'tabindex' => '1'));
$clave = $appForm->input('Usuario.loginClave', array('type' => 'password', 'label' => 'Clave', 'tabindex' => '2'));

$group = '';
if (!empty($groups)) {
    $group = $appForm->input('Usuario.loginGroup', array('label' => 'Grupo', 'tabindex' => '3', 'type' => 'select', 'options' => $groups));
}
$ingresar = $appForm->submit('Ingresar');

$appForm->addScript('
        //jQuery("#UsuarioLoginGroup").parent().hide();
        jQuery("#login").bind("click", function() {
            jQuery.getJSON("login/" + jQuery("#UsuarioLoginNombre").val() + "/" + jQuery("#UsuarioLoginClave").val(),
            function(datos){
                var options = "";
                for (var i = 0; i < datos.length; i++) {
                    options += "<option value=\'" + datos[i].optionValue + "\'>" + datos[i].optionDisplay + "</option>";
                }
                
                jQuery("#UsuarioLoginGroup").html(options);
                jQuery("#UsuarioLoginGroup").parent().show();
            });
        });
');

/**
 * creo el formulario
 */
$form = $appForm->form($usuario . $clave . $group . $ingresar, array('action' => 'login'));
echo $appForm->tag('div', $form, array('class' => 'ingreso'));

?>