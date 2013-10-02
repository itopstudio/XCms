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
				'createGroup' => array('class'=>'addGroup'),
				'createConfirmGroup' => array('class' => 'confirmGroup'),
				'createGroupMember' => array('class'=>'applyForJoinGroup'),
				'createReply' => array('class'=>'replyTrend'),
				'createSupport' => array('class'=>'supportTrend'),
				'createTrend' => array('class'=>'publishTrend'),
				
				'removeGroupMember' => array('class'=>'quitGroup'),
				'removeFriend' => array('class'=>'breakUp'),
				'removeTrend',
				
				'updateRemark',
				
				'getGroupApplication',
				'getFriendsTrends',
				'getTrends',
				'getSayHelloToMe',
				'getGroups' => array('class'=>'getMyGroups'),
				'getGroupMember',
				'getJoinedGroups',
				'getSearchGroups' => array('class'=>'getSearchGroupsList'),
				'getRandomGroups' => array('class'=>'getRandomGroupsList'),
				'getRandomFriends' => array('class'=>'getRandomFriendsList'),
				'getSearchFriends',
				'chat',
				'getOfflineMessage'
		);
	}
}