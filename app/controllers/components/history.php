<?php
/**
 * History Component.
 * Se encarga resolver la navegacion para atras.
 * 
 * Me baso parcialmente en el trabajo de Studio Sipak
 * website: http://webdesign.janenanneriet.nl
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers.components
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 1306 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2010-05-18 16:16:41 -0300 (mar 18 de may de 2010) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica necesaria para resolver la navegacion.
 *
 * @package     pragtico
 * @subpackage  app.controllers.components
 */
class HistoryComponent extends Object {
    
	
/**
 * Actions not to be saved.
 *
 * @var array
 * @access private
 */
	private $__blackListedActions = array('listable', 'save', 'delete', 'login');
	
	
/**
 * If true, avoid saving in session.
 *
 * @var array
 * @access private
 */
    private $__skip = false;
    

/**
 * Prevent the re-initialization of the component.
 *
 * @var boolean
 * @access private
 */
    var $__started = false;
	
	
/**
 * The Controller who instantiated the componet.
 *
 * @var array
 * @access public
 */
	var $controller;

	
/**
 * Initialize the component.
 *
 * @param object $controller A reference to the controller.
 * @return void
 * @access public
 */
    function startup(&$controller) {

        /** Prevent to be executed more than once */
        if (!$this->__started) {
            $this->__skip = false;
            $this->__started = true;
            $this->controller = $controller;
        }
    }


/**
 * Add url to history.
 *
 * @param object $controller A reference to the controller.
 * @return void
 * @access public
 */
    function beforeRender(&$controller) {
		if (!empty($controller->action)) {
        	$this->_addUrl();
		}
    }


/**
 * Goes to a previews visited page.
 *
 * @param integer $pos Position in history where to go back.
 * @return void
 * @access public
 */
    function goBack($pos = 0) {
	    $history = array_reverse($this->controller->Session->read('__history'));

        /*
		$this->log('=================');
		$this->log('Me voy a:');
		$this->log(Router::url($history[$pos]));
		$this->log('=================');
        */
        $this->controller->redirect($history[$pos], true);
    }


    function skip() {
        $this->__skip = true;
    }


    function pushUrl($url) {
        $this->_addUrl($url);
    }


/**
 * Adds current url to history stack,
 *
 * @return void
 * @access private
 */
	function _addUrl($url = null) {
		if (in_array($this->controller->action, $this->__blackListedActions)
            || $this->__skip
			|| $this->controller->params['isAjax'] === true
		    || (isset($this->controller->params['named']['layout']) && $this->controller->params['named']['layout'] === 'lov')) {
			return;
		}

		if (empty($url)) {
			$url['controller'] = $this->controller->name;
			$url['action'] = $this->controller->action;
			$url = array_merge($url, $this->controller->params['pass']);
			$url = array_merge($url, $this->controller->params['named']);
		}

		$history = $this->controller->Session->read('__history');
		if (empty($history)) {
			$this->controller->Session->write('__history', array($url));
		} else {
			
			$count = count($history);
			$history[$count] = $url;
			
			if (serialize($history[$count - 1]) !== serialize($url)) {
				$this->controller->Session->write('__history', array_slice($history, -3));

                /*
				$this->log('=================');
				$this->log('Agrego a __history:');
				$this->log(implode('/', $url));
				$this->log('=================');
				
				$this->log('=================');
				$this->log('__history lo guardo asi:');
                foreach (array_reverse($history) as $k => $url) {
                    $this->log($k . ') ' . implode('/', $url));
                }
				$this->log('=================');
                */
			}
		}
    }


    function log($text) {
        file_put_contents('/tmp/log.txt', $text . "\n", FILE_APPEND);
    }
}
?>