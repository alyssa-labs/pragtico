<?php
/**
 * Este archivo contiene toda la logica de negocio asociada a la instalacion por primera vez.
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.controllers
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 804 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-07-30 21:34:38 -0300 (Thu, 30 Jul 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */
/**
 * La clase encapsula la logica de negocio asociada a la installacion por primera vez.
 *
 * @package     pragtico
 * @subpackage  app.controllers
 */
class InstallController extends AppController {

	var $uses = null;


	function beforeFilter() {
	}

	function index() {

		$this->layout = 'install';

		$writables[CACHE] = false;
		$writables[APP . 'config' . DS] = false;
		$canWrite = true;
		foreach (array_keys($writables) as $check) {
			if (is_writable($check)) {
				$writables[$check] = true;
			} else {
				$canWrite = false;
			}
		}
		$this->set('writables', $writables);

		if (empty($this->data)) {
			$this->data['Install'] = array('host' => 'localhost', 'name' => '', 'username' => '', 'password' => '');
		} else {

			if (!$canWrite) {
				$this->Session->setFlash('Please check you have proper permissions on requiered files.');
			} else {
				if (!file_exists(CACHE . 'models')) {
					@mkdir(CACHE . 'models');
				}
				if (!file_exists(CACHE . 'persistent')) {
					@mkdir(CACHE . 'persistent');
				}
			}

			/** Try connecting to the database */
			$db = @mysql_connect($this->data['Install']['host'], $this->data['Install']['username'], $this->data['Install']['password']);
			if (!$db) {
				$this->Session->setFlash(__('Unable to connect to database.', true));
			}

			/** Try selecting the database */
			$selected = @mysql_select_db($this->data['Install']['name'], $db);
			if (!$selected) {
				$this->Session->setFlash(__('Could not select database. Did you created it?', true));
			}
				
			$configData = '<?php

class DATABASE_CONFIG {

	var $default = array(
		\'driver\' => \'mysql\',
		\'persistent\' => false,
		\'host\' => \'' . trim($this->data['Install']['host']) . '\',
		\'login\' => \'' . trim($this->data['Install']['username']) . '\',
		\'password\' => \'' . trim($this->data['Install']['password']) . '\',
		\'database\' => \'' . trim($this->data['Install']['name']) . '\',
		\'prefix\' => \'\',
		\'encoding\' => \'utf8\'
	);

}
?>';
			/** Try writting database file */
			$f = @fopen(APP . 'config' . DS . 'database.php', 'w');
			if (!$f) {
				$this->Session->setFlash(__(sprintf('Can\'t open %s for writing. Please check you have proper permissions.', APP . 'config' . DS . 'database.php'), true));
			}
			fwrite($f, $configData);
			fclose($f);


			/** Execute queries */
			$query = '';
			foreach(file(APP . 'config' . DS . 'sql' . DS . 'pragtico_com_ar.sql') as $line) {
				$line = trim($line);
				if (($line != '') && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$query .= $line;
					if (preg_match('/;\s*$/', $line)) {
						$result = mysql_query($query);
						if (!$result) {
							die(mysql_error());
						}
						$query = '';
					}
				}
			}

			$this->Session->setFlash(__(sprintf('Congratulations! You can now login to Pragtico as admin / admin'), true), 'error');
			$this->redirect(array('controller' => 'usuarios', 'action' => 'login'));
		}
	}


}
?>