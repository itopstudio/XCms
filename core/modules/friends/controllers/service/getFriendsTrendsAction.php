<?php
/**
 * @name getFriendsTrendsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getFriendsTrendsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}

		$module = $this->getController()->getModule();
		$manager = $this->app->getComponent('trendsManager');
		$userManager = $this->app->getComponent($module->userManagerId);
		
		$data = $manager->findFriendsTrends($userManager,$loginedId);
		$this->response(300,'',$data);
	}
}