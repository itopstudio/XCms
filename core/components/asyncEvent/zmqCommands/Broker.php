<?php
/**
 * @name Broker.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-7 
 * Encoding UTF-8
 */
class Broker extends ZMQBaseCommand{
	/**
	 * 后端连接地址
	 * @var array
	 */
	public $backendBindAddress;
	/**
	 * 前端连接地址
	 * @var string
	 */
	public $frontendBindAddress;
	
	private $_frontendHandler;
	private $_backendHandler;
	private $_poll;
	
	public function run($args){
		$context = $this->getContext();
		$this->_frontendHandler = $context->getSocket(ZMQ::SOCKET_ROUTER);
		$this->_backendHandler = $context->getSocket(ZMQ::SOCKET_DEALER);
		
		$this->_frontendHandler->bind($this->frontendBindAddress);
		$this->_backendHandler->bind($this->backendBindAddress);
		
		$this->_poll = new ZMQPoll();
		$this->_poll->add($this->_frontendHandler,ZMQ::POLL_IN);
		$this->_poll->add($this->_backendHandler,ZMQ::POLL_IN);
		
		$this->work();
	}
	
	protected function work(){
		$readable = $writeable = array();
		while ( true ){
			$events = $this->_poll->poll($readable,$writeable);
			
			foreach ( $readable as $socket ){
				if ( $socket === $this->_frontendHandler ){
					while ( true ){
						$message = $socket->recv();
						$more = $socket->getSockOpt(ZMQ::SOCKOPT_RCVMORE);
						$this->_backendHandler->send($message,$more ? ZMQ::MODE_SNDMORE : null);
						if ( !$more ){
							break;
						}
					}
				}elseif ( $socket === $this->_backendHandler ){
					while ( true ){
						$message = $socket->recv();
						$more = $socket->getSockOpt(ZMQ::SOCKOPT_RCVMORE);
						$this->_frontendHandler->send($message,$more ? ZMQ::MODE_SNDMORE : null);
						if ( !$more ){
							break;
						}
					}
				}//endif
			}//endforeach
			
		}
	}
}