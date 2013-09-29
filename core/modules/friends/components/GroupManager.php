<?php
/**
 * @name GroupManager.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-29
 * Encoding UTF-8
 */
class GroupManager extends CApplicationComponent{
	public $maxCreation = 3;
	
	/**
	 * count common group
	 * 
	 * @param int $uid
	 * @param int $type
	 * @return int
	 */
	public function countGroupCreation($uid,$type=0){
		return Groups::model()->count('`master_id`=:uid AND `type`=:t',array(':uid'=>$uid,':t'=>$type));
	}
	
	public function count($criteria,$params=array()){
		return Groups::model()->count($criteria,$params);
	}
	
	public function findCreatedGroups($uid,$type=0){
		return Groups::model()->findAll('`master_id`=:uid AND `type`=:t',array(':uid'=>$uid,':t'=>$type));
	}
	
	/**
	 * 
	 * @param int $groupId
	 * @param int $uid
	 * @return boolean
	 */
	public function isGroupMaster($groupId,$uid){
		return Groups::model()->find('id=:gid AND master_id=:uid',array(':gid'=>$groupId,':uid'=>$uid)) !== null;
	}
	
	/**
	 * 
	 * @param int $uid
	 * @param string $name
	 * @param int $type
	 * @param string $description
	 * @param string $message
	 * @return boolean
	 */
	public function createGroup($uid,$name,$type=0,$description=null,$message=''){
		$model = new Groups();
		
		if ( $this->countGroupCreation($uid,$type) >= $this->maxCreation ){
			$msg = $message === '' ? Yii::t('friends','you can only create {num} group(s) in total',array(
					'{num}' => $this->maxCreation,
			)) : $message;
			$model->addError('master_id',$msg);
			return $model->getErrors();
		}
		$attributes = array(
				'master_id' => $uid,
				'group_name' => $name,
				'description' => $description,
				'creation_time' => time(),
				'type' => $type
		);
		
		$model->attributes = $attributes;
		if ( $model->save() ){
			$ownedModel = new UserOwnedGroup();
			$ownedModel->attributes = array(
					'group_id' => $model->getPrimaryKey(),
					'user_id' => $uid,
			);
			$ownedModel->save();
			return $model;
		}else {
			return $model->getErrors();
		}
	}
	
	/**
	 * 
	 * @param int $groupId
	 * @param int $uid
	 * @param int $status
	 * @return boolean|array
	 */
	public function addMemberToGroup($groupId,$uid,$status=0){
		$ownedModel = new UserOwnedGroup();
		$ownedModel->attributes = array(
				'group_id' => $groupId,
				'user_id' => $uid,
				'status' => $status
		);
		$ownedModel->save();
		return $ownedModel;
	}
	
	/**
	 * 
	 * @param int $groupId
	 * @param int $uid
	 * @return boolean
	 */
	public function removeMemberFromGroup($groupId,$uid){
		if ( $this->isGroupMaster($groupId, $uid) === true ){
			return Groups::model()->deleteByPk($groupId) > 0;
		}else {
			return UserOwnedGroup::model()->deleteAll('group_id=:gid AND user_id=:uid',array(
					':gid' => $groupId,
					':uid' => $uid
			)) > 0;
		}
	}
	
	/**
	 * 
	 * @param int $uid
	 * @param int $status
	 * @param string $useRelate
	 * @return UserOwnedGroup[]
	 */
	public function getGroups($uid,$status=0,$useRelate=true){
		$criteria = new CDbCriteria();
		if ( $useRelate === true ){
			$criteria->with = array('group');
		}
		$criteria->condition = 'user_id=:uid AND status=:s';
		$criteria->params = array(':uid'=>$uid,':s'=>$status);
		
		return UserOwnedGroup::model()->findAll($criteria);
	}
}