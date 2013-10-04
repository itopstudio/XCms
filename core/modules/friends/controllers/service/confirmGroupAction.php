<?php
/**
 * @name confirmGroupAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-30
 * Encoding UTF-8
 */
class confirmGroupAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$groupId = $this->getPost('group',null);
		$userId = $this->getPost('user',null);
		
		if ( $groupId === null || $userId === null ){
			$this->response(201);
		}
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$result = $groupManager->confirmGroupAdd($loginedId,$groupId,$userId);
		if ( $result === true ){
			$chatManager = $this->app->getComponent('chatManager');
			$chatManager->getPusher()->setTimeToLive(864000);
			$alias = 'user'.$userId;
			$chatManager->pushNotification(1,$alias,1,'管理员同意您加入群，请到聊天-更多中查看','社区宝聊天',array('time'=>time()));
			
			$this->response(200);
		}else {
			$this->response(201);
		}
	}
}