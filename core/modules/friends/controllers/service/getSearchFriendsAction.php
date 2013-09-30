<?php
/**
 * @name getSearchFriendsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-1
 * Encoding UTF-8
 */
class getSearchFriendsAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(402);
		}
		
		$criteria = new CDbCriteria();
		$criteria->with = array(
				'baseUser' => array(
						'alias' => 'base',
						'select' => '*',
						'with' => array(
								'trends' => array(
										'limit' => 1,
										'offset' => 0,
										'order' => 'publish_time DESC'
								)
						),
				)
		);
		
		$value = null;
		$query = $this->getQuery();
		foreach ( $query as $key => $q ){
			if ( empty($q) ){
				$value = null;
				break;
			}
			if ( $key === 'mobile' ){
				$criteria->condition = 'mobile=:v';
				$value = $q;
			}elseif ( $key === 'email' ){
				$criteria->condition = 'email=:v';
				$value = $q;
			}elseif ( $key === 'nickname' ){
				$criteria->condition = 'base.nickname LIKE :v';
				$value = '%'.strtr($q,array('%'=>'\%', '_'=>'\_', '\\'=>'\\\\')).'%';
			}
		}
		if ( $value === null ){
			$this->response(201,'缺少参数');
		}
		$criteria->params = array(
				':v' => $value
		);
		
		$module = $this->getController()->getModule();
		$userManager = $this->app->getComponent($module->userManagerId);
		$data = $userManager->findAll($criteria);
		
		$response = array();
		foreach ( $data as $d ){
			$base = $d->getRelated('baseUser');
			$trend = $base->trends;
			
			$response[] = array(
					'id' => $d->getPrimaryKey(),
					'nickname' => $base->nickname,
					'icon' => $d->icon,
					'trend' => empty($trend) ? '' : $trend[0]->content
			);
		}
		$this->response(300,'',$response);
	}
}