<?php
/**
 * Short description for file.
 *
 * Long description for file
 *
 * PHP versions 5
 *
 * @filesource
 * @copyright       Copyright 2007-2009, Pragmatia
 * @link            http://www.pragmatia.com
 * @package         pragtico
 * @subpackage      app.tests.models
 * @since           Pragtico v 1.0.0
 * @version         $Revision: 319 $
 * @modifiedby      $LastChangedBy: mradosta $
 * @lastmodified    $Date: 2009-02-24 13:57:32 -0200 (mar 24 de feb de 2009) $
 * @author          Martin Radosta <mradosta@pragmatia.com>
 */

App::import('Component', 'Session');
require_once(TESTS . 'cases' . DS . 'models' . DS . 'models.php');


App::import('Core', 'DboSource');
Mock::generate('DboSource', 'MySqlMockDboSource');


class ModelTest extends CakeTestCase {


	function setUp() {
		$this->criticDb =& new MySqlMockDboSource();
		$this->criticDb->fullDebug = true;
	}


/**
 * fixtures property
 *
 * @var array
 * @access public
 */
    var $fixtures = array('core.article', 'core.comment');
    
    
/**
 * startTest method
 *
 * @access public
 * @return void
 */
    function startTest() {
    }

    
/**
 * __detachBehaviors method
 *
 * @access private
 * @return void
 */
    function __detachBehaviors(&$model) {
        foreach ($model->Behaviors->attached() as $behavior) {
            $model->Behaviors->detach($behavior);
        }
    }

    
    function xtestSaveMultipleSingleModelx() {
        $Article = new Article();
        $this->__detachBehaviors($Article);
        $this->__detachBehaviors($Article->Comment);
        $Article->Behaviors->attach('Containable');
        $this->db->truncate($Article);
        $this->db->truncate($Article->Comment);
        $Article->validate = array();
        $Article->Comment->validate = array();
        

        /**
        * Try inserting.
        */
        $data = null;
        $data = array(
            'Article' => array(
                array('published' => 'yd', 'title' => 'First Article'),
                array('published' => 'lx', 'title' => 'Second Article'),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        d($Article->savedDataLog);
        //$this->assertEqual($Article->savedDataLog, array('totalRecords' => 1, 'totalRecordsSaved' => 0, 'errors' => ));

        $this->assertEqual($Article->find('all', array('recursive' => -1)), array());
        die;


        /**
        * Try updating.
        */
        $data = null;
        $data = array(
            'Article' => array(
                array('id' => 1, 'title' => 'Modified First Article'),
                array('id' => 2, 'title' => 'Modified Second Article'),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 1, 'totalRecordsSaved' => 1));

        $expected = null;
        $expected = array('Modified First Article', 'Modified Second Article');
        $this->assertEqual(Set::extract('/Article/title', $Article->find('all', array('recursive' => -1))), $expected);
    }
    
/**
 * testSaveMultipleHasManyWithValidationOnMasterAndDetail method
 *
 * @access public
 * @return void
 */    
    function testSaveMultipleHasManyWithValidationOnMasterAndDetail() {
        $Article = new Article();
		$this->db->config['driver'] = 'MySqlMockDboSource';
        $this->__detachBehaviors($Article);
        $this->__detachBehaviors($Article->Comment);
        $Article->Behaviors->attach('Containable');
		$this->criticDb->expectAtLeastOnce('truncate');
        $this->db->truncate($Article);
        $this->db->truncate($Article->Comment);

		d($this->criticDb->fullDebug);
		//d("X");
        
        /**
        * Try inserting.
        */
        $Article->validate = array(
            'title' => array(
                array(
                    'rule'      => VALID_NUMBER,
                    'message'   =>'Invalid Number')
            )
        );
        $Article->Comment->validate = array(
            'comment' => array(
                array(
                    'rule'      => VALID_NUMBER,
                    'message'   =>'Invalid Number')
            )
        );
        $data = null;
        $data[] = array(
            'Article' => array(
                'user_id' => 1, 'title' => '1',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => '1', 'user_id' => 1),
                array('article_id' => null, 'comment' => 'Second comment for First Article', 'user_id' => 1),
                array('article_id' => null, 'comment' => '3', 'user_id' => 1),
            ),
        );
        $data[] = array(
            'Article' => array(
                'user_id' => 2, 'title' => 'Second Article with hasMany comments',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => '10', 'user_id' => 2),
                array('article_id' => null, 'comment' => '20', 'user_id' => 2),
                array('article_id' => null, 'comment' => 'Third comment for Second Article', 'user_id' => 2),
            ),
        );
        
        $result = $Article->appSave($data);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 2, 'totalRecordsSaved' => 0));
        $this->assertFalse($result);
        $expected = null;
        $expected = array(
            0 =>
                array('Comment' =>
                    array(
                        1 => array('comment' => 'Invalid Number'))),
            1 =>
                array(
                    'Article' => array('title' => 'Invalid Number'),
                    'Comment' =>
                        array(
                            2 => array('comment' => 'Invalid Number'))));
        $this->assertEqual($Article->validationErrors, $expected);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 2, 'totalRecordsSaved' => 0));
    }
    
    
