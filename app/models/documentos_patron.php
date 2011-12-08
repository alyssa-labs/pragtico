<?php
/**
 * Este archivo contiene toda la logica de acceso a datos asociada a los patrones de los documentos modelo del sistema.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2005-2008, Pragmatia de RPB S.A.
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
 * La clase encapsula la logica de acceso a datos asociada a los patrones de los documentos modelo del sistema.
 *
 * @package     pragtico
 * @subpackage  app.models
 */
class DocumentosPatron extends AppModel {

    var $permissions = array('permissions' => 508, 'group' => 'none', 'role' => 'higher');

    var $belongsTo = array('Documento');
    
}
?>