<?php

/**
 * This is the model class for table "{{chat_message}}".
 *
 * The followings are the available columns in table '{{chat_message}}':
 * @property string $id
 * @property string $sender
 * @property string $receive_room
 * @property string $content
 * @property string $send_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $sender0
 * @property ChatRoom $receiveRoom
 * @property ChatPic[] $chatPics
 * @property User[] $xcmsUsers
 */
class RoomMessage extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{chat_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender, receive_room, content, send_time, status', 'required'),
			array('status', 'numerical', 'integerOnly'=>true),
			array('sender, receive_room, send_time', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sender, receive_room, content, send_time, status', 'safe', 'on'=>'search'),
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
			'sender0' => array(self::BELONGS_TO, 'User', 'sender'),
			'receiveRoom' => array(self::BELONGS_TO, 'ChatRoom', 'receive_room'),
			'chatPics' => array(self::HAS_MANY, 'ChatPic', 'msg_id'),
			'xcmsUsers' => array(self::MANY_MANY, 'User', '{{offline_chat_message}}(msg_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sender' => 'Sender',
			'receive_room' => 'Receive Room',
			'content' => 'Content',
			'send_time' => 'Send Time',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('receive_room',$this->receive_room,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('send_time',$this->send_time,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RoomMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
