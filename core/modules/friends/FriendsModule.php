<?php
/**
 * @name FriendsModule.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class FriendsModule extends CmsModule{
	/**
	 * 
	 * @var string
	 */
	public $userManagerId = 'UserManager';
	
	protected function init(){
		parent::init();
		Yii::import('friends.components.*');
		Yii::import('friends.models.*');
		Yii::app()->getUser()->setStateKeyPrefix(Yii::app()->params['frontUserStateKeyPrefix']);
		
		Yii::app()->setComponents(array(
				'trendsManager' => array(
						'class' => 'friends.components.TrendsManager'
				),
				'chatManager' => array(
						'class' => 'friends.components.ChatManager',
				),
		));
	}
	
	public function loadSelfModels(){
		Yii::import('friends.models.*');
	}
}