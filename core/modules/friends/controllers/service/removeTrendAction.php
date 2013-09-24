<?php
/**
 * @name removeTrendAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class removeTrendAction extends CmsAction{
	public function run($resourceId){
		$trendId = $this->getPost('trendId');
		$trend = UserTrends::model()->with('pics')->findByPk($trendId);
		if ( $trend === null ){
			$this->response(202);
		}
		
		if ( $trend->user_id !== $this->app->user->id ){
			$this->response(402);
		}
		$manager = $this->app->getComponent('trendsManager');
		$result = $manager->delete($trend);
		if ( $result === true ){
			$this->response(200);
		}else {
			$this->response(201,$result);
		}
	}
}