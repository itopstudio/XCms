<?php
/**
 * @name CmsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-8
 * Encoding UTF-8
 */
class CmsAction extends CAction{
	/**
	 * @var CmsApplication
	 */
	public $app;
	/**
	 * @var CHttpRequest
	 */
	public $request;
	
	public function __construct($controller, $id){
		parent::__construct($controller, $id);
		$this->app = Yii::app();
		$this->request = Yii::app()->getRequest();
	}
	/**
	 * 
	 * @param number $code
	 * @param string $message
	 * @param string $data
	 * @param string $format
	 * @param string $contentType
	 */
	public function response($code=200,$message='',$data=null,$format='json',$contentType='text/html'){
		$this->getController()->response($code,$message,$data,$format,$contentType);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPost($name=null,$defaultValue=null){
		return $this->getController()->getPost($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getQuery($name=null,$defaultValue=null){
		return $this->getController()->getQuery($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPut($name,$defaultValue=null){
		return $this->getController()->getPut($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getDelete($name,$defaultValue=null){
		return $this->getController()->getDelete($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getRestParam($name=null,$defaultValue=null){
		return $this->getController()->getRestParam($name,$defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getRequestParam($name,$defaultValue=null){
		return $this->getController()->getRequestParam($name,$defaultValue);
	}
	
	public function render($view,$data=null,$return=false){
		return $this->getController()->render($view,$data,$return);
	}
	
	public function setPageTitle($title){
		$this->getController()->setPageTitle($title);
	}
	
	public function redirect($url,$terminate=true,$statusCode=302){
		$this->getController()->redirect($url,$terminate,$statusCode);
	}
	
	public function createUrl($route,$params=array(),$ampersand='&'){
		return $this->getController()->createUrl($route,$params,$ampersand);
	}
	
	public function createAbsoluteUrl($route,$params=array(),$schema='',$ampersand='&'){
		return $this->getController()->createAbsoluteUrl($route,$params,$schema,$ampersand);
	}
}