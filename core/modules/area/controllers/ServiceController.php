<?php
/**
 * @name ServiceController.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-26
 * Encoding UTF-8
 */
class ServiceController extends CmsController{
	public $actionClassPathAlias = 'area.controllers';
	
	public function filters(){
		return array();
	}
	
	public function getActionClass(){
		return array(
				'getLevel',
				'getDirectChildren',
				'getAllChildren',
		);
	}
}