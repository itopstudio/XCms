<?php
/**
 * @name CmsApplication.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-8
 * Encoding UTF-8
 */
class CmsApplication extends CWebApplication implements ArrayAccess{
	public $preloadModels = array();
	
	protected function init(){
		parent::init();
		$this->preloadModels();
	}
	
	protected function preloadModels(){
		foreach ( $this->preloadModels as $module => $models ){
			if ( is_string($module) ){//use 'FooModule' => 'BarModel'
				if ( is_array($models) ){
					foreach ( $models as $model ){
						CmsModule::loadModels($module,$model);
					}
				}else {
					CmsModule::loadModels($module,$models);
				}
			}else {
				CmsModule::loadModels($models);
			}
		}
	}
	
	public function offsetExists($offset) {
		return $this->params->__isset($offset);
	}

	public function offsetGet($offset) {
		return $this->params->__get($offset);
	}

	public function offsetSet($offset, $value) {
		$this->params->__set($offset,$value);
	}

	public function offsetUnset($offset) {
		$this->params->__unset($offset);
	}

}