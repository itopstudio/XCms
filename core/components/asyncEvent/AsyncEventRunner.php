<?php
/**
 * @name AsyncEventRunner.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-5 
 * Encoding UTF-8
 */
class AsyncEventRunner extends CApplicationComponent{
	public $zmqClientId = 'zmqClient';
	private $_events = array();
	
	/**
	 * 事件处理函数
	 * @param AsyncEvent $event
	 */
	public function processer($event){
		$zmqClient = Yii::app()->getComponent('zmqClient');
		$zmqMessage = new ZMQMessage($event->loadData());
		$zmqClient->send($zmqMessage);
	}
	
	/**
	 * onEndRequest事件处理函数
	 * @param CEvent $event
	 */
	public function onAppEnd($event){
		if ( isset($this->_events['onEndRequest']) ){
			$this->raiseAsyncEvent('onEndRequest',$event->params, $event->sender);
			unset($this->_events['onEndRequest']);
		}
	}
	
	/**
	 * 注册事件
	 * 一般用于配置文件中设置的
	 * @param array $events
	 */
	public function setEvents($events){
		foreach ( $events as $name => $event ){
			$this->registerAsyncEvent($name,$event);
		}
	}
	
	/**
	 * 注册事件
	 * 一般用于程序内部调用
	 * @param string $name
	 * @param array $eventConfig
	 */
	public function registerAsyncEvent($name,$eventConfig){
		if ( $name === 'onEndRequest' ){
			Yii::app()->onEndRequest = array($this,'onAppEnd');
		}
		if ( isset($eventConfig['command']) && is_array($eventConfig['command']) ){
			$this->_events[$name][] = $eventConfig;
		}
	}
	
	/**
	 * 触发事件
	 * @param string $name
	 * @param array $data
	 */
	public function raiseAsyncEvent($name,$params=array()){
		if ( isset($this->_events[$name]) ){
			$configs = $this->_events[$name];
			foreach ( $configs as $config ){
				if ( isset($config['params']) && is_array($config['params'])){
					$config['params'] = array_merge($config['params'],$params);
				}else {
					$config['params'] = $params;
				}
				$config['eventName'] = $name;
				$event = new AsyncEvent($this,$config);
				$this->processer($event);
			}
		}
	}
}

class AsyncEvent extends CEvent{
	public $eventName;
	public $command;
	
	public function __construct($sender,$configs=array()){
		parent::__construct($sender,$configs);
		foreach ( $configs as $name => $config ){
			$this->$name = $config;
		}
	}
	
	public function loadData(){
		return array(
				'eventName' => $this->eventName,
				'command' => $this->command,
				'params' => $this->params
		);
	}
}