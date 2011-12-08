<?php
/**
 * Pressentation layer.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
 * @link			http://www.pragmatia.com
 * @package			pragtico
 * @subpackage		app.views
 * @since			Pragtico v 1.0.0
 * @version			$Revision: 285 $
 * @modifiedby		$LastChangedBy: mradosta $
 * @lastmodified	$Date: 2009-02-18 16:46:33 -0200 (mié 18 de feb de 2009) $
 * @author      	Martin Radosta <mradosta@pragmatia.com>
 */
 
/**
 * Data input fields.
 */
$campos = null;
$campos['Zone.id'] = array();
$campos['Zone.code'] = array('labe' => __('Code', true));
$campos['Zone.name'] = array('labe' => __('Name', true));
$campos['Zone.percentage'] = array('labe' => __('Percentage', true));
$fieldsets[] = array('campos' => $campos);

$fieldset = $appForm->pintarFieldsets($fieldsets, array('div' => array('class'=>'unica'), 'fieldset' => array('imagen' => 'xones.gif')));

/**
 * Output view.
 */
echo $this->element('add/add', array('fieldset' => $fieldset, 'miga' => 'zone.name'));
?>