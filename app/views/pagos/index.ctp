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
 * @version			$Revision: 1423 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2010-09-01 00:39:54 -0300 (mié 01 de sep de 2010) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos para ingresar las condiciones.
*/
$condiciones['Condicion.Relacion-empleador_id'] = array('lov' =>
        array(  'controller'        => 'empleadores',
                'camposRetorno'     => array('Empleador.nombre')));

$condiciones['Condicion.Relacion-trabajador_id'] = array('lov' =>
		array(	'controller'		=> 'trabajadores',
				'separadorRetorno'	=> ' ',
				'camposRetorno'		=> array('Trabajador.apellido', 'Trabajador.nombre')));

/*
$condiciones['Condicion.Pago-relacion_id'] = array('lov' =>
		array('controller'			=> 'relaciones',
			  'camposRetorno'		=> array('Empleador.nombre', 'Trabajador.apellido')));
*/
//$condiciones['Condicion.Liquidacion-periodo_completo'] = array('type' => 'periodo');
$condiciones['Condicion.Pago-fecha__desde'] = array('label' => 'Desde', 'type' => 'date');
$condiciones['Condicion.Pago-fecha__hasta'] = array('label' => 'Hasta', 'type' => 'date');
$condiciones['Condicion.Pago-origen'] = array('type' => 'radio', 'options' => array('liquidaciones' => 'Liquidaciones', 'descuentos' => 'Descuentos'));
$condiciones['Condicion.Pago-moneda'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Pago-estado'] = array('type' => 'select', 'multiple' => 'checkbox');
$condiciones['Condicion.Pago-identificador'] = array();
$condiciones['Condicion.Liquidacion-liquidaciones_grupo_id'] = array('type' => 'text');
$fieldsets[] = array('campos' => $condiciones);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('fieldset'=>array('imagen' => 'pagos.gif')));


/**
* Creo el cuerpo de la tabla.
*/
$cuerpo = null;
foreach ($registros as $k => $v) {
	$fila = null;
	$fila[] = array('tipo' => 'desglose', 'id' => $v['Pago']['id'], 'imagen'=>array('nombre' => 'pagos_formas.gif', 'alt' => 'Formas de Pago'), 'url'=>'formas');

	$fila[] = array('tipo' => 'accion', 'valor' =>
			$appForm->link($appForm->image('print.gif'),
					array(	'controller'			=> 'pagos',
							'action'				=> 'reporte_recibos',
							$v['Pago']['id']),
					array(	'title' 				=> 'Imprimir Recibo'))
	);

	if ($v['Pago']['estado'] === 'Pendiente' && $v['Pago']['moneda'] === 'Pesos') {
		$fila[] = array('tipo' => 'accion', 'valor' => 
				$appForm->link($appForm->image('cheques.gif'), 
						array(	'controller'			=> 'pagos_formas',
								'action'				=> 'add',
								'PagosForma.forma'		=> 'Cheque',
								'PagosForma.pago_id'	=> $v['Pago']['id']), 
						array(	'title' 				=> 'Pago con Cheque')));
	} elseif ($v['Pago']['estado'] === 'Imputado') {
		$fila[] = array('tipo' => 'accion', 'valor' =>
				$appForm->link($appForm->image('revertir_pago.gif'), 
						'revertir_pago/' . $v['Pago']['id'], 
	  					array('title' => 'Revertir Pago')));
	}
	$fila[] = array('model' => 'Pago', 'field' => 'id', 'valor' => $v['Pago']['id'], 'write' => $v['Pago']['write'], 'delete' => $v['Pago']['delete']);
    $fila[] = array('model' => 'Pago', 'field' => 'estado', 'valor' => $v['Pago']['estado']);

    if (!empty($v['Relacion']['Trabajador']['cbu'])) {
        $fila[] = array('model' => 'Bar', 'field' => 'foo', 'valor' => $bancos[(int)substr($v['Relacion']['Trabajador']['cbu'], 0, 3)], 'nombreEncabezado' => 'Banco');
    } else {
        $fila[] = array('model' => 'Bar', 'field' => 'foo', 'valor' => '', 'nombreEncabezado' => 'Banco');
    }
    
	$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => $v['Relacion']['Empleador']['nombre'], 'nombreEncabezado' => 'Empleador');
	$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => $v['Relacion']['Trabajador']['numero_documento'], 'class' => 'derecha', 'nombreEncabezado' => 'Documento');
	$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => $v['Relacion']['Trabajador']['apellido'] . ' ' . $v['Relacion']['Trabajador']['nombre'], 'nombreEncabezado' => 'Trabajador');

	$fila[] = array('model' => 'Pago', 'field' => 'fecha', 'valor' => $v['Pago']['fecha']);
	$fila[] = array('model' => 'Pago', 'field' => 'monto', 'valor' => $v['Pago']['monto'], 'tipoDato' => 'moneda');
	$fila[] = array('model' => 'Pago', 'field' => 'saldo', 'valor' => $v['Pago']['saldo'], 'tipoDato' => 'moneda');
    
    if (!empty($v['Pago']['liquidacion_id'])) {
        $fila[] = array('tipo' => 'desglose', 'id' => $v['Pago']['liquidacion_id'], 'imagen'=>array('nombre' => 'liquidaciones.gif', 'alt' => 'liquidacion'), 'url'=>'../liquidaciones/recibo_html');
        $valor = sprintf('Liq. %s - %s%s%s', $v['Liquidacion']['tipo'], $v['Liquidacion']['ano'], $v['Liquidacion']['mes'], $v['Liquidacion']['periodo']);
    } else {
        $valor = sprintf('%s - %s', $v['Descuento']['tipo'], $formato->format($v['Descuento']['alta'], 'date'));
    }
    $fila[] = array('model' => 'Liquidacion', 'field' => 'tipo', 'valor' => $valor, 'nombreEncabezado' => 'Origen');
    
    $fila[] = array('model' => 'Pago', 'field' => 'identificador', 'valor' => $v['Pago']['identificador'], 'nombreEncabezado' => 'Ident.');

	if ($v['Pago']['estado'] === 'Imputado' || $v['Pago']['estado'] === 'Cancelado') {
		$cuerpo[] = array('contenido' 	=> $fila, 
						  'opciones' 	=> array('seleccionMultiple'=>false));
	} elseif (empty($v['Relacion']['Trabajador']['cbu'])) {
		$cuerpo[] = array('contenido' => $fila, 'opciones' => 
				array('title' => 'No se podra generar el archivo de soporte por no tener cuenta bancaria', 
					  'class' => 'fila_resaltada'));
	} else {
		$cuerpo[] = $fila;
	}
}

