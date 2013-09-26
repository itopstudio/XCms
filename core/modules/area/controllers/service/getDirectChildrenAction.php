<?php
/**
 * @name getDirectChildrenAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-27
 * Encoding UTF-8
 */
class getDirectChildrenAction extends CmsAction{
	public function run($resourceId){
		$data = Area::model()->findChildrenByParent($resourceId);
		if ( empty($data) ){
			$this->response(300,'',array());
		}
		
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