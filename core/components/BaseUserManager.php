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
	 * 
	 * @param CDbCriteria $criteria
	 * @param array $params
	 */
	abstract public function findAll($criteria=null,$params=array());
	/**
	 * 
	 * @param int $pk
	 * @param CDbCriteria $criteria
	 * @param array $params
	 */
	abstract public function findByPk($pk,$criteria=null,$params=array());
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
	 * @param int $exceptUid find user except this id
	 */
	public function getUserRandom($listSize,$criteria=null,$exceptUid=null){
		$countCondition = null;
		if ( $exceptUid !== null ){
			$countCondition = 'id!='.$exceptUid;
		}
		$count = $this->count($countCondition);
		
		if ( $criteria === null ){
			$criteria = new CDbCriteria();
		}elseif ( is_array($criteria) ){
			$criteria = new CDbCriteria($criteria);
		}
		if ( $exceptUid !== null ){
			$criteria->alias = 'x';
			$criteria->addCondition('x.'.$countCondition);
		}
		$criteria->limit = $listSize;
		if ( $listSize >= $count ){
			$criteria->offset = 0;
		}else {
			$criteria->offset = mt_rand(0,$count-$listSize);
		}
		
		return $this->findAll($criteria);
	}
	
	/**
	 * find user who want to make friends with me
	 *
	 * @param int $uid
	 * @return array
	 */
	public function getSayHelloList($uid){
		$criteria = new CDbCriteria();
		$criteria->with = array('follower'=>array(
				'select' => 'id,nickname',
				'with' => array(
						'frontUser' => array(
								'select' => 'icon'
						),
						'trends' => array(
								'select' => 'content,publish_time',
								'limit' => 1,
								'offset' => 0,
								'order' => 'publish_time'
						)
				)
		));
		$criteria->condition = 'followed=:uid AND status=0';
		$criteria->params = array(':uid'=>$uid);
	
		$list = UserInterest::model()->findAll($criteria);
		$return = array();
		foreach ( $list as $key => $l ){
			$follower = $l->getRelated('follower');
			$trends = $follower->getRelated('trends');
			$return[$key] = array(
					'id' => $follower->getAttribute('id'),
					'nickname' => $follower->getAttribute('nickname'),
					'helloId' => $l->getPrimaryKey(),
					'icon' => $follower->getRelated('frontUser')->getAttribute('icon'),
			);
			if ( !empty($trends) ){
				$return[$key]['trend'] = $trends[0]->getAttribute('content');
			}else {
				$return[$key]['trend'] = '';
			}
		}
		return $return;
	}
	
	/**
	 * 
	 * @param int $from
	 * @param int $to
	 * @return int|UserInterest
	 */
	public function sayHello($from,$to){
		$interest = new UserInterest();
		if ( $from === $to ){
			$interest->addError('follower',Yii::t('friends','can not make friends with yourself'));
		}elseif ( $this->isFriend($from,$to) ){
			$interest->addError('follower',Yii::t('friends','you have already been friends'));
		}elseif ( $this->hasSaidHello($from, $to) ){
			$interest->addError('follower',Yii::t('friends','you have already said hello'));
		}elseif ( ($sayHelloed = $this->hasSaidHello($to,$from)) !== false ) {
			$this->makeFriends($from,$sayHelloed->id);
			return true;
		}else {
			$toUser = $this->findByPk($to);
			if ( $toUser === null ){
				$interest->addError('followed','打招呼用户不存在');
				return $interest;
			}
			
			$attributes = array(
					'follower' => $from,
					'followed' => $to,
					'status' => 0
			);
			$interest->attributes = $attributes;
			if ( $interest->save() ){
				return true;
			}
		}
		return $interest;
	}
	
	/**
	 * 
	 * @param int $a
	 * @param int $b
	 * @return boolean
	 */
	public function isFriend($a,$b){
		$condition = 'follower=:a AND followed=:b AND status=1';
		$params = array(':a'=>$a,':b'=>$b);
		return UserInterest::model()->find($condition,$params) !== null;
	}
	
	/**
	 * 
	 * @param int $from
	 * @param int $to
	 * @return boolean|UserInterest
	 */
	public function hasSaidHello($from,$to){
		$condition = 'follower=:a AND followed=:b AND status=0';
		$params = array(':a'=>$from,':b'=>$to);
		$result = UserInterest::model()->find($condition,$params);
		if ( $result !== null ){
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 
	 * @param int $from
	 * @param int $to
	 * @return boolean
	 */
	public function hasDenied($from,$to){
		$condition = 'follower=:a AND followed=:b AND status=2';
		$params = array(':a'=>$from,':b'=>$to);
		return UserInterest::model()->find($condition,$params) !== null;
	}
	
	/**
	 * 
	 * @param int $follower
	 * @param int $followed
	 * @param string $remark
	 * @return boolean
	 */
	public function modifyRemark($follower,$followed,$remark){
		$interest = UserInterest::model()->find('follower=:r AND followed=:d',array(':r'=>$follower,':d'=>$followed));
		if ( $interest === null ){
			return false;
		}
		$interest->remark = $remark;
		$interest->save();
		return true;
	}
	
	/**
	 * 
	 * @param int $uid
	 * @param int $sayHelloId
	 * @return boolean|string
	 */
	public function makeFriends($uid,$sayHelloId){
		$interest = UserInterest::model()->findByPk($sayHelloId);
		if ( $interest === null ){
			return Yii::t('friends','please say hello before you can be friends with him');
		}
		$follower = $interest->getAttribute('follower');
		$followed = $interest->getAttribute('followed');
		
		if ( $uid !== $followed ){
			$interest->addError('follower',Yii::t('friends','can not make friends with him or her'));
			return $interest;
		}

		if ( $this->isFriend($follower,$followed) ){
			$interest->addError('follower',Yii::t('friends','you have already been friends'));
			return $interest;
		}
		
		$interest->setAttribute('status',1);
		$interest->save();
		
		$hasSaidHelloToo = $this->hasSaidHello($followed,$follower);
		if ( $hasSaidHelloToo !== false ){
			$hasSaidHelloToo->setAttribute('status',1);
		}else {
			$hasSaidHelloToo = new UserInterest();
			$hasSaidHelloToo->attributes = array(
					'follower' => $followed,
					'followed' => $follower,
					'status' => 1
			);
		}
		$hasSaidHelloToo->save();
		return $interest;
	}
	
	/**
	 *
	 * @param int $uid
	 * @param int $sayHelloId
	 * @return boolean|string
	 */
	public function denyHello($uid,$sayHelloId){
		$interest = UserInterest::model()->findByPk($sayHelloId);
		if ( $interest === null ){
			$interest->addError('follower',Yii::t('friends','can not denied him'));
			return $interest;
		}
		$follower = $interest->getAttribute('follower');
		$followed = $interest->getAttribute('followed');
		if ( $uid !== $followed ){
			$interest->addError('follower',Yii::t('friends','can not denied him'));
			return $interest;
		}
		if ( $this->hasDenied($follower, $followed) === false ){
			$interest->setAttribute('status',2);
			$interest->save();
			UserInterest::model()->deleteAll('follower=:followed AND followed=:follower',array(':follower'=>$follower,'followed'=>$followed));
		}
		return $interest;
	}
	
	/**
	 *
	 * @param int $uid
	 * @param int $with
	 * @return boolean
	 */
	public function breakUp($uid,$with){
		$criteria = new CDbCriteria();
		$criteria->condition = 'follower=:uid AND followed=:with AND status=1 OR followed=:uid AND follower=:with AND status=1';
		$criteria->params = array(':uid'=>$uid,':with'=>$with);
		
		return UserInterest::model()->deleteAll($criteria) > 0;
	}
}