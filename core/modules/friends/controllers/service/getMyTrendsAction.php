<?php
/**
 * @name getMyTrendsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getMyTrendsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		$manager = $this->app->getComponent('trendsManager');
		$result = $manager->findMyTrends($loginedId);
		$this->response(300,'',$result);
	}
}