<?php
/**
 * @name chatAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-24
 * Encoding UTF-8
 */
class chatAction extends CmsAction{
	public function run($resourceId){
		$loginedUid = $this->app->getUser()->getId();
		
		if ( $resourceId !== $loginedUid ){
			$this->response(402,Yii::t('friends','you can only chat with your friends'));
		}
		
		$chatManager = Yii::app()->getComponent('chatManager');
		$pusher = $chatManager->getPusher();
		$with = $this->getPost('with',null);
		$type = $this->getPost('type',null);
		if ( $with !== null && $type !== null ){
			$bind = $chatManager->resolveBindName($type,$with);
			if ( $bind === false ){
				$this->response(202,Yii::t('friends','can not resolve bind name'));
			}
			$return = $pusher->pushMessageWithTags(2,'room1','api push2','title');
			var_dump($return);
		}else {
			$this->response(201,Yii::t('friends','chat peer and chat type can not be empty'));
		}
	}
}