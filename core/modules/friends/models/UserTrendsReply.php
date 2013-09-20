<?php

/**
 * This is the model class for table "{{user_trends_reply}}".
 *
 * The followings are the available columns in table '{{user_trends_reply}}':
 * @property string $id
 * @property string $trends_id
 * @property string $user_id
 * @property string $content
 *
 * The followings are the available model relations:
 * @property UserTrends $trends
 * @property User $user
 */
class UserTrendsReply extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_trends_reply}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('trends_id, user_id, content', 'required'),
			array('trends_id, user_id', 'length', 'max'=>11),
			array('id','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, trends_id, user_id, content', 'safe', 'on'=>'search'),
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
			'trends' => array(self::BELONGS_TO, 'UserTrends', 'trends_id'),
			'user' => array(self::BELONGS_TO, 'UserModel', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'trends_id' => 'Trends',
			'user_id' => 'User',
			'content' => 'Content',
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
		$criteria->compare('trends_id',$this->trends_id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserTrendsReply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
