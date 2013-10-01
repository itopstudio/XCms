<?php
/**
 * @name AreaModule.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-26
 * Encoding UTF-8
 */
class AreaModule extends CmsModule{
	public function init(){
		Yii::import('cms.modules.area.models.*');
	}
	
	public static function loadSelfModels(){
		Yii::import('cms.modules.area.models.*');
	}
}