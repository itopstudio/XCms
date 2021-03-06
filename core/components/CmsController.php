<?php
/**
 * @name CmsController.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-8
 * Encoding UTF-8
 */
class CmsController extends CController{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/main';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	/**
	 * @var CmsApplication
	 */
	public $app;
	/**
	 * @var CHttpRequest
	 */
	public $request;
	/**
	 * when a action is defined as a CAction
	 * then that action's name is his name and this subfix
	 * @var string $actionSubfix used in @method self::actions()
	 */
	public $actionClassSubfix = 'Action';
	/**
	 * @var string $actionClassFolder used in @method self::actions()
	 */
	public $actionClassPathAlias = 'application.controllers';
	
	/**
	 * @see CController::init()
	 */
	public function init(){
		$this->app = Yii::app();
		$this->request = $this->app->getRequest();
	}
	
	/**
	 * default to use hasLogined filter
	 * @see CController::filters()
	 */
	public function filters(){
		return array(
				'hasLogined' => array('cms.components.filters.HasLogined'),
				'accessControl' => 'accessControl'
		);
	}
	
	public function filterAccessControl($filterChain)
	{
		Yii::import('cms.modules.accessControl.components.AccessControlFilter',true);
		$filter=new AccessControlFilter;
		$filter->setRules($this->accessRules());
		$filter->filter($filterChain);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPost($name=null,$defaultValue=null){
		if ( $name !== null ){
			return $this->request->getPost($name,$defaultValue);
		}else {
			return $_POST;
		}
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getQuery($name=null,$defaultValue=null){
		if ( $name !== null ){
			return  $this->request->getQuery($name,$defaultValue);
		}else {
			return $_GET;
		}
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPut($name,$defaultValue=null){
		return $this->request->getPut($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getDelete($name,$defaultValue=null){
		return $this->request->getDelete($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getRestParam($name=null,$defaultValue=null){
		$result = $this->request->getRestParams();
		if ( $name !== null ){
			return isset($result[$name]) ? $result[$name] : $defaultValue;
		}
		return $result;
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getRequestParam($name,$defaultValue=null){
		return $this->request->getParam($name,$defaultValue);
	}
	
	public function response($code=200,$message='',$data=null,$format='json',$contentType='text/html'){
		header('Content-type: '.$contentType);
		$response = array(
				'status' => $code,
				'message' => $message,
				'data' => $data
		);
		if ( $format === 'json' ){
			echo json_encode($response);
		}
		$this->app->end();
	}
	
	/**
	 * @return array
	 */
	public function actions(){
		$actions = $this->getActionClass();
		$folderAlias = "{$this->actionClassPathAlias}.{$this->id}";

		$returnActions = array();
		foreach( $actions as $name => $option ){
			if ( is_array($option) ){
				$option['class'] = "{$folderAlias}.{$option['class']}{$this->actionClassSubfix}";
				$returnActions[$name] = $option;
			}else{
				$returnActions[$option] = "{$folderAlias}.{$option}{$this->actionClassSubfix}";
			}
		}
		
		return $returnActions;
	}
	
	/**
	 * @return array
	 */
	public function getActionClass(){
		return array();
	}
	
	public function loginRequired(){
		
	}
	
	public function accessDenied(){
		
	}
	
	public function accessRules(){
		$module = $this->getModule();
		return array(
				array('allow',
					'roles' => array(
							array(
								'module' => $module === null ? null : $module->getId(),
								'controller' => $this->getId(),
								'action' => $this->getAction()->getId()),
					),
					'deniedCallback' => array($this,'accessDenied')
				),
		);
	}
}