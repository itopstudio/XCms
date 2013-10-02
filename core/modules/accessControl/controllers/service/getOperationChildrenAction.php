<?php
/**
 * @name getOperationChildrenAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-2
 * Encoding UTF-8
 */
class getOperationChildrenAction extends CmsAction{
	public function run($resourceId){
		$data = AuthOperation::model()->findChildrenByParent($resourceId);
		if ( $data === null ){
			$this->response(201);
		}
		
		$response = array();
		foreach ( $data as $d ){
			$response[] = array(
					'id' => $d->getPrimaryKey(),
					'name' => $d->getAttribute('operation_name')
			);
		}
		
		$this->response(300,'',$response);
	}
}