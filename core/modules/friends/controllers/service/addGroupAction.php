<?php
/**
 * @name addGroupAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-29
 * Encoding UTF-8
 */
class addGroupAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$name = $this->getPost('name',null);
		$description = $this->getPost('description',null);
		if ( $name === null ){
			$this->response(201);
		}
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$result = $groupManager->createGroup($loginedId,$name,0,$description);
		
		if ( !$result->hasErrors() ){
			$this->response(200);
		}else {
			$this->response(201,'',$result);
		}
	}
}