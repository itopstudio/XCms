<?php
/**
 * @name getMyGroupsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-1
 * Encoding UTF-8
 */
class getMyGroupsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$list = $groupManager->findMasteredGroups($loginedId);
		
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