$fila = null;
$fila[] = array('model' => 'Pago', 'field' => 'id', 'valor' => '');
$fila[] = array('model' => 'Empleador', 'field' => 'nombre', 'valor' => '');
$fila[] = array('model' => 'Trabajador', 'field' => 'numero_documento', 'valor' => '');
$fila[] = array('model' => 'Trabajador', 'field' => 'apellido', 'valor' => '');
$fila[] = array('model' => 'Pago', 'field' => 'fecha', 'valor' => '');
$fila[] = array('model' => 'Pago', 'field' => 'moneda', 'valor' => '');
$fila[] = array('model' => 'Pago', 'field' => 'monto', 'valor' => $formato->format($monto, 'moneda'));
$fila[] = array('model' => 'Pago', 'field' => 'saldo', 'valor' => '');
$fila[] = array('model' => 'Liquidacion', 'field' => 'tipo', 'valor' => '');
$fila[] = array('model' => 'Pago', 'field' => 'estado', 'valor' => '');
$fila[] = array('model' => 'Pago', 'field' => 'identificador', 'valor' => '');
$fila[] = array('model' => 'Bar', 'field' => 'foo', 'valor' => '');
$pie[] = $fila;


$acciones[] = $appForm->link('Soporte Mag.', null, 
			array(	'id' 		=> 'generar_soporte_magnetico', 
				  	'class' 	=> 'link_boton', 
	  				'title' 	=> 'Generar Soporte Magnetico'));
$acciones[] = $appForm->link('Conf. Sop. Mag.', null,
            array(  'id'        => 'confirmar_soporte_magnetico',
                    'class'     => 'link_boton', 
                    'title'     => 'Confirmar Soporte Magnetico'));
$acciones[] = $appForm->link('Efectivo', null, 
			array(	'id' 		=> 'pago_efectivo', 
					'class' 	=> 'link_boton', 
	 				'title' 	=> 'Realiza un pago masivo en Efectivo'));
$acciones[] = $appForm->link('Beneficios', null, 
			array(	'id' 		=> 'pago_beneficios', 
					'class' 	=> 'link_boton', 
	 				'title' 	=> 'Realiza un pago masivo con Beneficios'));
$accionesExtra['opciones'] = array('acciones' => $acciones);
/*
$botonesExtra = $appForm->button('Det. Cambio', 
			array(	'id' 		=> 'detalle_cambio', 
					'title' 	=> 'Imprime el Detalle de Cambio'));
					
echo $this->element('index/index', 
			array(	'botonesExtra' => array('opciones' => 
					array(	'botones' 		=> array('limpiar', 'buscar', $botonesExtra))), 
						  	'accionesExtra' => $accionesExtra, 
							'opcionesTabla' => array('tabla' => array('eliminar' => false, 'modificar' => false)), 
							'condiciones' 	=> $fieldset, 
	   						'cuerpo' 		=> $cuerpo));
*/
echo $this->element('index/index', array(
						  	'accionesExtra' => $accionesExtra, 
							'opcionesTabla' => array('tabla' => array('eliminar' => false, 'modificar' => false)), 
							'condiciones' 	=> $fieldset, 
	   						'cuerpo' 		=> $cuerpo,
                            'pie'           => $pie));

/**
* Agrego el evento click asociado al detalle de cambio.
*/
$js = '
	function enviar(action, chequearCantidad) {
		if(chequearCantidad) {
			var c = jQuery(".tabla :checkbox").checkbox("contar");
			if (c == 0) {
				alert("Debe seleccionar al menos un pago a imputar/confirmar.");
				return false;
			}
		}
		jQuery("#form")[0].action = "' . Router::url('/') . $this->params['controller'] . '" + action;
		jQuery("#form")[0].submit();
	}

	jQuery("#detalle_cambio").click(
		function() {
			enviar("/detalle_cambio", false);
		}
	);
	
	jQuery("#pago_beneficios").click(
		function() {
			enviar("/registrar_pago_masivo/beneficios", true);
		}
	);

	jQuery("#pago_deposito").click(
		function() {
			enviar("/registrar_pago_masivo/deposito", true);
		}
	);

	jQuery("#pago_efectivo").click(
		function() {
			enviar("/registrar_pago_masivo/efectivo", true);
		}
	);
	
	jQuery("#generar_soporte_magnetico").click(
		function() {
			enviar("/generar_soporte_magnetico", true);
		}
	);
    
    jQuery("#confirmar_soporte_magnetico").click(
        function() {
            enviar("/confirmar_soporte_magnetico", true);
        }
    );
';
$appForm->addScript($js);

?>