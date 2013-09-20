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
				$this->response(200,Yii::t('friends','say hello success'));
			}else {
				$this->response(201,'',$result->getErrors());
			}
			
		}else {
			$this->response(403,Yii::t('friends','can not make friends with him or her'));
		}
	}
}