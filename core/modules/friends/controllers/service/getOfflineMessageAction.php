<?php
/**
 * @name getOfflineMessageAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-26
 * Encoding UTF-8
 */
class getOfflineMessageAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$timestamp = $this->getQuery('start');
		$chatManager = Yii::app()->getComponent('chatManager');
		$chatManager->pushOfflineMessage($loginedId,$timestamp);
	}
}