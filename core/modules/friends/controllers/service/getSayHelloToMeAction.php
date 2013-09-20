<?php
/**
 * @name getSayHelloToMeAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getSayHelloToMeAction extends CmsAction{
	public function run($resourceId){
		$loginedUid = $this->app->getUser()->getId();
		if ( $loginedUid === $resourceId ){
			$module = $this->getController()->getModule();
			$userManager = $this->app->getComponent($module->userManagerId);
			$list = $userManager->getSayHelloList($loginedUid);
			$this->response(300,'',$list);
		}else {
			$this->response(403,Yii::t('friends','you can only get your say hello list'));
		}
	}
}