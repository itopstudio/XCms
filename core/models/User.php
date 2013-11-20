<?php

/**
 * This is the model class for table "{{user}}".
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $id
 * @property string $password
 * @property string $uuid
 * @property string $last_login_time
 * @property string $last_login_ip
 * @property integer $locked
 * @property string $user_type
 *
 * The followings are the available model relations:
 * @property AuthPermission[] $authPermissions
 * @property FrontUser $frontUser
 * @property AuthGroups[] $authGroups
 * @property AuthRoles[] $authRoles
 */
class User extends SingleInheritance
{
	/**
	 * @var boolean
	 */
	private $_changeUUID = false;
	/**
	 * @var array
	 */
	protected $_uuidDependence = array('password');
	
	public $maxPassword = 15;
	public $minPassword = 6;
	
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
		$passwordMessage = '密码需要大于'.$this->minPassword.'且小于'.$this->maxPassword;
		return array(
			array('password', 'required','message'=>'密码不能为空'),
			array('password', 'length', 
					'max'=>$this->maxPassword,
					'min'=>$this->minPassword,
					'tooShort'=>$passwordMessage,
					'tooLong'=>$passwordMessage,
					'message'=>$passwordMessage,
					
			),
			array('uuid,locked,user_type,last_login_time,last_login_ip','safe')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'authPermissions' => array(self::MANY_MANY, 'AuthPermission', '{{auth_user_permission}}(user_id, permission_id)'),
			'frontUser' => array(self::HAS_ONE, 'FrontUser', 'id'),
			'authGroups' => array(self::MANY_MANY, 'AuthGroups', '{{user_group}}(user_id, group_id)'),
			'authRoles' => array(self::MANY_MANY, 'AuthRoles', '{{user_role}}(user_id, role_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'password' => 'Password',
			'uuid' => 'Uuid',
			'last_login_time' => 'Last Login Time',
			'last_login_ip' => 'Last Login Ip',
			'locked' => 'Locked',
			'user_type' => 'User Type',
		);
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
	
	protected function beforeSave(){
		$isNewRecord = $this->getIsNewRecord();
		if ( $isNewRecord ){
			$this->setPassword($this->getAttribute('password'));
		}

		if ( $isNewRecord || $this->_changeUUID === true ){
			$uuidRawData = $this->getAttributes($this->_uuidDependence);
			$uuid = Yii::app()->getSecurityManager()->generateUUID($uuidRawData);
			$this->setAttribute('uuid',$uuid);
		}

		return parent::beforeSave();
	}
	
	public function setPassword($password,$cost=11){
		$security = Yii::app()->getSecurityManager();
		$this->_changeUUID = true;
		$this->setAttribute('password',$security->generatePassword($newPassword,$cost));
	}
}
