<?php

/**
 * This is the model class for table "{{logger_access}}".
 *
 * The followings are the available columns in table '{{logger_access}}':
 * @property string $id
 * @property string $user_id
 * @property string $username
 * @property string $remote_ip
 * @property string $message
 * @property string $catgory
 * @property string $level
 * @property string $logtime
 * @property string $execution_time
 * @property string $memory_usage
 * @property string $backtrace
 */
class LoggerAccess extends CmsActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{logger_access}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('remote_ip, message, logtime, execution_time, backtrace', 'required'),
			array('user_id,username,execution_time,memory_usage,category,level','safe')
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'username' => 'Username',
			'remote_ip' => 'Remote Ip',
			'message' => 'Message',
			'catgory' => 'Catgory',
			'level' => 'Level',
			'logtime' => 'Logtime',
			'execution_time' => 'Execution Time',
			'memory_usage' => 'Memory Usage',
			'backtrace' => 'Backtrace',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LoggerAccess the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
