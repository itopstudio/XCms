<?php
/**
 * @name ZMQMessage.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-11-26 
 * Encoding UTF-8
 */
class ZMQMessage extends CComponent{
	private $_data;
	private $_extras;
	
	public function setData($data){
		$this->_data = $data;
	}
	
	public function setExtras($extras){
		$this->_extras = $extras;
	}
	
	public function run(){
		return json_encode(array('data'=>$this->_data,'extras'=>$this->_extras));
	}
}