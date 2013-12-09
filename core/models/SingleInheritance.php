<?php
/**
 * @name SingleInheritance.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-11-21 
 * Encoding UTF-8
 */
abstract class SingleInheritance extends CmsActiveRecord{
	/**
	 * @var string parent relation defined in {@link CActiveRecord::relations()}
	 * null if this model does not have a parent
	 */
	protected $_parentRelation = null;
	/**
	 * 
	 * @var CActiveRecord
	 */
	protected $_parentNewRecord = null;
	/**
	 * 
	 * @var array
	 */
	protected  static $_factoryMap = array();
	
	/**
	 * 
	 * @return array
	 */
	public function __sleep(){
		$keys = parent::__sleep();
		$parent = $this->parentFactory(__FUNCTION__);
		if ( $parent !== null ){
			$keys = array_merge($keys,$parent->__sleep());
		}
		return $keys;
	}
	
	/**
	 * 
	 * @see CActiveRecord::__get()
	 */
	public function __get($name){
		try {
			$result = parent::__get($name);
		}catch ( CException $e ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$result = $parent->__get($name);
			}else {
				throw $e;
			}
		}
		
		return $result;
	}
	
	/**
	 * 
	 *  @see CActiveRecord::__set()
	 */
	public function __set($name, $value){
		$hasSetInParent = false;
		$parent = $this->parentFactory(__FUNCTION__);
		if ( $parent !== null ){
			try {
				$parent->__set($name,$value);
				$hasSetInParent = true;
			}catch ( CException $e ){
			}
		}
		
		try {
			parent::__set($name,$value);
		}catch ( CException $e ){
			if ( $hasSetInParent === false ){
				throw $e;
			}
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::__isset()
	 */
	public function __isset($name){
		if ( parent::__isset($name) === false ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				return $parent->__isset($name);
			}
			return false;
		}else {
			return true;
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::__unset()
	 */
	public function __unset($name){
		try {
			parent::__unset($name);
		}catch ( CException $e ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$parent->__unset($name);
			}else {
				throw $e;
			}
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::__call()
	 */
	public function __call($name, $parameters){
		try {
			return parent::__call($name, $parameters);
		}catch ( CException $se ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				if ( method_exists($parent,$name) ){
					return call_user_func_array(array($parent,$name),$parameters);
				}else {
					try {
						return $parent->__call($name,$parameters);
					}catch ( CException $pe ){
					}
				}
			}
			
			throw $se;
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::getRelated()
	 */
	public function getRelated($name,$refresh=false,$params=array()){
		$related = parent::getRelated($name,$refresh,$params);
		if ( $related === null ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$related = $parent->getRelated($name,$refresh,$params);
			}
		}
		
		return $related;
	}
	
	/**
	 * 
	 * @see CActiveRecord::hasRelated()
	 */
	public function hasRelated($name){
		if ( parent::hasRelated($name) === false ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				return $parent->hasRelated($name);
			}else {
				return false;
			}
		}else {
			return true;
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::attributeNames()
	 */
	public function attributeNames(){
		$selfAttributeNames = parent::attributeNames();
		$parentAttributeNames = array();
		$parent = $this->parentFactory(__FUNCTION__);
		
		if ( $parent !== null ){
			$parentAttributeNames = $parent->attributeNames();
		}
		
		return array_merge($parentAttributeNames,$selfAttributeNames);
	}
	
	/**
	 * 
	 * @see CActiveRecord::getActiveRelation()
	 */
	public function getActiveRelation($name){
		$activeRelation = parent::getActiveRelation($name);
		if ( $activeRelation === null ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				return $parent->getActiveRelation($name);
			}else {
				return null;
			}
		}else {
			return $activeRelation;
		}
	}
	
	/**
	 * 
	 * @see CmsActiveRecord::hasAttribute()
	 */
	public function hasAttribute($name,$checkProperty=true){
		$has = parent::hasAttribute($name,$checkProperty);
		if ( $has === false ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				return $parent->hasAttribute($name,$checkProperty);
			}else {
				return false;
			}
		}else {
			return true;
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::getAttribute()
	 */
	public function getAttribute($name){
		$attribute = parent::getAttribute($name);
		if ( $attribute === null ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$attribute = $parent->getAttribute($name);
			}
		}
		
		return $attribute;
	}
	
	/**
	 * 
	 * @see CActiveRecord::setAttribute()
	 */
	public function setAttribute($name, $value){
		$set = parent::setAttribute($name,$value);
		if ( $set === false ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$set = $parent->setAttribute($name,$value);
			}
		}
		
		return $set;
	}
	
	/**
	 * 
	 * @see CActiveRecord::getAttributes()
	 */
	public function getAttributes($names=true){
		$selfAttributes = parent::getAttributes($names);
		
		$parent = $this->parentFactory(__FUNCTION__);
		if ( $parent !== null ){
			foreach ( $parent->getAttributes($names) as $name => $value ){
				if ( !isset($selfAttributes[$name]) ){
					$selfAttributes[$name] = $value;
				}
			}
		}
		
		return $selfAttributes;
	}
	
	/**
	 * @see CModel::setAttributes()
	 */
	public function setAttributes($values,$safeOnly=true){
		parent::setAttributes($values,$safeOnly);
		$parent = $this->parentFactory(__FUNCTION__);
		if ( $parent !== null ){
			$parent->setAttributes($values,$safeOnly);
		}
	}
	
	/**
	 * 
	 * @see CModel::validate()
	 */
	public function validate($attributes=null,$clearErrors=true){
		if ( $this->getIsNewRecord() === false && $this->hasParentRelated() === false ){//edit sub class only
			return parent::validate($attributes,$clearErrors);
		}
		
		$parent = $this->parentFactory(__FUNCTION__);
		
		if ( $parent !== null ){
			return $parent->validate($attributes,$clearErrors) && parent::validate($attributes,$clearErrors);
		}else {
			return true;
		}
	}
	
	/**
	 * 
	 * @see CModel::hasErrors()
	 */
	public function hasErrors($attribute=null){
		if ( parent::hasErrors($attribute) === false ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				return $parent->hasErrors($attribute);
			}
			return false;
		}else {
			return true;
		}
	}
	
	/**
	 * 
	 * @see CModel::getErrors()
	 */
	public function getErrors($attribute=null){
		$errors = parent::getErrors($attribute);
		$parent = $this->parentFactory(__FUNCTION__);
		if ( $parent !== null ){
			$errors = array_merge($parent->getErrors($attribute),$errors);
		}
		return $errors;
	}
	
	/**
	 * @see CModel::getError()
	 */
	public function getError($attribute){
		$error = parent::getError($attribute);
		if ( $error === null ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$error = $parent->getError($attribute);
			}
		}
		return $error;
	}
	
	/**
	 * 
	 * @see CActiveRecord::insert()
	 */
	public function insert($attributes=null){
		$insert = false;
		try {
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null && $parent->insert($attributes) ){
				$insertId = $this->getDbConnection()->getLastInsertID();
				$foreignKey = $this->getMetaData()->relations[$this->_parentRelation]->foreignKey;
				parent::setAttribute($foreignKey,$insertId);
			}
			$insert = parent::insert($attributes);
		}catch ( CException $e ){
			throw $e;
		}
		return $insert;
	}
	
	/**
	 * 
	 * @see CActiveRecord::update()
	 */
	public function update($attributes=null){
		$update = false;
		try {
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$parent->update($attributes);
			}
			$update = parent::update($attributes);
		}catch ( CException $e ){
			throw $e;
		}
		return $update;
	}
	
	/**
	 * 
	 * @see CActiveRecord::saveAttributes()
	 */
	public function saveAttributes($attributes){
		$result = false;
		try {
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent !== null ){
				$parent->saveAttributes($attributes);
			}
			$result = parent::saveAttributes($attributes);
		}catch ( CException $e ){
			throw $e;
		}
		return $reuslt;
	}
	
	/**
	 * 
	 * @see CActiveRecord::delete()
	 */
	public function delete($deleteParent=true){
		if ( $deleteParent === true ){
			$parent = $this->parentFactory(__FUNCTION__);
			if ( $parent === null ){
				throw new CException(YIi::t('models/singleInheritance','record can not be deleted.can not find base record'));
			}else {
				return $parent->delete();
			}
		}else {
			return parent::delete();
		}
	}
	
	/**
	 * 
	 * @see CActiveRecord::refresh()
	 */
	public function refresh(){
		$parent = $this->parentFactory(__FUNCTION__);
		if ( $parent !== null ){
			if ( $parent->refresh() ){
				return parent::refresh();
			}
		}
		return false;
	}
	
	/**
	 * 
	 * @param string $from
	 * @param string $refresh
	 * @param array $params
	 * @return CActiveRecord
	 */
	protected function parentFactory($from,$refresh=false,$params=array()){
		$parent = null;
		
		if ( $this->_parentRelation !== null && $this->getIsNewRecord() === true ){
			if ( $this->_parentNewRecord === null ){
				$relationClass = $this->getMetaData()->relations[$this->_parentRelation]->className;
				$this->_parentNewRecord = new $relationClass;
			}
			$parent = $this->_parentNewRecord;
		}elseif ( $this->_parentRelation !== null ){
			$parent = parent::getRelated($this->_parentRelation,$refresh,$params);
		}
		
		return $parent;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	protected function hasParentRelated(){
		if ( $this->_parentRelation !== null ){
			return parent::hasRelated($this->_parentRelation);
		}else {
			return false;
		}
	}
}