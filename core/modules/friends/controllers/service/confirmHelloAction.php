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
		$chatManager = $this->app->getComponent('chatManager');
		$chatManager->getPusher()->setTimeToLive(864000);
		
		$result = '';
		if ( $type == 1 ){
			$result = $userManager->makeFriends($loginedId,$helloId);
			$message = '你有了新的朋友，请到好友中查看';
		}elseif ( $type == 2 ){
			$result = $userManager->denyHello($loginedId,$helloId);
		}
		
		if ( !is_string($result) && $result->hasErrors() ){
			if ( $type == 1 ){
				$alias = 'user'.$result->followed;
				
				$extras[] = time();
				$extras['ios'] = array(
						'badge' => 1,
						'sound' => 'happy'
				);
				$chatManager->pushNotification(1,$alias,1,$message,'社区宝聊天',$extras);
			}
			$this->response(200);
		}else {
			$this->response(201,$result->getErrors());
		}
	}
}