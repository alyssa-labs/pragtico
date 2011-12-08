<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los datos auxiliares de las liquidaciones.
 * Estos datos los calculo al momento de pre-liquidar y los dejo pendientes en la tabla liquidaciones_auxiliares,
 * luego, en caso de que se confirme la pre-liquidacion, aplico estos datos auxiliares segun corresponda.
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
 * @lastmodified    $Date: 2009-07-31 15:56:04 -0300 (vie 31 de jul de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de acceso a datos asociada a los datos auxiliares de las liquidaciones.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class LiquidacionesAuxiliar extends AppModel {

    var $permissions = array('permissions' => 448, 'group' => 'default', 'role' => 'all');

    var $belongsTo = array('Liquidacion');
}
?>