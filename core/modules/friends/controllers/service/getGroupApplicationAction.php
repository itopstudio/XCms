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
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$response = array();
		
		$result = $groupManager->getMasteredGroupsMembers($loginedId,50);
		
		foreach ( $result as $r ){
			$member = $r->getRelated('member');
			$response[] = array(
					'groupId' => $r->getRelated('group')->getPrimaryKey(),
					'id' => $member->getPrimaryKey(),
					'nickname' => $member->getAttribute('nickname'),
					'icon' => $member->getRelated('frontUser')->getAttribute('icon')
			);
		}
		
		$this->response(300,'',$response);
	}
}