/**
 * testSaveMultipleHasManyWithValidationOnMaster method
 *
 * @access public
 * @return void
 */    
    function testSaveMultipleHasManyWithValidationOnMaster() {
        $Article = new Article();
        $this->__detachBehaviors($Article);
        $this->__detachBehaviors($Article->Comment);
        $Article->Behaviors->attach('Containable');
        $this->db->truncate($Article);
        $this->db->truncate($Article->Comment);
        $Article->Comment->validate = array();

        
        /**
        * Try inserting.
        */
        $Article->validate = array(
            'title' => array(
                array(
                    'rule'      => VALID_NUMBER,
                    'message'   =>'Invalid Number')
            )
        );
        $data = null;
        $data[] = array(
            'Article' => array(
                'user_id' => 1, 'title' => '1',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => 'First comment for First Article', 'user_id' => 1),
                array('article_id' => null, 'comment' => 'Second comment for First Article', 'user_id' => 1),
                array('article_id' => null, 'comment' => 'Third comment for First Article', 'user_id' => 1),
            ),
        );
        $data[] = array(
            'Article' => array(
                'user_id' => 2, 'title' => 'Second Article with hasMany comments',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => 'First comment for Second Article', 'user_id' => 2),
                array('article_id' => null, 'comment' => 'Second comment for Second Article', 'user_id' => 2),
                array('article_id' => null, 'comment' => 'Third comment for Second Article', 'user_id' => 2),
            ),
        );
        
        $result = $Article->appSave($data);
        $this->assertFalse($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 2, 'totalRecordsSaved' => 1));
        $this->assertEqual($Article->validationErrors, array(1 => array('Article' => array('title' => 'Invalid Number'))));
    }
    
    
/**
 * testSaveMultipleHasManyWithValidationOnDetail method
 *
 * @access public
 * @return void
 */    
    function testSaveMultipleHasManyWithValidationOnDetail() {
        $Article = new Article();
        $this->__detachBehaviors($Article);
        $this->__detachBehaviors($Article->Comment);
        $Article->Behaviors->attach('Containable');
        $this->db->truncate($Article);
        $this->db->truncate($Article->Comment);
        $Article->validate = array();

        
        $Article->Comment->validate = array(
            'comment' => array(
                array(
                    'rule'      => VALID_NUMBER,
                    'message'   =>'Invalid Number')
            )
        );
        /**
        * Try inserting.
        */
        $data = null;
        $data[] = array(
            'Article' => array(
                'user_id' => 1, 'title' => 'First Article with hasMany comments',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => '1', 'user_id' => 1),
                array('article_id' => null, 'comment' => 'Second comment for First Article', 'user_id' => 1),
                array('article_id' => null, 'comment' => '2', 'user_id' => 1),
            ),
        );
        $data[] = array(
            'Article' => array(
                'user_id' => 2, 'title' => 'Second Article with hasMany comments',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => 'First comment for Second Article', 'user_id' => 2),
                array('article_id' => null, 'comment' => '20', 'user_id' => 2),
                array('article_id' => null, 'comment' => 'Third comment for Second Article', 'user_id' => 2),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertFalse($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 2, 'totalRecordsSaved' => 0));
        $expected = null;
        $expected = array(
            0 =>
                array('Comment' =>
                    array(
                        1 => array('comment' => 'Invalid Number'))),
            1 =>
                array('Comment' =>
                    array(
                        0 => array('comment' => 'Invalid Number'),
                        2 => array('comment' => 'Invalid Number'))));
        $this->assertEqual($Article->validationErrors, $expected);
    }
    
    
