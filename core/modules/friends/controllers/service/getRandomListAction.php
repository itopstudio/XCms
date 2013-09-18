<?php
/**
 * @name getRandomListAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getRandomListAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(400,Yii::t('friends','can not get random list'));
		}
		$module = $this->getController()->getModule();
		$listSize = $this->getQuery('size',10);
		$userManager = $this->app->getComponent($module->userManagerId);
		$users = $userManager->getUserRandom($listSize);
		
		$data = array();
		$attributeNames = array('id','nickname');
		foreach ( $users as $user ){
			$data[] = $user->getAttributes($attributeNames);
		}
		
		$this->response(200,'',$data);
	}
}