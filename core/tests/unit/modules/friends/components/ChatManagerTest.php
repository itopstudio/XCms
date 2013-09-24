<?php
/**
 * @name ChatManagerTest.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-24
 * Encoding UTF-8
 */
class ChatManagerTest extends CDbTestCase{
	public $chatManager;
	
	protected function setUp(){
		parent::setUp();
		$this->chatManager = Yii::app()->getModule('chatManager');
	}
	
	public function testPush(){
		
	}
}