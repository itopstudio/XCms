<?php
/**
 * @name ReplyServerBase.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-7 
 * Encoding UTF-8
 */
abstract class ReplyServerBase extends ZMQBaseCommand{
	/**
	 * 绑定地址
	 * @var string
	 */
	public $bindAddress;
	/**
	 * socket类型
	 * @var int
	 */
	public $socketType;
	/**
	 * socket实例
	 * @var ZMQSocket
	 */
	private $_socket = null;
	
	public function run($args){
		$socket = $this->getSocket();
		$socket->bind($this->bindAddress);
		while ( true ){
			$request = $socket->recv();
			
			$result = $this->work($request);
			
			$socket->send($result);
		}
	}
	
	protected function createSocket(){
		$this->_socket = $this->getContext()->getSocket($this->socketType);
	}
	
	public function getSocket(){
		if ( $this->_socket === null ){
			$this->createSocket();
		}
		return $this->_socket;
	}
	
	abstract public function work($params='');
}