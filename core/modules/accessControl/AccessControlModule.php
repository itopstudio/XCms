<?php
/**
 * @name AccessControlModule.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-12
 * Encoding UTF-8
 */
class AccessControlModule extends CmsModule{
	protected function init(){
		parent::init();
		Yii::import('cms.modules.accessControl.controllers.*');
	}
	
	public static function loadSelfModels(){
		Yii::import('cms.modules.accessControl.models.*');
	}
}