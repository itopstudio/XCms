<?php
/**
 * @name ZMQClient.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-11-26 
 * Encoding UTF-8
 */
class ZMQClient extends CAppicationComponent{
	private $_zmqContext;
	private $_zmqRequester;
	public $zmqServer;
	public $sendTimeout;
	public $reciveTimeout;
	
	public function init(){
		parent::init();
		$this->_zmqContext = new ZMQContext();
		$this->_zmqRequester = new ZMQSocket($this->_zmqContext, ZMQ::SOCKET_REQ);
		
		if(defined('ZMQ::SOCKOPT_SNDTIMEO')){
			$this->requester->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO,$this->sendTimeout);
		}
		if(defined('ZMQ::SOCKOPT_RCVTIMEO')){
			$this->requester->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO,$this->reciveTimeout);
		}
		$this->requester->connect($this->zmqServer);
	}
	
	public function send($zmqMessage){
		if ( $zmqMessage instanceof ZMQMessage ){
			$this->_zmqRequester->send($zmqMessage->run());
			return true;
		}else {
			return false;
		}
	}
}