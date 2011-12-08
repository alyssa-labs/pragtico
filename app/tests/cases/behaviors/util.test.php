<?php
/* SVN FILE: $Id: containable.test.php 7821 2008-11-03 23:58:44Z renan.saddam $ */
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) Tests <https://trac.cakephp.org/wiki/Developement/TestSuite>
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 *  Licensed under The Open Group Test Suite License
 *  Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          https://trac.cakephp.org/wiki/Developement/TestSuite CakePHP(tm) Tests
 * @package       cake.tests
 * @subpackage    cake.tests.cases.libs.model.behaviors
 * @since         CakePHP(tm) v 1.2.0.5669
 * @version       $Revision: 319 $
 * @modifiedby    $LastChangedBy: mradosta $
 * @lastmodified  $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @license       http://www.opensource.org/licenses/opengroup.php The Open Group Test Suite License
 */

App::import('Core', array('AppModel', 'Model'));
//require_once(dirname(dirname(__FILE__)) . DS . 'models.php');
require_once('/var/www/pragtico/cake/tests/cases/libs/model/models.php');
/**
 * UtilTest class
 *
 * @package       cake
 * @subpackage    cake.tests.cases.libs.model.behaviors
 */
class UtilTest extends CakeTestCase {
/**
 * Fixtures associated with this test case
 *
 * @var array
 * @access public
 */
	var $fixtures = array(
		'core.article', 'core.article_featured', 'core.article_featureds_tags', 'core.articles_tag', 'core.attachment', 'core.category',
		'core.comment', 'core.featured', 'core.tag', 'core.user'
	);
/**
 * Method executed before each test
 *
 * @access public
 */
	function startTest() {
		$this->User =& ClassRegistry::init('User');
		$this->Article =& ClassRegistry::init('Article');

		$this->User->bind(array(
			'Article' => array('type' => 'hasMany'),
			'ArticleFeatured' => array('type' => 'hasMany'),
			'Comment' => array('type' => 'hasMany')
		));
		$this->User->ArticleFeatured->unbindModel(array('belongsTo' => array('Category')), false);
		$this->User->ArticleFeatured->hasMany['Comment']['foreignKey'] = 'article_id';

		$this->User->Behaviors->attach('Util');
		$this->Article->Behaviors->attach('Util');
	}
/**
 * Method executed after each test
 *
 * @access public
 */
	function endTest() {
		unset($this->Article);
		unset($this->User);

		ClassRegistry::flush();
	}
/**
 * testContainments method
 *
 * @access public
 * @return void
 */
	function testContainments() {
		$r = $this->__containments($this->Article, array('Comment' => array('conditions' => array('Comment.user_id' => 2))));
		$this->assertTrue(Set::matches('/Article/keep/Comment/conditions[Comment.user_id=2]', $r));

		$r = $this->__containments($this->User, array(
			'ArticleFeatured' => array(
				'Featured' => array(
					'id',
					'Category' => 'name'
				)
		)));
		$this->assertEqual(Set::extract('/ArticleFeatured/keep/Featured/fields', $r), array('id'));

		$r = $this->__containments($this->Article, array(
			'Comment' => array(
				'User',
				'conditions' => array('Comment' => array('user_id' => 2)),
			),
		));
		$this->assertTrue(Set::matches('/User', $r));
		$this->assertTrue(Set::matches('/Comment', $r));
		$this->assertTrue(Set::matches('/Article/keep/Comment/conditions/Comment[user_id=2]', $r));

		$r = $this->__containments($this->Article, array('Comment(comment, published)' => 'Attachment(attachment)', 'User(user)'));
		$this->assertTrue(Set::matches('/Comment', $r));
		$this->assertTrue(Set::matches('/User', $r));
		$this->assertTrue(Set::matches('/Article/keep/Comment', $r));
		$this->assertTrue(Set::matches('/Article/keep/User', $r));
		$this->assertEqual(Set::extract('/Article/keep/Comment/fields', $r), array('comment', 'published'));
		$this->assertEqual(Set::extract('/Article/keep/User/fields', $r), array('user'));
		$this->assertTrue(Set::matches('/Comment/keep/Attachment', $r));
		$this->assertEqual(Set::extract('/Comment/keep/Attachment/fields', $r), array('attachment'));

		$r = $this->__containments($this->Article, array('Comment' => array('limit' => 1)));
		$this->assertEqual(array_keys($r), array('Comment', 'Article'));
		$this->assertEqual(array_shift(Set::extract('/Comment/keep', $r)), array('keep' => array()));
		$this->assertTrue(Set::matches('/Article/keep/Comment', $r));
		$this->assertEqual(array_shift(Set::extract('/Article/keep/Comment/.', $r)), array('limit' => 1));

		$r = $this->__containments($this->Article, array('Comment.User'));
		$this->assertEqual(array_keys($r), array('User', 'Comment', 'Article'));
		$this->assertEqual(array_shift(Set::extract('/User/keep', $r)), array('keep' => array()));
		$this->assertEqual(array_shift(Set::extract('/Comment/keep', $r)), array('keep' => array('User' => array())));
		$this->assertEqual(array_shift(Set::extract('/Article/keep', $r)), array('keep' => array('Comment' => array())));
	}
/**
 * testInvalidContainments method
 *
 * @access public
 * @return void
 */
	function testInvalidContainments() {
		$this->expectError();
		$r = $this->__containments($this->Article, array('Comment', 'InvalidBinding'));

		$this->Article->Behaviors->attach('Util', array('notices' => false));
		$r = $this->__containments($this->Article, array('Comment', 'InvalidBinding'));
	}
/**
 * testBeforeFind method
 *
 * @access public
 * @return void
 */
	function testBeforeFind() {
// 		$r = $this->Article->find('all', array('contain' => array('Comment')));
// 		$this->assertFalse(Set::matches('/User', $r));
// 		$this->assertTrue(Set::matches('/Comment', $r));
// 		$this->assertFalse(Set::matches('/Comment/User', $r));

/*		$r = $this->Article->find('all', array('contain' => 'Comment.User'));
		$this->assertTrue(Set::matches('/Comment/User', $r));
		$this->assertFalse(Set::matches('/Comment/Article', $r));
*/
/*
		$r = $this->Article->find('all', array('contain' => array('Comment' => array('User', 'Article'))));
		$this->assertTrue(Set::matches('/Comment/User', $r));
		$this->assertTrue(Set::matches('/Comment/Article', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('conditions' => array('Comment.user_id' => 2)))));
		$this->assertFalse(Set::matches('/Comment[user_id!=2]', $r));
		$this->assertTrue(Set::matches('/Comment[user_id=2]', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment.user_id = 2')));
		$this->assertFalse(Set::matches('/Comment[user_id!=2]', $r));

		$r = $this->Article->find('all', array('contain' => 'Comment.id DESC'));
		$ids = $descIds = Set::extract('/Comment[1]/id', $r);
		rsort($descIds);
		$this->assertEqual($ids, $descIds);

		$r = $this->Article->find('all', array('contain' => 'Comment'));
		$this->assertTrue(Set::matches('/Comment[user_id!=2]', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('fields' => 'comment'))));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertFalse(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('fields' => array('comment', 'updated')))));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertTrue(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('comment', 'updated'))));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertTrue(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment(comment,updated)')));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertTrue(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => 'Comment.created'));
		$this->assertTrue(Set::matches('/Comment/created', $r));
		$this->assertFalse(Set::matches('/Comment/comment', $r));
*/

		$r = $this->Article->find('all', array('contain' => array('User.Article(title)', 'Comment(comment)')));
		$this->assertFalse(Set::matches('/Comment/Article', $r));
		$this->assertFalse(Set::matches('/Comment/User', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		//d($r);
		$this->assertTrue(Set::matches('/User/Article/title', $r));
		$this->assertFalse(Set::matches('/User/Article/created', $r));
/*
		$r = $this->Article->find('all', array('contain' => array()));
		$this->assertFalse(Set::matches('/User', $r));
		$this->assertFalse(Set::matches('/Comment', $r));

		$this->expectError();
		$r = $this->Article->find('all', array('contain' => array('Comment' => 'NonExistingBinding')));*/
	}

/**
 * containments method
 *
 * @param mixed $Model
 * @param array $contain
 * @access private
 * @return void
 */
	function __containments(&$Model, $contain = array()) {
		if (!is_array($Model)) {
			$result = $Model->containments($contain);
			return $this->__containments($result['models']);
		} else {
			$result = $Model;
			foreach ($result as $i => $containment) {
				$result[$i] = array_diff_key($containment, array('instance' => true));
			}
		}

		return $result;
	}
/**
 * assertBindings method
 *
 * @param mixed $Model
 * @param array $expected
 * @access private
 * @return void
 */
	function __assertBindings(&$Model, $expected = array()) {
		$expected = array_merge(array('belongsTo' => array(), 'hasOne' => array(), 'hasMany' => array(), 'hasAndBelongsToMany' => array()), $expected);

		foreach ($expected as $binding => $expect) {
			$this->assertEqual(array_keys($Model->$binding), $expect);
		}
	}
/**
 * bindings method
 *
 * @param mixed $Model
 * @param array $extra
 * @param bool $output
 * @access private
 * @return void
 */
	function __bindings(&$Model, $extra = array(), $output = true) {
		$relationTypes = array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany');

		$debug = '[';
		$lines = array();
		foreach ($relationTypes as $binding) {
			if (!empty($Model->$binding)) {
				$models = array_keys($Model->$binding);
				foreach ($models as $linkedModel) {
					$line = $linkedModel;
					if (!empty($extra) && !empty($Model->{$binding}[$linkedModel])) {
						$extraData = array();
						foreach (array_intersect_key($Model->{$binding}[$linkedModel], array_flip($extra)) as $key => $value) {
							$extraData[] = $key . ': ' . (is_array($value) ? '(' . implode(', ', $value) . ')' : $value);
						}
						$line .= ' {' . implode(' - ', $extraData) . '}';
					}
					$lines[] = $line;
				}
			}
		}
		$debug .= implode(' | ' , $lines);
		$debug .=  ']';
		$debug = '<strong>' . $Model->alias . '</strong>: ' . $debug . '<br />';

		if ($output) {
			echo $debug;
		}

		return $debug;
	}
}

?>