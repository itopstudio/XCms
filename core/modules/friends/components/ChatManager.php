<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-9-10
 * Encoding GBK 
 */
class ChatManager extends CApplicationComponent{
	/**
	 * pusher component id
	 * @var string
	 */
	public $messagePusherId = 'JPush';
	
	public function init(){
		Yii::import('chat.components.*');
		Yii::import('chat.components.push.*');
		Yii::import('chat.models.*');
		Yii::app()->setComponents(array(
				'pusher' => array(
						'class' => 'chat.components.push.'.$this->messagePusherId
				)
		));
	}
	
	/**
	 * @return PushBase
	 */
	public function getPusher(){
		return Yii::app()->getComponent('pusher');
	}
}