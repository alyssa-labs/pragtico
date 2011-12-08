<?php
/**
      class UsersController extends AppController {
          var $name="Users";
          var $components = array('Helper');
          var $actionHelpers = array('Number', 'Time');
          
          function index() {
              $example = $this->Number->precision(100.1151933, 2);
              // $example -> 100.11
          }
      }
*/

class HelperComponent extends Object {
	var $controller;

	function startup(&$controller) {
		$this->controller = $controller;
		if (isset($controller->actionHelpers)) {
			$this->pushHelpers();
		}
	}
 
	function pushHelpers() {
		foreach ($this->controller->actionHelpers as $helper) {
			$helper = ucfirst($helper);
			App::import("Helper", $helper);
			$_helperClassName = $helper . "Helper";
			$this->controller->{$helper} = new $_helperClassName();
		}
	}
}
?>