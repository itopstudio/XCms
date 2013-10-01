<?php
/**
 * @name CurlMultiBehavior.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-26
 * Encoding UTF-8
 */
class CurlMultiBehavior extends CBehavior{
	/**
	 *
	 * @var resource
	 */
	private $_multiHandler = null;
	/**
	 * 
	 * @var resource[]
	 */
	private $_handlers = array();
	/**
	 * 
	 * @var CurlBehavior[]
	 */
	private $_curlBehaviors = array();
	/**
	 * 
	 * @var int
	 */
	private $_maxConnections = 1;
	/**
	 * 
	 * @var array
	 */
	private $_readableHandlers = array();
	
	/**
	 * 
	 * @return boolean
	 */
	public function exec(){
		$multiHandler = $this->getCurlMultiHandler();
		if ( $this->_handlers === array() ){
			return false;
		}
		
		foreach ( $this->_handlers as $count => $handler ){
			if ( $count >= $this->_maxConnections ){
				break;
			}
			curl_multi_add_handle($multiHandler,$handler);
		}
		
		$active = 0;
		do{
			while ( ($code=curl_multi_exec($multiHandler,$active)) === CURLM_CALL_MULTI_PERFORM );
			if ( $code !== CURLM_OK ){
				return false;
			}
			
			while ( ($reader=curl_multi_info_read($multiHandler)) !== false ){
				if ( $reader['result'] === CURLM_OK ){
					$this->_readableHandlers[] = $reader['handle'];
				}
			}
			
			if ( $active > 0 ){
				curl_multi_select($multiHandler,0.5);
			}
			
		}while ( $active > 0 );
		
		return true;
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function close(){
		if ( $this->_handlers === array() ){
			return true;
		}
		foreach ( $this->_handlers as $count => $handler ){
			if ( $count >= $this->_maxConnections ){
				break;
			}
			curl_multi_remove_handle($this->_maxConnections,$handler);
			curl_close($handler);
		}
		curl_multi_close($this->_multiHandler);
		return true;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getReadableHandlers(){
		return $this->_readableHandlers;
	}
	
	/**
	 * 
	 * @param resource $ch
	 * @return string
	 */
	public function getContent($ch){
		return curl_multi_getcontent($ch);
	}
	
	/**
	 * 
	 * @param resource $ch
	 * @param string $opt
	 * @return mixed
	 */
	public function getInfo($ch,$opt=null){
		return curl_getinfo($ch,$opt);
	}
	
	public function curlMultiInit(){
		$this->_multiHandler = curl_multi_init();
	}
	
	/**
	 * 
	 * @param resource $handlers
	 */
	public function addHandlersToMultiHandler($handlers){
		if ( is_array($handlers) ){
			foreach ( $handlers as $handler ){
				$this->_handlers[] = $handler;
			}
		}else {
			$this->_handlers[] = $handlers;
		}
	}
	
	/**
	 * 
	 * @param CurlBehavior|CurlBehavior[] $behaviors
	 */
	public function addCurlBehaviorsToMultiHandler($behaviors){
		if ( is_array($behaviors) ){
			foreach ( $behaviors as $behavior ){
				$behavior->curlBuildOpts();
				$this->_handlers[] = $behavior->getCurlHandler();
			}
		}else {
			$behaviors->curlBuildOpts();
			$this->_handlers[] = $behaviors->getCurlHandler();
		}
	}
	
	/**
	 * 
	 * @param int $value
	 */
	public function setMaxConnections($value){
		$value = intval($value);
		$this->_maxConnections = $value;
	}
	
	/**
	 * 
	 * @return resource
	 */
	public function getCurlMultiHandler($refresh=false){
		if ( $refresh === true || $this->_multiHandler === null ){
			$this->curlMultiInit();
		}
		return $this->_multiHandler;
	}
}