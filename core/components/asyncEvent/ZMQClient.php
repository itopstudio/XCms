<?php
/**
 * @name ZMQClient.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-11-26 
 * Encoding UTF-8
 */
class ZMQClient extends CApplicationComponent{
	private $_zmqContext = null;
	private $_socket = null;
	public $zmqServer;
	public $sendTimeout;
	public $reciveTimeout;
	public $socketType;
	private $_active = false;
	
	public function init(){
		parent::init();
		$this->_zmqContext = new ZMQContext();
		$this->setSocket($this->socketType);
	}
	
	public function setSocket($type){
		$this->_socket = null;
		$this->_socket = $this->_zmqContext->getSocket($type);
		if(defined('ZMQ::SOCKOPT_SNDTIMEO')){
			$this->_socket->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO,$this->sendTimeout);
		}
		if(defined('ZMQ::SOCKOPT_RCVTIMEO')){
			$this->_socket->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO,$this->reciveTimeout);
		}
	}
	
	/**
	 * 
	 * @param ZMQMessage $zmqMessage
	 * @param int $mode
	 * @return boolean
	 */
	public function send($zmqMessage,$mode=0){
		if ( $zmqMessage instanceof ZMQMessage ){
			if ( $this->_active === false ){
				$this->_socket->connect($this->zmqServer);
				$this->_active = true;
			}
			$this->_socket->send($zmqMessage->run(),$mode);
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 
	 * @param int $mode
	 */
	public function receive($mode=0){
		return $this->_socket->recv($mode);
	}
}

class ZMQMessage extends CComponent{
	private $_data;
	
	public function __construct($data=null){
		$this->_data = $data;
	}

	public function setData($data){
		$this->_data = $data;
	}

	public function run(){
		if ( is_array($this->_data) ){
			return json_encode($this->_data);
		}else {
			return json_encode(array($this->_data));
		}
	}
}