<?php
/**
 * @name AsyncEventRunner.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-5 
 * Encoding UTF-8
 */
class AsyncEventRunner extends CApplicationComponent{
	public $zmqClientId = 'zmqClient';
	public $logRouterID = 'log';
	/**
	 * 异步事件，通过{@link self::raiseAsyncEvent}可以发起
	 * @var array
	 */
	private $_events = array();
	/**
	 * {@link CApplication}中定义的事件，事件讲交给handler处理
	 * @var array
	 */
	private $_systemHooks = array();
	/**
	 * 处理系统及用户预定义事件的对象
	 * @var string
	 */
	private $_hookHandler = 'AsyncEventHandlers';
	/**
	 * 异步日志记录路由
	 * @var array
	 */
	private $_asyncLogRoutes = array();
	
	
	public function __construct(){
		Yii::setPathOfAlias('asyncEventRunnerAlias',dirname(__FILE__));
		Yii::import('asyncEventRunnerAlias.*');
		Yii::import('asyncEventRunnerAlias.zmqCommands.*');
		Yii::import('asyncEventRunnerAlias.logging.*');
		
		$hookHandler = $this->_hookHandler;
		$hookHandler::$runner = $this;
		$this->_systemHooks = array(
				'onEndRequest' => array($hookHandler,'onAppEnd'),
				'onException' => array($hookHandler,'onException'),
				'onError' => array($hookHandler,'onError')
		);
	}
	
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
	 * 注册事件
	 * 一般用于配置文件中设置的
	 * @param array $events
	 */
	public function setEvents($events){
		foreach ( $events as $name => $event ){
			$this->registerAsyncEvent($name,$event);
		}
	}
	
	public function setSystemHooks($hooks){
		foreach ( $hooks as $name => $hook ){
			$this->_systemHooks[$name] = $hook;
		}
	}
	
	public function setAsyncLogRoutes($routes){
		$log = Yii::app()->getComponent($this->logRouterID);
		if ( $log === null ){
			return false;
		}
		foreach ( $routes as $i => $route ){
			$route['eventRunner'] = $this;
			$route=Yii::createComponent($route);
			$route->init();
			$routes[$i] = $route;
		}
		$log->setRoutes($routes);
	}
	
	/**
	 * 注册事件
	 * 一般用于程序内部调用
	 * @param string $name
	 * @param array $eventConfig
	 */
	public function registerAsyncEvent($name,$eventConfig){
		if ( array_key_exists($name,$this->_systemHooks) ){
			Yii::app()->$name = $this->_systemHooks[$name];
		}
		if ( !isset($eventConfig[0]) || !is_array($eventConfig[0]) ){
			$configs = array($eventConfig);
		}else {
			$configs = $eventConfig;
		}
		foreach ( $configs as $config ){
			if ( isset($config['command']) && is_array($config['command']) ){
				$this->_events[$name][] = $config;
			}
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
			unset($this->_events[$name]);
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