/**
 * testSaveMultipleSingleModel method
 *
 * @access public
 * @return void
 */    
    function testSaveMultipleSingleModel() {
        $Article = new Article();
        $this->__detachBehaviors($Article);
        $this->__detachBehaviors($Article->Comment);
        $Article->Behaviors->attach('Containable');
        $this->db->truncate($Article);
        $this->db->truncate($Article->Comment);
        $Article->validate = array();
        $Article->Comment->validate = array();
        

        /**
        * Try inserting.
        */
        $data = null;
        $data = array(
            'Article' => array(
                array('user_id' => 1, 'title' => 'First Article'),
                array('user_id' => 1, 'title' => 'Second Article'),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 1, 'totalRecordsSaved' => 1));

        $expected = null;
        $expected = array('First Article', 'Second Article');
        $this->assertEqual(Set::extract('/Article/title', $Article->find('all', array('recursive' => -1))), $expected);


        /**
        * Try updating.
        */
        $data = null;
        $data = array(
            'Article' => array(
                array('id' => 1, 'title' => 'Modified First Article'),
                array('id' => 2, 'title' => 'Modified Second Article'),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 1, 'totalRecordsSaved' => 1));

        $expected = null;
        $expected = array('Modified First Article', 'Modified Second Article');
        $this->assertEqual(Set::extract('/Article/title', $Article->find('all', array('recursive' => -1))), $expected);
    }

    
    
/**
 * testSaveMultipleHasMany method
 *
 * @access public
 * @return void
 */    
    function testSaveMultipleHasMany() {
        $Article = new Article();
        $this->__detachBehaviors($Article);
        $this->__detachBehaviors($Article->Comment);
        $Article->Behaviors->attach('Containable');
        $this->db->truncate($Article);
        $this->db->truncate($Article->Comment);
        $Article->validate = array();
        $Article->Comment->validate = array();

        /**
        * Try inserting.
        */
        $data = null;
        $data[] = array(
            'Article' => array(
                'user_id' => 1, 'title' => 'First Article with hasMany comments',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => 'First comment for First Article', 'user_id' => 1),
                array('article_id' => null, 'comment' => 'Second comment for First Article', 'user_id' => 1),
                array('article_id' => null, 'comment' => 'Third comment for First Article', 'user_id' => 1),
            ),
        );
        $data[] = array(
            'Article' => array(
                'user_id' => 2, 'title' => 'Second Article with hasMany comments',
            ),
            'Comment' => array(
                array('article_id' => null, 'comment' => 'First comment for Second Article', 'user_id' => 2),
                array('article_id' => null, 'comment' => 'Second comment for Second Article', 'user_id' => 2),
                array('article_id' => null, 'comment' => 'Third comment for Second Article', 'user_id' => 2),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 2, 'totalRecordsSaved' => 2));

        $expected = null;
        $expected[] = array('First comment for First Article', 'Second comment for First Article', 'Third comment for First Article');
        $expected[] = array('First comment for Second Article', 'Second comment for Second Article', 'Third comment for Second Article');
        foreach ($Article->find('all') as $k => $article) {
            $this->assertEqual(Set::extract($article['Comment'], '{n}.comment'), $expected[$k]);
        }


        /**
        * Try updating.
        */
        $data = null;
        $data[] = array(
            'Article' => array(
                'id' => 2, 'user_id' => 2, 'title' => 'Modified Second Article with hasMany comments',
            ),
            'Comment' => array(
                array('id' => 4, 'article_id' => 2, 'comment' => 'Modified First comment for Second Article', 'user_id' => 2),
                array('id' => 6, 'article_id' => 2, 'comment' => 'Modified Third comment for Second Article', 'user_id' => 2),
            ),
        );
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 1, 'totalRecordsSaved' => 1));
        
        $expected = null;
        $expected = array('Modified First comment for Second Article', 'Second comment for Second Article', 'Modified Third comment for Second Article');
        $article = $Article->read();
        $this->assertEqual(Set::extract($article['Comment'], '{n}.comment'), $expected);
        

        /**
        * Try updating with unique detail.
        */
        $data = null;
        $data[] = array(
            'Article' => array(
                'id' => 2, 'user_id' => 2, 'title' => 'Modified Second Article with hasMany comments',
            ),
            'Comment' => array(
                array('id' => 4, 'article_id' => 2, 'comment' => 'Modified First comment for Second Article', 'user_id' => 2),
                array('id' => 6, 'article_id' => 2, 'comment' => 'Modified Third comment for Second Article', 'user_id' => 2),
            ),
        );
        $Article->hasMany['Comment']['unique'] = true;
        $result = $Article->appSave($data);
        $this->assertTrue($result);
        $this->assertEqual($Article->savedDataLog, array('totalRecords' => 1, 'totalRecordsSaved' => 1));
        
        $expected = null;
        $expected = array('Modified First comment for Second Article', 'Modified Third comment for Second Article');
        $article = $Article->read();
        $this->assertEqual(Set::extract($article['Comment'], '{n}.comment'), $expected);
    }

    

}
?>