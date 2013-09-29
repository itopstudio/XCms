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
	 * 
	 * @param int $uid
	 * @return int
	 */
	public function countUserCreation($uid){
		return Groups::model()->count('master_id=:uid',array(':uid'=>$uid));
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
	 * @param string $description
	 * @return boolean
	 */
	public function createGroup($uid,$name,$description=null){
		$model = new Groups();
		
		if ( $this->countUserCreation($uid) >= $this->maxCreation ){
			$model->addError('master_id',Yii::t('friends','you can only create {num} group(s) in total',array(
					'{num}' => $this->maxCreation,
			)));
		}
		$attributes = array(
				'master_id' => $uid,
				'group_name' => $name,
				'description' => $description,
				'creation_time' => time(),
		);
		
		$model->attributes = $attributes;
		if ( $model->save() ){
			$ownedModel = new UserOwnedGroup();
			$ownedModel->attributes = array(
					'group_id' => $model->getPrimaryKey(),
					'user_id' => $uid,
			);
			$ownedModel->save();
			return true;
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
		if ( $ownedModel->save() ){
			return true;
		}else {
			return $ownedModel->getErrors();
		}
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
}