<?php
/**
 * @name applyForJoinGroupAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-29
 * Encoding UTF-8
 */
class applyForJoinGroupAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$groupId = $this->getPost('group',null);
		if ( $groupId === null ){
			$this->response(201);
		}
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$group = $groupManager->findByPk($groupId,array('select'=>'master_id'));
		if ( $group === null ){
			$this->response(201);
		}
		$result = $groupManager->addMemberToGroup($groupId,$loginedId,50);
		
		if ( !$result->hasErrors() ){
			$chatManager = $this->app->getComponent('chatManager');
			$chatManager->getPusher()->setTimeToLive(864000);
			$alias = 'user'.$group->master_id;
			$chatManager->pushNotification(1,$alias,1,'有用户申请加群，请到聊天-更多中查看','社区宝聊天',array('time'=>time()));
			
			$this->response(200);
		}else {
			$this->response(201,'',$result->getErrors());
		}
	}
}