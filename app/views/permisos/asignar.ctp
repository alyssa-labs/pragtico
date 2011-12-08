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
 * @version			$Revision: 1342 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-06-03 22:14:29 -0300 (jue 03 de jun de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
$condiciones['Permisos.usuario_id'] = array('options' => 'listable', "empty"=>true, "model"=>"Usuario", "displayField"=>array("Usuario.nombre"), "order"=>array("Usuario.nombre"), "empty"=>true);
$condiciones['Permisos.model_id'] = array("label"=>"Modelo", "options"=>$models);
$condiciones['Permisos.grupo_id'] = array('type' => 'select', 'multiple' => 'checkbox', 'options' => 'listable', "model"=>"Grupo", "displayField"=>array("Grupo.nombre"), "order"=>array("Grupo.nombre"));
$condiciones['Permisos.rol_id'] = array('type' => 'select', 'multiple' => 'checkbox', 'options' => 'listable', "model"=>"Rol", "displayField"=>array("Rol.nombre"), "order"=>array("Rol.nombre"));

$fieldsets[] = array('campos' => $condiciones);
$condiciones = $appForm->pintarFieldsets($fieldsets, array('fieldset' => array('legend' => "Cambiar permisos", 'imagen' => 'permisos.gif')));

if(empty($accion)) {
	$bloque_confirmacion = "";
	$accion = "falta_confirmacion";
	$labelBoton = "Asignar";
}
elseif($accion === "falta_confirmacion") {
	$accion = "confirmado";
	$bloque_confirmacion[] = "<span class='color_rojo'><h1>Atencion, los cambios se aplicaran sobre TODOS los registros del model " . $model . "</h1></span>";
	$bloque_confirmacion[] = "<span class='color_rojo'><h1>Esta seguro de querer continuar?</h1></span>";
	$labelBoton = "Confirmar";
}


/**
 * Creo un bloque con caja redondeada entre las condiciones, los botones y las opciones lov (si las hubiese).
 */
$accionesExtra['opciones'] = array("acciones"=>array("nuevo", $appForm->bloque($appForm->link("Importar Planilla", "importarPlanillas", array("class"=>"link_boton", "title"=>"Importa las planillas de ingreso masivo de horas")))));
$botonesExtra['opciones']['botones'][] = $appForm->button("Cancelar", array("title"=>"Cancelar la asignacion", "class"=>"limpiar", "onclick"=>"document.getElementById('accion').value='cancelar';form.action='" . router::url("/") . $this->params['controller'] . "/asignar" . "';form.submit();"));
$botonesExtra['opciones']['botones'][] = $appForm->submit($labelBoton, array("title"=>"Realizar la asignacion", "onclick"=>"document.getElementById('accion').value='" . $accion . "'"));
$botones = $this->element("index/buscadores", array("botonesExtra"=>$botonesExtra, "opcionesForm"=>array("action" => "asignar")));

/**
* Creo la tabla.
*/
$fila = null;
$valor = $appForm->input("yy.xx", array("id"=>"dueno", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false));
$fila[] = array("valor"=>$valor . " Dueño", "class"=>"imitar_th_centro");
$link = $appForm->input("Permisos.dl", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$link = $appForm->input("Permisos.de", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$link = $appForm->input("Permisos.dd", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$cuerpo[] = $fila;

$fila = null;
$valor = $appForm->input("yy.yy", array("id"=>"grupo", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false));
$fila[] = array("valor"=>$valor . " Grupo", "class"=>"imitar_th_centro");
$link = $appForm->input("Permisos.gl", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$link = $appForm->input("Permisos.ge", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$link = $appForm->input("Permisos.gd", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$cuerpo[] = $fila;

$fila = null;
$valor = $appForm->input("yy.zz", array("id"=>"otros", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false));
$fila[] = array("valor"=>$valor . " Otros", "class"=>"imitar_th_centro");
$link = $appForm->input("Permisos.ol", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$link = $appForm->input("Permisos.oe", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$link = $appForm->input("Permisos.od", array("type"=>"checkbox", "label"=>false, 'div' => false, "class"=>"checkbox"));
$fila[] = array("valor"=>$link, "class"=>"centro");
$cuerpo[] = $fila;

$encabezado[] = $appForm->input("xx.vv", array("id"=>"todos", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false));
$encabezado[] = $appForm->input("xx.xx", array("id"=>"leer", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false)) . " Leer";
$encabezado[] = $appForm->input("xx.yy", array("id"=>"escribir", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false)) . " Escribir";
$encabezado[] = $appForm->input("xx.zz", array("id"=>"eliminar", "type"=>"checkbox", "class"=>"checkbox", "label"=>false, 'div' => false)) . " Eliminar";

$opcionesTabla =  array("tabla"=>
							array(	"eliminar"			=>false,
									"ordenEnEncabezados"=>false,
									'permisos'			=>false,
									"modificar"			=>false,
									"seleccionMultiple"	=>false,
									"mostrarEncabezados"=>true,
									"zebra"				=>false,
									"mostrarIds"		=>false,
									"omitirMensajeVacio"=>true));


$tabla = $appForm->tag("div", $appForm->tabla(am(array('cuerpo' => $cuerpo, "encabezado"=>$encabezado), $opcionesTabla)), array("class"=>"tabla", "style"=>"margin-left:13px;"));

$bloques[] = $appForm->tag("div", am($condiciones, $tabla, $bloque_confirmacion, $botones), array('class' => 'unica'));



/**
 * Creo el formulario y pongo todo dentro.
 */
$form = $appForm->form($bloques, array("action"=>"asignar"));


echo $appForm->tag("div", $form, array("class"=>"index"));


$appForm->addScript('
	var valor;
	
	jQuery("#todos").click(
		function() {
			if(jQuery(this).attr("checked")) {
				jQuery(".tabla input:checkbox").checkbox("seleccionar");
			} else {
				jQuery(".tabla input:checkbox").checkbox("deseleccionar");
			}
		}
	);
	
	jQuery("#leer").click(
		function() {
			if(jQuery(this).attr("checked")) {
				valor = true;
			} else {
				valor = false;
			}
			jQuery("#PermisosDl").attr("checked", valor);
			jQuery("#PermisosGl").attr("checked", valor);
			jQuery("#PermisosOl").attr("checked", valor);
		}
	);

	jQuery("#escribir").click(
		function() {
			if(jQuery(this).attr("checked")) {
				valor = true;
			} else {
				valor = false;
			}
			jQuery("#PermisosDe").attr("checked", valor);
			jQuery("#PermisosGe").attr("checked", valor);
			jQuery("#PermisosOe").attr("checked", valor);
		}
	);

	jQuery("#eliminar").click(
		function() {
			if(jQuery(this).attr("checked")) {
				valor = true;
			} else {
				valor = false;
			}
			jQuery("#PermisosDd").attr("checked", valor);
			jQuery("#PermisosGd").attr("checked", valor);
			jQuery("#PermisosOd").attr("checked", valor);
		}
	);

	jQuery("#dueno").click(
		function() {
			if(jQuery(this).attr("checked")) {
				valor = true;
			} else {
				valor = false;
			}
			jQuery("#PermisosDl").attr("checked", valor);
			jQuery("#PermisosDe").attr("checked", valor);
			jQuery("#PermisosDd").attr("checked", valor);
		}
	);
	
	jQuery("#grupo").click(
		function() {
			if(jQuery(this).attr("checked")) {
				valor = true;
			} else {
				valor = false;
			}
			jQuery("#PermisosGl").attr("checked", valor);
			jQuery("#PermisosGe").attr("checked", valor);
			jQuery("#PermisosGd").attr("checked", valor);
		}
	);
		
	jQuery("#otros").click(
		function() {
			if(jQuery(this).attr("checked")) {
				valor = true;
			} else {
				valor = false;
			}
			jQuery("#PermisosOl").attr("checked", valor);
			jQuery("#PermisosOe").attr("checked", valor);
			jQuery("#PermisosOd").attr("checked", valor);
		}
	);
');

?>