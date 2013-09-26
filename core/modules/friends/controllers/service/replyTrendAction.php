<?php
/**
 * @name replyTrendAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class replyTrendAction extends CmsAction{
	public function run($resourceId){
		if ( $resourceId !== $this->app->getUser()->getId() ){
			$this->response(402);
		}
		
		$trendId = $this->getPost('trendId');
		$trend = UserTrends::model()->findByPk($trendId);
		if ( $trend === null ){
			$this->response(202);
		}
		
		$content = $this->getPost('content');
		
		$manager = $this->app->getComponent('trendsManager');
		$result = $manager->reply($resourceId,$trend,$content);
		if ( $result === true ){
			$this->response(200);
		}else {
			$this->response(201,$result);
		}
	}
}