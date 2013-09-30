<?php
/**
 * @name getSearchGroupsListAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-30
 * Encoding UTF-8
 */
class getSearchGroupsListAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$name = $this->getQuery('name',null);
		if ( $name === null ){
			$this->response(300,'',array());
		}
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$list = $groupManager->findBySearchName($name);
		
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