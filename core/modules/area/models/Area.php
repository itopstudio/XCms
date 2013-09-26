<?php

/**
 * This is the model class for table "{{area}}".
 *
 * The followings are the available columns in table '{{area}}':
 * @property string $id
 * @property string $fid
 * @property string $level
 * @property string $lft
 * @property string $rgt
 * @property string $area_name
 *
 * The followings are the available model relations:
 * @property UserAddress[] $userAddresses
 * @property UserAddress[] $userAddresses1
 */
class Area extends LevelModel
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{area}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('area_name', 'required',),
			array('fid, level, lft, rgt','safe'),
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
			'userAddress' => array(self::HAS_MANY, 'UserAddress', 'location'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '地区',
			'city_tier' => '一二线城市',
			'country' => '国家',
			'cn_name' => '城市',
			'cn_district' => '地区',
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
		$criteria->compare('fid',$this->fid,true);
		$criteria->compare('level',$this->level,true);
		$criteria->compare('lft',$this->lft,true);
		$criteria->compare('rgt',$this->rgt,true);
		$criteria->compare('city_tier',$this->city_tier);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('cn_name',$this->cn_name,true);
		$criteria->compare('cn_district',$this->cn_district,true);
		$criteria->compare('big5_name',$this->big5_name,true);
		$criteria->compare('big5_district',$this->big5_district,true);
		$criteria->compare('en_name',$this->en_name,true);
		$criteria->compare('en_district',$this->en_district,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Area the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
