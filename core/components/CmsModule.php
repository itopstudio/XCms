<?php
/**
 * @name CmsModule.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-12
 * Encoding UTF-8
 */
class CmsModule extends CWebModule{
	protected function init(){
		Yii::app()->setComponent('messages', array('basePath'=>Yii::getPathOfAlias('cms.messages')));
	}
	
	/**
	 * load module level model,default to load all models
	 * 
	 * @param string $moduleId
	 * @param string $models
	 */
	public static function loadModels($moduleId,$models='*'){
		$module = Yii::app()->getModule($moduleId);
		if ( $module !== null ){
			$module->loadSelfModels();
		}
	}
	
	/**
	 * 
	 * @throws Exception
	 */
	public function loadSelfModels(){
		throw new Exception(Yii::t('cmsModule','loadSelfModels must be overwrite'));
	}
}