<?php
/**
 * @name ServiceController.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-2
 * Encoding UTF-8
 */
class ServiceController extends CmsController{
	public $actionClassPathAlias = 'access.controllers';
	
	public function filters(){
		return array();
	}
	
	public function getActionClass(){
		return array(
				'getOperationChildren'
		);
	}
}