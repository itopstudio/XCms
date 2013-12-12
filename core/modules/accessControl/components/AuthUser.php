<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-8-26
 * Encoding UTF-8 
 */
class AuthUser extends CWebUser{
	private $_access = array();
	public $accessCacheTimeout = 3600;
	
	/**
	 * check operation access
	 * @param array $operation. contains 'module','controller','action'
	 * @param array $params use to absorb params delivered in {@link CAccessControlFilter}
	 * @param boolean $allowCaching
	 * @return boolean
	 */
	public function checkAccess($operation,$params=array(),$allowCaching=true){
		if ( $allowCaching === true ){
			$operationKey = $this->generateOperationKey($operation);
			$cachedAccess = $this->getCachedAccess($operationKey);
			if ( $cachedAccess !== null ){
				return $cachedAccess;
			}
		}
		
		$access = Yii::app()->getAuthManager()->checkAccess($operation,$this->getId()) !== false;
		if ( $allowCaching === true ){
			$this->cacheAccess($operationKey,$access);
		}
		return $access;
	}
	
	/**
	 * @param array $operation
	 * @return string
	 */
	public function generateOperationKey(&$operation){
		return 'USER_'.$this->getId().'_AuthUser_Access_Cache_'.json_encode($operation);
	}
	
	/**
	 * @param string $key
	 * @return boolean
	 */
	public function getCachedAccess($key){
		$cache = Yii::app()->getCache();
		if ( $cache !== null ){
			return $cache->get($key);
		}else {
			return Yii::app()->session->itemAt($key);
		}
	}
	
	/**
	 * @param string $key
	 * @param boolean $data
	 */
	public function cacheAccess($key,$data){
		$cache = Yii::app()->getCache();
		if ( $cache !== null ){
			$cache->set($key,$data,$this->accessCacheTimeout);
		}else {
			Yii::app()->session->add($key,$data);
		}
	}
	
	protected function beforeLogin($id, $states, $fromCookie){
		if ( $fromCookie === true ){
			if ( User::model()->count("uuid='{$states['uuid']}'") != 1 ){
				return false;
			}
		}
		//restore from cookie as a guest without autoRenewCookie
		if ( $fromCookie === true && $this->autoRenewCookie === false ){
			$this->renewCookie();
		}
		return true;
	}
}