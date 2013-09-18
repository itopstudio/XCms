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
	public $userManagerId = null;
	
	protected function init(){
		parent::init();
		Yii::import('friends.components.*');
		Yii::import('friends.models.*');
		Yii::app()->getUser()->setStateKeyPrefix(Yii::app()->params['frontUserStateKeyPrefix']);
		Yii::app()->setComponent('trendsManager',array(
				'class' => 'friends.components.TrendsManager'
		));
	}
	
	public function loadSelfModels(){
		Yii::import('friends.models.*');
	}
}