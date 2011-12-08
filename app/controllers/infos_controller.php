<?php
/**
 * Este archivo contiene toda la logica de negocio asociada al manejo de informaciones varias.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Pragmatia de RPB S.A.
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 766 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-28 09:33:09 -0300 (Tue, 28 Jul 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada al manejo de informaciones varias.
 *
 * @package	    pragtico
 * @subpackage  app.controllers
 */
class InfosController extends AppController {


	function index() {

		$this->set('invoiceErrors', $this->Info->findInvoiceErrors());
		$this->set('relationErrors', $this->Info->findRelationErrors());

		
	}

}
?>