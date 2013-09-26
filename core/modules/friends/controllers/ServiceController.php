<?php
/**
 * @name ServiceController.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class ServiceController extends CmsController{
	public $actionClassPathAlias = 'friends.controllers';
	
	public function loginRequired(){
		$this->response(400,'请登录');
	}
	
	public function filters(){
		$filters = parent::filters();
		return array($filters['hasLogined']);
	}
	
	public function getActionClass(){
		return array(
				'createHello' => array('class'=>'sayHello'),
				'createFriend' => array('class'=>'confirmHello'),
				'removeFriend' => array('class'=>'breakUp'),
				'createTrend' => array('class'=>'publishTrend'),
				'removeTrend',
				'getFriendsTrends',
				'getTrends',
				'createReply' => array('class'=>'replyTrend'),
				'createSupport' => array('class'=>'supportTrend'),
				'getSayHelloToMe',
				'getRandomFriends' => array('class'=>'getRandomList'),
				'chat',
				'getOfflineMessage'
		);
	}
}