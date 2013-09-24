<?php

/**
 * This is the model class for table "{{group_message}}".
 *
 * The followings are the available columns in table '{{group_message}}':
 * @property string $id
 * @property string $sender
 * @property string $receive_group
 * @property string $content
 * @property string $send_time
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property User $sender0
 * @property Groups $receiveGroup
 * @property GroupMsgPic[] $groupMsgPics
 * @property User[] $xcmsUsers
 */
class GroupMessage extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{group_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender, receive_group, content, send_time, status', 'required'),
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
			'receiveGroup' => array(self::BELONGS_TO, 'Groups', 'receive_group'),
			'groupMsgPics' => array(self::HAS_MANY, 'GroupMsgPic', 'msg_id'),
			'xcmsUsers' => array(self::MANY_MANY, 'User', '{{offline_group_message}}(msg_id, user_id)'),
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
			'receive_group' => 'Receive Group',
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
		$criteria->compare('receive_group',$this->receive_group,true);
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
	 * @return GroupMessage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
