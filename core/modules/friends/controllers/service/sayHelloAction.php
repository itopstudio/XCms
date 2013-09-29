<?php
/**
 * @name sayHelloAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class sayHelloAction extends CmsAction{
	/**
	 * 
	 * @param int $resourceId logined user id
	 */
	public function run($resourceId){
		$loginedUid = $this->app->getUser()->getId();
		
		if ( $resourceId === $loginedUid ){
			$to = $this->getPost('to');
			if ( $to === $loginedUid ){
				$this->response(202,Yii::t('friends','can not make friends with yourself'));
			}
			$module = $this->getController()->getModule();
			$userManager = $this->app->getComponent($module->userManagerId);
			
			$result = $userManager->sayHello($loginedUid,$to);
			if ( $result === true || $result->hasErrors() === false ){
				$chatManager = $this->app->getComponent('chatManager');
				$chatManager->getPusher()->setTimeToLive(864000);
				$alias = 'user'.$to;
				$chatManager->pushNotification(1,$alias,1,'收到一条打招呼信息','社区宝聊天',array('time'=>time()));
				$this->response(200,Yii::t('friends','say hello success'));
			}else {
				$this->response(201,'',$result->getErrors());
			}
			
		}else {
			$this->response(403,Yii::t('friends','can not make friends with him or her'));
		}
	}
}