<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $nickname
 * @property string $realname
 * @property string $email
 * @property string $password
 * @property string $salt
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $locked
 *
 * The followings are the available model relations:
 * @property AdViewClick[] $adViewClicks
 * @property Administrators $administrators
 * @property AuthPermission[] $AuthPermissions
 * @property ChatRoom[] $ChatRooms
 * @property ChatMessage[] $chatMessages
 * @property ChatRoom[] $ChatRooms1
 * @property Groups[] $Groups
 * @property GroupMessage[] $groupMessages
 * @property Groups[] $Groups1
 * @property Groups[] $groups
 * @property ChatMessage[] $ChatMessages
 * @property GroupMessage[] $GroupMessages
 * @property SqbUser $sqbUser
 * @property UserBlacklist[] $userBlacklists
 * @property UserBlacklist[] $userBlacklists1
 * @property AuthGroups[] $AuthGroups
 * @property UserInterest[] $userInterests
 * @property UserInterest[] $userInterests1
 * @property ChatRoom[] $ChatRooms2
 * @property Groups[] $Groups2
 * @property UserReport[] $userReports
 * @property AuthRoles[] $AuthRoles
 * @property UserTrends[] $userTrends
 * @property UserTrendsReply[] $userTrendsReplies
 * @property UserTrends[] $UserTrends
 */
class UserModel extends SingleInheritanceModel
{
	/**
	 * @var boolean
	 */
	private $_changeUUID = false;
	/**
	 * @var array
	 */
	protected $_uuidDependence = array('password');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nickname, password', 'required'),
			array('last_login_time, last_login_ip', 'required', 'on'=>'update'),
			array('locked', 'numerical', 'integerOnly'=>true),
			array('nickname', 'length', 'max'=>20),
			array('realname', 'length', 'max'=>5),
			array('password', 'length', 'max'=>16,'min'=>6,'message'=>'密码需要大于6位小于16位'),
			array('last_login_time', 'length', 'max'=>11),
			array('last_login_ip', 'length', 'max'=>15),
			array('uuid','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nickname, realname, password, uuid, last_login_time, last_login_ip, locked', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'AuthPermissions' => array(self::MANY_MANY, 'AuthPermission', '{{auth_user_permission}}(user_id, permission_id)'),
			'AuthGroups' => array(self::MANY_MANY, 'AuthGroups', '{{user_group}}(user_id, group_id)'),
			'AuthRoles' => array(self::MANY_MANY, 'AuthRoles', '{{user_role}}(user_id, role_id)'),
			'firends' => array(self::HAS_MANY,'UserInterest','follower','condition'=>'status=1'),
			'chatGroups' => array(self::MANY_MANY,'Groups','{{user_own_group}}(group_id,user_id)'),
			'chatRooms' => array(self::MANY_MANY,'ChatRoom','{{user_own_chat}}(room_id,user_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nickname' => 'Nickname',
			'realname' => 'Realname',
			'email' => 'Email',
			'password' => 'Password',
			'uuid' => 'Uuid',
			'last_login_time' => 'Last Login Time',
			'last_login_ip' => 'Last Login Ip',
			'locked' => 'Locked',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('realname',$this->realname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('salt',$this->salt,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('last_login_ip',$this->last_login_ip,true);
		$criteria->compare('locked',$this->locked);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	protected function beforeSave(){
		if ( $this->getIsNewRecord() ){
			$this->changePassword($this->getAttribute('password'));
		}
		
		if ( $this->getIsNewRecord() || $this->_changeUUID === true ){
			$uuidRawData = $this->getAttributes($this->_uuidDependence);
			$uuid = Yii::app()->getSecurityManager()->generateUUID($uuidRawData);
			$this->setAttribute('uuid',$uuid);
		}
		
		return parent::beforeSave();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @param array $uuidRawData
	 * @param array $attributes
	 */
	public function changeUUID($uuidRawData,$attributes){
		if ( $this->_changeUUID === true ){
			return;
		}
		foreach ( $this->_uuidDependence as $dependence ){
			if ( isset($uuidRawData[$dependence]) ){
				if ( $dependence === 'password' && !Yii::app()->getSecurityManager()->verify($uuidRawData[$dependence],$attributes[$dependence]) ){//password
					$this->_changeUUID = true;
					return;
				}elseif ( $uuidRawData[$dependence] !== $attributes[$dependence] ){
					$this->_changeUUID = true;
					return;
				}
			}
		}
	}
	
	/**
	 * @param string $newPassword
	 */
	public function changePassword($newPassword){
		$security = Yii::app()->getSecurityManager();
		$this->_changeUUID = true;
		$this->setAttribute('password',$security->generate($newPassword));
	}
	
	public static function getUserRelationInfo($uid){
		$raw = $this->with(array(
				'friends' => array('with'=>'follwed','select'=>'id,nickname'),
				'chatRooms',
				'chatGroups'
		))->findByPk($uid,array('select'=>'id'));
		if ( empty($raw) ){
			return array();
		}
		$return = array(
				'alias' => 'user'.$uid,
				'friends' => array(),
				'chatRooms' => array(),
				'tags' => array()
		);
		foreach ( $raw->getRelated('friends') as $friend ){
			$follwed = $friend->getRelated('follwed');
			$return['friends'][] = array(
					'id' => $follwed->getAttribute('id'),
					'nickname' => $follwed->getAttribute('nickname')
			);
		}
		foreach ( $raw->getRelated('chatRooms') as $chatRoom ){
			$return['chatRooms'][] = $chatRoom->getAttributes();
			$return['tags'][] = 'room'.$chatRoom->getAttribute('id');
		}
		foreach ( $raw->getRelated('chatGroups') as $chatGroup ){
			$return['chatGroups'][] = $chatGroup->getAttributes();
			$return['tags'][] = 'group'.$chatGroup->getAttribute('id');
		}
		return $return;
	}
}