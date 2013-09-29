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
		$result = $groupManager->addMemberToGroup($groupId,$loginedId,50);
		
		if ( !$result->hasErrors() ){
			$this->response(200);
		}else {
			$this->response(201,'',$result->getErrors());
		}
	}
}