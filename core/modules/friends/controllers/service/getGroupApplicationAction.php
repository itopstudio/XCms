<?php
/**
 * @name getGroupApplicationAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-29
 * Encoding UTF-8
 */
class getGroupApplicationAction extends CmsAction{
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
		$group = $groupManager->findByPk($groupId);
		if ( $group === null || $group->master_id !== $loginedId ){
			$this->response(403,'只能查看自己创建的群信息',array());
		}
		$result = $groupManager->getGroupMembers($groupId,50);
		
		$response = array();
		foreach ( $result as $r ){
			$member = $r->getRelated('member');
			$response[] = array(
					'id' => $member->getPrimaryKey(),
					'nickname' => $member->getAttribute('nickname'),
					'icon' => $member->getRelated('frontUser')->getAttribute('icon')
			);
		}
		
		$this->response(300,'',$response);
	}
}