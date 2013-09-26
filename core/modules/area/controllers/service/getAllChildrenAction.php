<?php
/**
 * @name getAllChildrenAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-27
 * Encoding UTF-8
 */
class getAllChildrenAction extends CmsAction{
	public function run($resourceId){
		$tree = Area::model()->findChildrenInPreorder($resourceId);
		if ( empty($tree) ){
			$this->response(300,'',array());
		}
		$response = array();
		foreach ( $tree as $node => $t ){
			$response[$node] = array(
					'parent' => $t['parent'],
					'data' => array(
							'id' => $t['record']->getPrimaryKey(),
							'areaName' => $t['record']->getAttribute('area_name')
					)
			);
		}
		
		$this->response(300,'',$response);
	}
}