<?php

/**
 * This is the model class for table "{{groups}}".
 *
 * The followings are the available columns in table '{{groups}}':
 * @property string $id
 * @property string $master_id
 * @property string $group_name
 * @property string $description
 * @property string $announcement
 * @property integer $admin_num
 * @property integer $user_num
 * @property integer $creation_time
 *
 * The followings are the available model relations:
 * @property User[] $xcmsUsers
 * @property GroupMessage[] $groupMessages
 * @property User $master
 */
class Groups extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{groups}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id, master_id, group_name, admin_num, creation_time', 'required'),
			array('admin_num, user_num, creation_time', 'numerical', 'integerOnly'=>true),
			array('id, master_id', 'length', 'max'=>11),
			array('group_name', 'length', 'max'=>15),
			array('description, announcement', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, master_id, group_name, description, announcement, admin_num, user_num, creation_time', 'safe', 'on'=>'search'),
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
			'xcmsUsers' => array(self::MANY_MANY, 'User', '{{user_own_group}}(group_id, user_id)'),
			'groupMessages' => array(self::HAS_MANY, 'GroupMessage', 'receive_group'),
			'master' => array(self::BELONGS_TO, 'User', 'master_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'master_id' => 'Master',
			'group_name' => 'Group Name',
			'description' => 'Description',
			'announcement' => 'Announcement',
			'admin_num' => 'Admin Num',
			'user_num' => 'User Num',
			'creation_time' => 'Creation Time',
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
		$criteria->compare('master_id',$this->master_id,true);
		$criteria->compare('group_name',$this->group_name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('announcement',$this->announcement,true);
		$criteria->compare('admin_num',$this->admin_num);
		$criteria->compare('user_num',$this->user_num);
		$criteria->compare('creation_time',$this->creation_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbLocal;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Groups the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
