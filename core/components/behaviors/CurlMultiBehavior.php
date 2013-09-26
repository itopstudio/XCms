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
	
	public function curlMultiInit(){
		$this->_multiHandler = curl_multi_init();
	}
	
	public function curlAddToMultiHandler($handlers){
		$multiHandler = $this->getCurlHandler();
		if ( is_array($handlers) ){
			foreach ( $handlers as $handler ){
				curl_multi_add_handle($multiHandler,$handlers);
			}
		}else {
			curl_multi_add_handle($multiHandler,$handlers);
		}
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