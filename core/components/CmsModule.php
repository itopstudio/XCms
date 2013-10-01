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
		static $modulesConfig = null;
		static $class = null;
		static $enabled = null;
		
		if ( $modulesConfig === null ){
			$modulesConfig = Yii::app()->getModules();
		}
		
		if ( $modulesConfig !== null && isset($modulesConfig[$moduleId]['class']) ){
			$config = $modulesConfig[$moduleId];
			if ( $class !== $config['class'] ){
				if ( isset($config['enabled']) ){
					$enabled = $config['enabled'];
				}else {
					$enabled = null;
				}
				$class = $config['class'];
				Yii::import($class,true);
			}
			
			if ( ($pos=strrpos($class,'.')) !== false ){
				$module = substr($class,$pos+1);
			}else {
				$module = $class;
			}
			
			$config['enabled'] = false;
			Yii::app()->setModules( array($moduleId=>$config) );
			$module::loadSelfModels();
			if ( $enabled === null ){
				$config['enabled'] = true;
				Yii::app()->setModules( array($moduleId=>$config) );
			}else {
				$config['enabled'] = $enabled;
				Yii::app()->setModules( array($moduleId=>$config) );
			}
		}else {
			return false;
		}
	}
	
	/**
	 * 
	 * @throws Exception
	 */
	public static function loadSelfModels(){
		throw new Exception(Yii::t('cmsModule','loadSelfModels must be overwrite'));
	}
}