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
		$with = $this->getPost('with',null);
		if ( $with === $loginedUid ){
			$this->response(400,Yii::t('friends','can not chat with yourself'));
		}
		
		$chatManager = Yii::app()->getComponent('chatManager');
		$pusher = $chatManager->getPusher();
		$type = $this->getPost('type',null);
		$content = $this->getPost('content',null);
		
		if ( $with !== null && $type !== null && $content !== null ){
			$attributes = array(
					'sender' => $loginedUid,
					'content' => $content,
					'send_time' => time(),
					'status' => 0
			);
			$bindInfo = $chatManager->resolveBindInfo($type,$with,$attributes);
			if ( $bindInfo === false ){
				$this->response(202,Yii::t('friends','can not resolve bind name'));
			}
			$model = $bindInfo['model'];
			$sendTo = $bindInfo['bindName'];
			
			$model->attributes = $attributes;
			if ( $model->save() ){
				$sendno = $model->getPrimaryKey();
				$content = $attributes['content'];
				$extras = array($type,$loginedUid,$model->send_time);
				
				$result = $chatManager->pushMessage($type,$sendTo,$sendno,$content,'社区宝新消息',$extras);
				
				if ( $result->hasError === false ){
					$this->response(200);
				}else {
					$model->delete();
					$this->response($result->errorCode,$result->errorMsg);
				}
			}else {
				$this->response(201,'',$model->getErrors());
			}
		}else {
			$this->response(201,Yii::t('friends','chat peer and chat type can not be empty'));
		}
	}
}