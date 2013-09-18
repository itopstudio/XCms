<?php
/**
 * @name publishTrendAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class publishTrendAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		$data = array('content'=>$this->getPost('content'));
		$data['user_id'] = $loginedId;

		$manager = $this->app->getComponent('trendsManager');
		$result = $manager->publish($data);
		if ( $result === true ){
			$this->response(200);
		}else {
			$this->response(201,$result);
		}
	}
}