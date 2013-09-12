<?php
/**
 * @name ConfigBase.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-6
 * Encoding UTF-8
 * 
 * all config's base class
 */
class ConfigBase{
	/**
	 * @var boolean
	 */
	public $debug;
	/**
	 * @var int
	 */
	public $traceLevel;
	/**
	 * @var Environment
	 */
	protected $_owner;
	/**
	 * @var array
	 */
	private $_config = array();
	
	/**
	 * @param Environment $owner
	 */
	public function init($owner){
		$this->debug = false;
		$this->traceLevel = 0;
		$this->_owner = $owner;
	}
	
	public function getConfig(){
		if ( empty($this->_config) ){
			$this->_config = array_merge_recursive($this->base(),$this->merge());
		}
		$basePath = $this->_owner->basePath;
		if ( $basePath !== '' ){
			$this->_config['basePath'] = $basePath;
		}
		return $this->_config;
	}
	
	public function base(){
		return array(
			'basePath' => defined('BASE_PATH') ? BASE_PATH : dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..',
			'id' => 'Powered By XCms',
			'name' => 'XCms Application' ,
			'sourceLanguage' => 'en_us',
			'language' => 'zh_cn',
			
			'defaultController' => 'site',
			// Preloading 'log' component
			'preload' => array('log'),
			
			'import' => array(
				//import from application directory
				'application.models.*',
				'application.controllers.*',
				'application.components.*',
				'application.extensions.*',
		
				//import from cms
				'cms.components.*',
				'cms.components.behaviors.*',
				'cms.components.filters.*',
				'cms.components.pagers.*',
				'cms.components.exceptions.*',
				'cms.extensions.*',
				'cms.models.*',
				'cms.widgets.*',
				'cms.globals.*',
			),
				
			'modules' => array(
				'accessControl' => array(
					'class' => 'cms.modules.accessControl.AccessControlModule'
				),
				'ad' => array(
					'class' => 'cms.modules.ad.AdModule'
				),
			),
				
			'components'=>array(
				'user'=>array(
					'class' => 'cms.modules.accessControl.components.AuthUser'
				),
		
				'authManager' => array(
					'class' => 'cms.modules.accessControl.components.AuthManager'
				),
					
				'securityManager' => array(
					'class' => 'cms.components.CmsSecurity',
				),
		
//				'errorHandler'=>array(
// 					'errorAction'=>'error/index',
// 				),
			),
			
			
		);
	}
	
	public function merge(){
		return array();
	}
}