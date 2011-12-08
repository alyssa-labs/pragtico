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
 * @version			$Revision: 644 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-06-26 16:25:46 -0300 (vie 26 de jun de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
* Especifico los campos de ingreso de datos.
*/
$campos = null;
$campos['AusenciasMotivo.id'] = array();
$campos['AusenciasMotivo.motivo'] = array();
$campos['AusenciasMotivo.tipo'] = array();
$campos['AusenciasMotivo.situacion_id'] = array(	'aclaracion' => 	"Opcional: Se refiere a la situacion que se informara en SIAP si existe una relacion entre ambas.",
											"lov"		=>	array(	"controller"		=> 	"situaciones",
																	"seleccionMultiple"	=> 	0,
																		"camposRetorno"	=> 	array(	"Situacion.codigo",
																									"Situacion.nombre")));
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class' => 'unica'), 'fieldset' => array('legend' => "Motivos de Ausencia", 'imagen' => 'ausencias_motivos.gif')));

/**
* Pinto el element add con todos los fieldsets que he definido.
*/
echo $this->element('add/add', array('fieldset' => $fieldset));
?>