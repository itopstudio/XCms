<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-9-10
 * Encoding GBK 
 */
class ChatManager extends CApplicationComponent{
	/**
	 * pusher component id
	 * 
	 * @var string
	 */
	public $messagePusherId = 'JPush';
	
	public function init(){
		Yii::import('friends.components.push.*');
		Yii::app()->setComponent('pusher',array(
				'class' => 'friends.components.push.'.$this->messagePusherId
		));
	}
	
	/**
	 * 
	 * @return PushBase
	 */
	public function getPusher(){
		return Yii::app()->getComponent('pusher');
	}
	
	/**
	 * 
	 * @param int $type
	 * @param int $info
	 * @return string
	 */
	public function resolveBindName($type,$info){
		if ( $type === 1 ){
			$bind = 'user'.$info;
		}elseif ( $type === 2 ){
			$bind = 'room'.$info;
		}elseif ( $type === 3 ){
			$bind = 'group'.$info;
		}else {
			$bind = false;
		}
		return $bind;
	}
	
}