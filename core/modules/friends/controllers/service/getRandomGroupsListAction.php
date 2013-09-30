<?php
/**
 * @name getRandomGroupsListAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-30
 * Encoding UTF-8
 */
class getRandomGroupsListAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$listSize = $this->getQuery('size',10);
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$list = $groupManager->findRandomList($listSize);
		
		$response = array();
		foreach ( $list as $l ){
			$response[] = array(
					'id' => $l->getPrimaryKey(),
					'name' => $l->group_name,
					'description' => $l->description,
					'userNum' => $l->user_num
			);
		}
		$this->response(300,'',$response);
	}
}