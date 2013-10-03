<?php
/**
 * @name getJoinedGroupsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-1
 * Encoding UTF-8
 */
class getJoinedGroupsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$type = $this->getQuery('type',null);
		if ( $type === null ){
			$this->response(201);
		}
		$t = $type == 1 ? 0 : 100;
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$list = $groupManager->getGroups($loginedId,0);
		$response = array();
		foreach ( $list as $l ){
			$group = $l->group;
			if ( $group->type == $t ){
				$response[] = array(
						'id' => $group->getPrimaryKey(),
						'name' => $group->group_name,
						'description' => $group->description,
						'userNum' => $group->user_num
				);
			}
		}
		$this->response(300,'',$response);
	}
}