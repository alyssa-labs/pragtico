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
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['Trabajador.id'] = array();
$campos['Trabajador.cuil'] = array();
$campos['Trabajador.nombre'] = array();
$campos['Trabajador.apellido'] = array();
$campos['Trabajador.tipo_documento'] = array();
$campos['Trabajador.numero_documento'] = array('aclaracion' => 'Si lo deja en blanco, se lo extraera desde el cuil.');
$campos['Trabajador.estado_civil'] = array();
$campos['Trabajador.sexo'] = array();
$campos['Trabajador.nacimiento'] = array();
$campos['Trabajador.archivo'] = array('type' => 'file', 'label' => 'Foto', 'mostrar'=>true);
$campos['Trabajador.nacionalidad'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Identificacion', 'imagen' => 'identificacion.gif')));

$campos = null;
$campos['Trabajador.direccion'] = array();
$campos['Trabajador.numero'] = array();
$campos['Trabajador.codigo_postal'] = array();
$campos['Trabajador.barrio'] = array();
$campos['Trabajador.ciudad'] = array();
$campos['Trabajador.localidad_id'] = array('lov'=>array('controller'		=>	'localidades',
														'seleccionMultiple'	=> 	0,
														'separadorRetorno'	=> 	', ',
														'camposRetorno'		=>	array(	'Provincia.nombre',
																						'Localidad.nombre')));
$campos['Trabajador.pais'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Ubicacion', 'imagen' => 'ubicacion.gif')));

$campos = null;
$campos['Trabajador.telefono'] = array();
$campos['Trabajador.celular'] = array();
$campos['Trabajador.email'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Contacto', 'imagen' => 'contacto.gif')));

$campos = null;
$campos['Trabajador.solicitar_tarjeta_debito'] = array();
$campos['Trabajador.tipo_cuenta'] = array('label' => 'Tipo');
$campos['Trabajador.cbu'] = array('aclaracion' => 'Ingrese sin guiones ni barras.');
$campos['Trabajador.deposita'] = array('aclaracion' => 'Indica si debe depositarsele el sueldo en la cuenta especificada.', 'div' => array('id' => 'divDeposita'));
if ($this->action === 'edit') {
	$campos['Trabajador.banco'] = array('type' => 'soloLectura');
	$campos['Trabajador.sucursal'] = array('type' => 'soloLectura');
	$campos['Trabajador.cuenta'] = array('type' => 'soloLectura');
}
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Informacion Bancaria', 'imagen' => 'pagos.gif')));

$campos = null;
$campos['Trabajador.jubilacion'] = array();
$campos['Trabajador.condicion_id'] = array( 'options'       => 'listable',
                                            'order'         => 'Condicion.codigo',
                                            'displayField'  => 'Condicion.nombre',
                                            'model'         => 'Condicion');
$campos['Trabajador.obra_social_id'] = array(	'lov'=>array('controller'	=>	'obras_sociales',
														'seleccionMultiple'	=> 	0,
														'camposRetorno'		=>	array(	'ObrasSocial.codigo',
																						'ObrasSocial.nombre')));
$campos['Trabajador.adicional_os'] = array('aclaracion' => 'Importe adicional en la Obra Social (SIAP).');
$campos['Trabajador.excedentes_os'] = array('aclaracion' => 'Importe de los excedentes en la Obra Social (SIAP).');
$campos['Trabajador.adherentes_os'] = array();
$campos['Trabajador.aporte_adicional_os'] = array('aclaracion' => 'Aporte adicional a la Obra Social (SIAP).');
$campos['Trabajador.siniestrado_id'] = array(   'options'       => 'listable',
                                            'order'         => 'Siniestrado.codigo',
                                            'displayField'  => 'Siniestrado.nombre',
                                            'model'         => 'Siniestrado',
                                            'aclaracion'    => 'Indica algun tipo de imposibilidad (SIAP).');
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Afip', 'imagen' => 'afip.gif')));

$campos = null;
$campos['Trabajador.observacion'] = array();
$fieldsets[] = array('campos' => $campos, 'opciones' => array('div' => array('class' => 'subset'), 'fieldset' => array('legend' => 'Observaciones', 'imagen' => 'observaciones.gif')));



/**
* Pinto el element add con todos los fieldsets que he definido.
*/
$fieldset = $appForm->pintarFieldsets($fieldsets,
	array(	'div' => array('class' => 'unica'),
			'fieldset' => array('imagen' => 'trabajadores.gif')));


$accionesExtra['opciones']['acciones'][] = 'cancelar';

if (empty($this->data['Trabajador']['id'])) {
	$accionesExtra['opciones']['acciones'][] = $appForm->button('Guardar y Cont.', array(
		'title'		=> 'Guardar y continuar creando la Relacion',
		'onclick' 	=> "jQuery('#accion').val('grabar_continuar|controller:relaciones|action:add|Relacion.trabajador_id:##ID##');form.submit();"
		)
	);
}
$accionesExtra['opciones']['acciones'][] = 'grabar';

echo $this->element('add/add', array(
		'fieldset' 		=> $fieldset,
		'accionesExtra' => $accionesExtra,
		'opcionesForm' 	=> array('enctype' => 'multipart/form-data')
	)
);

$appForm->addScript("

	if (jQuery('#TrabajadorCbu').val().length == 22) {
		jQuery('#divDeposita').show();
	} else {
		jQuery('#divDeposita').hide();
	}
	jQuery('#TrabajadorCbu').bind('keyup', function() {
		if (jQuery('#TrabajadorCbu').val().length == 22) {
			jQuery('#divDeposita').show();
		} else {
			jQuery('#divDeposita').hide();
		}
	});
");
?>