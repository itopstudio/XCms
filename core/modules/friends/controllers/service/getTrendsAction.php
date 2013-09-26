<?php
/**
 * @name getTrendsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-20
 * Encoding UTF-8
 */
class getTrendsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$manager = $this->app->getComponent('trendsManager');
		$target = $this->getQuery('target',null);
		$pageSize = $this->getQuery('pageSize',40);
		
		$data = $manager->findUserTrends($loginedId,$pageSize);
		$this->response(300,'',$data);
	}
}