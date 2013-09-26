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
	
	
	public function pushMessage($type,$to,$sendno,$content,$title='',$extras=array()){
		$pusher = $this->getPusher();
		if ( $type === 1 ){
			$return = $pusher->pushMessageWithAlias($sendno,$to,$content,$title,'',$extras);
		}else {
			$return = $pusher->pushMessageWithTags($sendno,$to,$content,$title,'',$extras);
		}
		return $return;
	}
	
	/**
	 * 
	 * @param int $type
	 * @param int $info
	 * @return string
	 */
	public function resolveBindInfo($type,$to,&$attributes=array()){
		$type = intval($type);
		if ( $type === 1 ){
			$bind = 'user'.$to;
			$model = new UserMessage();
			$attributes['receiver'] = $to;
		}elseif ( $type === 2 ){
			$bind = 'room'.$to;
			$model = new RoomMessage();
			$attributes['receive_room'] = $to;
		}elseif ( $type === 3 ){
			$bind = 'group'.$to;
			$model = new GroupMessage();
			$attributes['receive_group'] = $to;
		}else {
			return false;
		}
		return array(
				'bindName' => $bind,
				'model' => $model,
		);
	}
	
	
	
}