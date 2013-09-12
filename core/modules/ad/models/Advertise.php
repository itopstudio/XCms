<?php

/**
 * This is the model class for table "{{advertise}}".
 *
 * The followings are the available columns in table '{{advertise}}':
 * @property string $id
 * @property string $advertiser_id
 * @property string $title
 * @property string $content
 * @property integer $view
 * @property integer $click
 * @property string $direct_to
 * @property integer $pay_type
 * @property double $cpm
 * @property double $cpc
 * @property integer $priority
 *
 * The followings are the available model relations:
 * @property AdViewClick[] $adViewClicks
 * @property Advertiser $advertiser
 * @property AdvertisePic $advertisePic
 */
class Advertise extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{advertise}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('advertiser_id, title, content, direct_to', 'required'),
			array('advertiser_id','exist','className'=>'Advertiser','attributeName'=>'advertiser_id','message'=>'广告主不存在'),
			array('pay_type, priority,cpm, cpc', 'numerical', 'integerOnly'=>true),
			array('view, click','safe'),
			array('advertiser_id', 'length', 'max'=>11),
			array('title', 'length', 'max'=>20),
			array('direct_to', 'length', 'max'=>255),
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
			'adView' => array(self::HAS_MANY, 'AdViewClick', 'advertise_id','condition'=>'type=0'),
			'adClick' => array(self::HAS_MANY, 'AdViewClick', 'advertise_id','condition'=>'type=1'),	
			'advertiser' => array(self::HAS_ONE, 'Advertiser', 'advertiser_id'),
			'adPic' => array(self::HAS_MANY, 'AdvertisePic', 'ad_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'advertiser_id' => 'Advertiser',
			'title' => 'Title',
			'content' => 'Content',
			'view' => 'View',
			'click' => 'Click',
			'direct_to' => 'Direct To',
			'pay_type' => 'Pay Type',
			'cpm' => 'Cpm',
			'cpc' => 'Cpc',
			'priority' => 'Priority',
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
		$criteria->compare('advertiser_id',$this->advertiser_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('view',$this->view);
		$criteria->compare('click',$this->click);
		$criteria->compare('direct_to',$this->direct_to,true);
		$criteria->compare('pay_type',$this->pay_type);
		$criteria->compare('cpm',$this->cpm);
		$criteria->compare('cpc',$this->cpc);
		$criteria->compare('priority',$this->priority);

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
	 * @return Advertise the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
