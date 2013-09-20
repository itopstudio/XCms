<?php
/**
 * @name confirmHelloAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-20
 * Encoding UTF-8
 */
class confirmHelloAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$helloId = $this->getPost('helloId',null);
		$type = $this->getPost('type',null);
		$module = $this->getController()->getModule();
		$userManager = $this->app->getComponent($module->userManagerId);
		
		$result = '';
		if ( $type == 1 ){
			$result = $userManager->makeFriends($loginedId,$helloId);
		}elseif ( $type == 2 ){
			$result = $userManager->denyHello($loginedId,$helloId);
		}
		
		if ( $result === true ){
			$this->response(200);
		}else {
			$this->response(201,$result);
		}
	}
}