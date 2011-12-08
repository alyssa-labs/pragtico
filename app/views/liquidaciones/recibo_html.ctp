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
 * @version			$Revision: 616 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-06-08 18:55:24 -0300 (lun 08 de jun de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Datos del empleador.
*/
$fila = null;
$fila[] = array("valor"=>"Datos del Empleador", "class"=>"imitar_th_izquierda", "colspan"=>10);
$cuerpo[] = $fila;

$fila = null;
$fila[] = array("valor"=>"<span class='label_liquidacion'>Nombre: </span>" . $this->data['Liquidacion']['empleador_nombre'], "class"=>"izquierda", "colspan"=>10);
$cuerpo[] = $fila;

$fila = null;
$fila[] = array("valor"=>"<span class='label_liquidacion'>Direccion: </span>" . $this->data['Liquidacion']['empleador_direccion'], "class"=>"izquierda", "colspan"=>10);
$cuerpo[] = $fila;

$fila = null;
$fila[] = array("valor"=>"<span class='label_liquidacion'>Cuit: </span>" . $this->data['Liquidacion']['empleador_cuit'], "class"=>"izquierda", "colspan"=>10);
$cuerpo[] = $fila;

/**
* Datos del trabajador.
*/
$fila = null;
$fila[] = array("valor"=>"Datos del Trabajador", "class"=>"imitar_th_izquierda", "colspan"=>10);
$cuerpo[] = $fila;

$fila = null;
$fila[] = array("valor"=>"<span class='label_liquidacion'>Nombre: </span>" . $this->data['Liquidacion']['trabajador_nombre'] . ", " . $this->data['Liquidacion']['trabajador_apellido'], "class"=>"izquierda", "colspan"=>2);
$fila[] = array("valor"=>"<span class='label_liquidacion'>Cuil: </span>" . $this->data['Liquidacion']['trabajador_cuil'], "class"=>"izquierda", "colspan"=>3);
$cuerpo[] = $fila;

$fila = null;
$fila[] = array("valor"=>"<span class='label_liquidacion'>Puesto/Categoria: </span>" . $this->data['Liquidacion']['convenio_categoria_nombre'], "class"=>"izquierda");
$fila[] = array("valor"=>"<span class='label_liquidacion'>Jornada: </span>" . $this->data['Liquidacion']['convenio_categoria_jornada'], "class"=>"izquierda");
$fila[] = array("valor"=>"<span class='label_liquidacion'>Ingreso: </span>" . $formato->format($this->data['Liquidacion']['relacion_ingreso'], "date"), "class"=>"izquierda", "colspan"=>3);
$cuerpo[] = $fila;


/**
* Conceptos.
*/
$fila = null;
if (!empty($this->data['Liquidacion']['mes'])) {
    $fila[] = array("valor"=>$formato->format($this->data['Liquidacion']['ano'] . str_pad($this->data['Liquidacion']['mes'], 2, '0', STR_PAD_LEFT) . $this->data['Liquidacion']['periodo'], array('type' => 'periodoEnLetras', 'case' => 'ucfirst')), "class"=>"imitar_th_izquierda", "colspan"=>10);
    $cuerpo[] = $fila;
} elseif ($this->data['Liquidacion']['tipo'] !== 'Final') {
	$fila[] = array("valor"=>$formato->format($this->data['Liquidacion']['ano'] . $this->data['Liquidacion']['periodo'], array('type' => 'periodoEnLetras', 'case' => 'ucfirst')), "class"=>"imitar_th_izquierda", "colspan"=>10);
    $cuerpo[] = $fila;
}
$fila = null;
$fila[] = array('valor' => 'Concepto', 'class' => 'imitar_th_izquierda');
$fila[] = array('valor' => 'Cantidad', 'class' => 'imitar_th_izquierda');
$fila[] = array('valor' => 'Remunarativo', 'class' => 'imitar_th_izquierda');
$fila[] = array('valor' => 'Deduccion', 'class' => 'imitar_th_izquierda');
$fila[] = array('valor' => 'No Remunarativo', 'class' => 'imitar_th_izquierda');
$cuerpo[] = $fila;


foreach($this->data['LiquidacionesDetalle'] as $concepto) {
	if($concepto['concepto_imprimir'] === 'Si' || ($concepto['concepto_imprimir'] === 'Solo con valor') && abs($concepto['valor']) > 0) {
		$fila = null;
		$fila[] = array('valor' => $concepto['concepto_codigo'], 'class' => 'oculto');
		$fila[] = array('valor' => $concepto['concepto_nombre']);
		if($concepto['valor_cantidad'] > 0) {
			$fila[] = array('valor' => $concepto['valor_cantidad'], 'class' => 'derecha editable');
		} else {
			$fila[] = array('valor' => '');
		}

		$valor = $formato->format($concepto['valor'], 'moneda');
		if($concepto['concepto_tipo'] === 'Remunerativo') {
			$fila[] = array('valor' => $valor, 'class' => 'derecha');
			$fila[] = array('valor' => '');
			$fila[] = array('valor' => '');
		} elseif($concepto['concepto_tipo'] === "Deduccion") {
			$fila[] = array('valor' => '');
			$fila[] = array('valor' => $valor, 'class' => 'derecha');
			$fila[] = array('valor' => '');
		} elseif($concepto['concepto_tipo'] === 'No Remunerativo') {
			$fila[] = array('valor' => '');
			$fila[] = array('valor' => '');
			$fila[] = array('valor' => $valor, 'class' => 'derecha');
		}
		$cuerpo[] = $fila;
	}
}

/**
* Totales
*/
$fila = null;
$fila[] = array('valor' => 'Totales', 'class'=>'imitar_th_izquierda', 'colspan'=>2);
$fila[] = array('valor' => $formato->format($this->data['Liquidacion']['remunerativo'], 'moneda'), 'class' => 'derecha');
$fila[] = array('valor' => $formato->format($this->data['Liquidacion']['deduccion'], 'moneda'), 'class' => 'derecha');
$fila[] = array('valor' => $formato->format($this->data['Liquidacion']['no_remunerativo'], 'moneda'), 'class' => 'derecha');
$cuerpo[] = $fila;

$fila = null;
$fila[] = array('valor' => 'Son ' . $formato->format($this->data['Liquidacion']['total_pesos'], 'numeroEnLetras') . ' en Pesos', 'class'=>'imitar_th_izquierda', 'colspan'=>4);
$fila[] = array('valor' => 'Pesos ' . $formato->format($this->data['Liquidacion']['total_pesos'], 'moneda'), 'class'=>'imitar_th_derecha');
$cuerpo[] = $fila;
$fila = null;
$fila[] = array('valor' => 'Son ' . $formato->format($this->data['Liquidacion']['total_beneficios'], 'numeroEnLetras') . ' en Beneficios', 'class'=>'imitar_th_izquierda', 'colspan'=>4);
$fila[] = array('valor' => 'Beneficios ' . $formato->format($this->data['Liquidacion']['total_beneficios'], 'moneda'), 'class'=>'imitar_th_derecha');
$cuerpo[] = $fila;

$opcionesTabla =  array('tabla'=>
							array(	'eliminar'			=> false,
									'ordenEnEncabezados'=> false,
									'permisos'			=> false,
									'modificar'			=> false,
									'seleccionMultiple'	=> false,
									'mostrarEncabezados'=> false,
									'zebra'				=> false,
									'mostrarIds'		=> false,
									'omitirMensajeVacio'=> true));


$tabla = $appForm->tabla(am(array('cuerpo' => $cuerpo), $opcionesTabla));

/**
* Pongo todo dentro de un div (index) y muestro el resultado.
*/
echo $appForm->bloque($appForm->bloque($tabla), array('div' => array('id' => 'liq', 'class' => 'index')));

/*
$url = Router::url("/");
echo $appForm->codeBlock('
	jQuery(".editable").editable("' . $url . 'liquidaciones/recibo_html", {
		cssclass  		: "edicion_en_grilla",
		submitdata 		: function() {
			var liquidacionId = jQuery(this).parent().parent().parent().parent().parent().attr("id").replace(/desglose[0-9]+_/, "");
			var conceptoCodigo = jQuery(this).parent().find("td:first").html();
			return {"liquidacionId" : liquidacionId, "conceptoCodigo" : conceptoCodigo};
		},
		onblur			: "submit",
		indicator 		: "<img src=\'' . $url . 'img/loading.gif\' />",
		width			: "none",
		height			: "none",
		name			: "valor",
		update			: function() {
			var liquidacionId = jQuery(this).parent().parent().parent().parent().parent().attr("id").replace(/desglose[0-9]+_/, "");
			return "#desglose1_" + liquidacionId
		}
	});'
);
*/
?>