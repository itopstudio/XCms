<?php
/**
 * @name BaseUserManager.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-18
 * Encoding UTF-8
 */
abstract class BaseUserManager extends CApplicationComponent{
	/**
	 * find all user with it's parent.
	 * 
	 * @param CDbCriteria $criteria
	 * @param array $params
	 */
	abstract public function findAll($criteria=null,$params=array());
	/**
	 * 
	 * @param CDbCriteria $criteria
	 * @param array $params
	 */
	abstract public function count($criteria=null,$params=array());
	/**
	 * 
	 * @param int $listSize
	 * @param CDbCriteria $criteria
	 */
	public function getUserRandom($listSize,$criteria=null){
		$count = $this->count();
		
		if ( $criteria === null ){
			$criteria = new CDbCriteria();
		}elseif ( is_array($criteria) ){
			$criteria = new CDbCriteria($criteria);
		}
		$criteria->limit = $listSize;
		if ( $listSize >= $count ){
			$criteria->offset = 0;
		}else {
			$criteria->offset = mt_rand(0,$count-$listSize);
		}
		
		return $this->findAll($criteria);
	}
}