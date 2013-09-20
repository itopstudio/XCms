<?php
/**
 * @name breakUpAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class breakUpAction extends CmsAction{
	public function run($resourceId){
		$loginedUid = $this->app->getUser()->getId();
		
		if ( $resourceId === $loginedUid ){
			$with = $this->getDelete('with');
			if ( $with === $loginedUid ){
				$this->response(202,Yii::t('friends','can not remove friend'));
			}
			$module = $this->getController()->getModule();
			$userManager = $this->app->getComponent($module->userManagerId);
			
			$userManager->breakUp($loginedUid, $with);
			$this->response(200);
		}else {
			$this->response(402,Yii::t('friends','you can only remove your friends'));
		}
	}
}