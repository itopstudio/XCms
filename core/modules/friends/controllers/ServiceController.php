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
	
	public function getActionClass(){
		return array(
				'createFriends' => array('class'=>'makeFriends'),
				'removeFriends' => array('class'=>'breakUp'),
				'createTrend' => array('class'=>'publishTrend'),
				'removeTrend',
				'getMyTrends',
				'getFriendsTrends',
				'createReply' => array('class'=>'replyTrend'),
				'createSupport' => array('class'=>'supportTrend'),
				'getSayHelloToMe',
				'getRandomFriendList' => array('class'=>'getRandomList'),
		);
	}
}