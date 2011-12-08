<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los grupos de liquidaciones.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 811 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-31 15:56:04 -0300 (Fri, 31 Jul 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los grupos de liquidaciones.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class LiquidacionesGrupo extends AppModel {

    var $permissions = array('permissions' => 292, 'group' => 'default', 'role' => 'all');


	var $hasMany = array('Liquidacion');

}
?>