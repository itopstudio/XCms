<?php
/**
 * @name updateRemarkAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-2
 * Encoding UTF-8
 */
class updateRemarkAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$target = $this->getPut('target',null);
		$remark = $this->getPut('remark',null);
		if ( $target === null ){
			$this->response(201);
		}
		
		$module = $this->getController()->getModule();
		$userManager = $this->app->getComponent($module->userManagerId);
		$result = $userManager->modifyRemark($loginedId,$target,$remark);
		if ( $result === true ){
			$this->response(200);
		}else {
			$this->response(201);
		}
	}
}