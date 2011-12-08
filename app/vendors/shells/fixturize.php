<?php
/**
 * A CakePHP shell to create a fixture using the values of any given database table so one does not have to type it up manually.
 *
 * Copyright 2008, Debuggable, Ltd.
 * Hibiskusweg 26c
 * 13089 Berlin, Germany
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2008, Debuggable, Ltd.
 * @version 1.0
 * @author Felix Geisendörfer <felix@debuggable.com>, Tim Koschützki <tim@debuggable.com>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
App::import(array('Model', 'AppModel', 'File'));
class FixturizeShell extends Shell{
 	function main() {
		if ($this->args && $this->args[0] == '?') {
			return $this->out('Usage: ./cake fixturize <table> [-force] [-reindex] [-fields] [-limit 10]');
		}
		
		/**
		* Default options.
		*/
		$options = array(
			'force' => false,
			'reindex' => false,
			'all' => false,
			'fields' => false,
			'limit' => null
		);
		$options = array_merge($options, $this->params);
		
		if ($options['all']) {
			$db = ConnectionManager::getDataSource('default');
			$this->args = $db->listSources();
		}
		
		$binaries = array();
	
		if (empty($this->args)) {
			return $this->err('Usage: ./cake fixturize <table> [-force] [-reindex] [-fields] [-limit 10]');
		}
		foreach ($this->args as $table) {
			$name = Inflector::classify($table);
			$Model = new AppModel(array(
				'name' => $name,
				'table' => $table,
			));
	
		$file = sprintf('%stests/fixtures/%s_fixture.php', APP, Inflector::underscore($name));
		$File = new File($file);
		if ($File->exists() && !$options['force']) {
			$this->err(sprintf('File %s already exists, use --force option.', $file));
			continue;
		}
		
		/**
		* Limit Support
		*/
		$conditions['limit'] = $options['limit'];
		$records = $Model->find('all', $conditions);
	
		$out = array();
		$out[] = '<?php
/**
* Este archivo contiene los datos de un fixture para los casos de prueba.
*
* PHP versions 5
*
* @filesource
* @copyright		Copyright 2007-2008, Pragmatia de RPB S.A.
* @link			http://www.pragmatia.com
* @package			pragtico
* @subpackage		app.tests.fixtures
* @since			Pragtico v 1.0.0
* @version			$Revision: 54 $
* @modifiedby		$LastChangedBy: mradosta $
* @lastmodified	$Date: 2008-10-23 23:14:28 -0300 (Thu, 23 Oct 2008) $
* @author      	Martin Radosta <mradosta@pragmatia.com>
*/
/**
* La clase para un fixture para un caso de prueba.
*
* @package app.tests
* @subpackage app.tests.fixtures
*/';	  
		$out[] = sprintf('class %sFixture extends CakeTestFixture {', $name);
		$out[] = '';
		$out[] = '
/**
* El nombre de este Fixture.
*
* @var array
* @access public
*/';
		$out[] = sprintf('    var $name = \'%s\';', $name);
		$out[] = '';
		
		/**
		* Fields support
		*/
		if($options['fields']) {
		$out[] = '
/**
* La definicion de la tabla.
*
* @var array
* @access public
*/';
			$out[] = '    var $fields = array(';
			foreach($Model->schema() as $fieldName=>$description) {
				if(empty($description['length'])) {
					unset($description['length']);
				}
				if(isset($description['type'])) {
					if(in_array($description['type'], array('date', 'datetime')) 
						&& isset($description['null']) && $description['null'] === false 
						&& empty($description['default'])) {
						unset($description['default']);
					}
					elseif($description['type'] === 'binary') {
						$binaries[] = $fieldName;
					}
				}
			$outTmp = array();
			foreach($description as $k=>$v) {
				if($k === 'type' && preg_match('/enum\(\'.*\'\)/', $v)) {
					$v = 'string';
				}
				
				if ($k === 'null' && empty($v)) {
					$outTmp[] = sprintf('\'%s\' => %s', $k, 'false');
				}
				else {
					$outTmp[] = sprintf('\'%s\' => \'%s\'', $k, $v);
				}
			}
			$out[] = sprintf('        \'%s\' => array(%s),', $fieldName, implode(', ', $outTmp));
			}
			$out[] = '    );';
			$out[] = '';
		}
		
		$out[] = '
/**
* Los registros.
*
* @var array
* @access public
*/';
		$out[] = '    var $records = array(';
		foreach ($records as $record) {
			$out[] = '        array(';
			if ($options['reindex']) {
				foreach (array('old_id', 'vendor_id') as $field) {
					if ($Model->hasField($field)) {
						$record[$name][$field] = $record[$name]['id'];
						break;
					}
				}
				$record[$name]['id'] = String::uuid();
			}
			foreach ($record[$name] as $field => $val) {
				if(in_array($field, $binaries)) {
					$val = '';
				}
				$out[] = sprintf('            \'%s\' => \'%s\',', addcslashes($field, "'"), addcslashes($val, "'"));
			}
			$out[] = '        ),';
		}
		$out[] = '    );';
		$out[] = '}';
		$out[] = '';
		$out[] = '?>';
	
		$File->write(join("\n", $out));
		$this->out(sprintf('-> Create %sFixture with %d records (%d bytes) in "%s"', $name, count($records), $File->size(), $file));
		}
	}
}
 
?>