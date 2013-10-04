<?php
/**
 * @name getJoinedRooms.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-4
 * Encoding UTF-8
 */
class getJoinedRoomsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$groupManager = $this->getController()->getModule()->getGroupManager();
		$list = $groupManager->getRooms($loginedId);
		$response = array();
		foreach ( $list as $l ){
			$group = $l->room;
			$response[] = array(
					'id' => $group->getPrimaryKey(),
					'name' => $group->room_name,
					'description' => $group->description,
					'userNum' => $group->user_num
			);
		}
		$this->response(300,'',$response);
	}
}