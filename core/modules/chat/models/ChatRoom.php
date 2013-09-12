<?php

/**
 * This is the model class for table "{{chat_room}}".
 *
 * The followings are the available columns in table '{{chat_room}}':
 * @property string $id
 * @property string $room_name
 * @property integer $user_num
 * @property string $description
 * @property integer $admin_num
 *
 * The followings are the available model relations:
 * @property User[] $xcmsUsers
 * @property ChatMessage[] $chatMessages
 */
class ChatRoom extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{chat_room}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('room_name, admin_num', 'required'),
			array('user_num, admin_num', 'numerical', 'integerOnly'=>true),
			array('room_name', 'length', 'max'=>15),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, room_name, user_num, description, admin_num', 'safe', 'on'=>'search'),
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
			'xcmsUsers' => array(self::MANY_MANY, 'User', '{{user_own_chat}}(room_id, user_id)'),
			'chatMessages' => array(self::HAS_MANY, 'ChatMessage', 'receive_room'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'room_name' => 'Room Name',
			'user_num' => 'User Num',
			'description' => 'Description',
			'admin_num' => 'Admin Num',
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
		$criteria->compare('room_name',$this->room_name,true);
		$criteria->compare('user_num',$this->user_num);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('admin_num',$this->admin_num);

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
	 * @return ChatRoom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
