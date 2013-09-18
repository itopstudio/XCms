<?php
/**
 * @name getRandomListAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getRandomListAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(403,Yii::t('friends','can not get random list'));
		}
		$module = $this->getController()->getModule();
		$listSize = $this->getQuery('size',10);
		$userManager = $this->app->getComponent($module->userManagerId);
		$with = array(
				'with' => array(
					'baseUser' => array(
						'select' => 'id','nickname','icon',
						'with' => array(
							'trends' => array(
								'limit' => 1,
								'offset' => 0,
								'order' => 'publish_time DESC'
							),
						),
					),
				)
		);
		$users = $userManager->getUserRandom($listSize,$with);
		
		$data = array();
		$attributeNames = array('id','nickname','icon');
		foreach ( $users as $user ){
			$attributes = $user->getAttributes($attributeNames);
			
			$trends = $user->getRelated('baseUser')->getRelated('trends');
			if ( !empty($trends) ){
				$attributes['trend'] = $trends[0]->getAttribute('content');
			}else {
				$attributes['trend'] = array();
			}
			
			$data[] = $attributes;
		}
		
		$this->response(300,'',$data);
	}
}