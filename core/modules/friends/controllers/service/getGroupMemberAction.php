<?php
/**
 * @name getGroupMemberAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-1
 * Encoding UTF-8
 */
class getGroupMemberAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$groupId = $this->getQuery('group',null);
		if ( $groupId === null ){
			$this->response(201);
		}
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$list = $groupManager->getGroupMembers($groupId);
		
		$response = array();
		foreach ( $list as $l ){
			$user = $l->getRelated('member');
			$response[] = array(
					'id' => $user->getPrimaryKey(),
					'nickname' => $user->nickname,
					'icon' => $user->getRelated('frontUser')->icon
			);
		}
		
		$this->response(300,'',$response);
	}
}