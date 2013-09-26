<?php
/**
 * @name getLevelAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-27
 * Encoding UTF-8
 */
class getLevelAction extends CmsAction{
	public function run($resourceId){
		if ( $resourceId <= 0 ){
			$this->response(300,'',array());
		}
		$data = Area::model()->findChildrenByLevel($resourceId);
		$response = array();
		
		foreach ( $data as $d ){
			$response[] = array(
					'id' => $d->getPrimaryKey(),
					'areaName' => $d->getAttribute('area_name')
			);
		}
		
		$this->response(300,'',$response);
	}
}