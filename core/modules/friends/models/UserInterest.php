<?php

/**
 * This is the model class for table "{{user_interest}}".
 *
 * The followings are the available columns in table '{{user_interest}}':
 * @property string $follower
 * @property string $followed
 * @property string $remark
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $follower
 * @property User $followed
 */
class UserInterest extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_interest}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('follower, followed, status', 'required'),
			array('followed','unionUnique','unionAttributes'=>array('follower'),'message'=>Yii::t('friends','you have already said hello')),
			array('status', 'numerical', 'integerOnly'=>true),
			array('follower, followed', 'length', 'max'=>11),
			array('remark', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('follower, followed, remark, status', 'safe', 'on'=>'search'),
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
			'follower' => array(self::BELONGS_TO, 'UserModel', 'follower'),
			'followed' => array(self::BELONGS_TO, 'UserModel', 'followed'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'follower' => 'Follower',
			'followed' => 'Followed',
			'remark' => 'Remark',
			'status' => 'Status',
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

		$criteria->compare('follower',$this->follower,true);
		$criteria->compare('followed',$this->followed,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserInterest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
				'select' => 'nickname'
		));
		$criteria->condition = 'followed=:uid AND status=0';
		$criteria->params = array(':uid'=>$uid);
		
		$list = $this->findAll($criteria);
		$return = array();
		foreach ( $list as $l ){
			$return[] = $l->getRelated('follower')->getAttributes(array('nickname','id'));
		}
		return $return;
	}
	
	/**
	 * 
	 * @param int $uid
	 * @param int $with
	 * @return boolean
	 */
	public function breakUp($uid,$with){
		$criteria = new CDbCriteria();
		$criteria->condition = 'follower=:uid AND followed=:with AND status=1';
		$criteria->params = array(':uid'=>$uid,':with'=>$with);
		
		return $this->deleteAll($criteria) > 0;
	}
}