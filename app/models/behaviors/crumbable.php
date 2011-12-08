<?php
/**
 * Behavior que contiene el manejo de permisos a nivel registros (row level).
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.Models.behaviors
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 332 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-25 16:33:58 -0200 (mié 25 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * Especifico todos los metodos que me garantizan que de manera automagica cada registro que es recuperado o
 * guardado, siempre contendra el usuario y el grupo correcto, como asi tambien los permisos.
 *
 * @package     pragtico
 * @subpackage  app.Models.behaviors
 */
class CrumbableBehavior extends ModelBehavior {

/**
 * Una vez que haya realizado una busqueda, a cada registro le agrego dos nuevos campos que
 * con una bandera booleana me indican si puedo escribir y/o borrar.
 *
 * @param object $Model Model que usa este behavior.
 * @param array $results Los resultados que retorno alguna query.
 * @param boolean $primary Indica si este resultado viene de una query principal o de una query que
 *						   es generada por otra (recursive > 1)
 * @return array array $results Los resultados con los campos de permisos ya agregados a cada registro.
 * @access public
 */	
	function afterFind(&$Model, $results, $primary = false) {

		if ($primary === true && isset($results[0][$Model->name])) {
			$results[0][$Model->name]['bread_crumb_text'] = $Model->getCrumb($results[0]);
		}
		return $results;
	}


}
?>