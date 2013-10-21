<?php
/**
 * @name CurlBehavior.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-23
 * Encoding UTF-8
 * 
 * @property array $response
 * @property string $output
 * @property resource $curlHandler
 * @property array $error
 * @property boolean $hasError
 */
class CurlBehavior extends CBehavior{
	private $_enableSsl = false;
	private $_url = '';
	private $_method = '';
	/**
	 * curl request header
	 * 
	 * @var array
	 */
	private $_header = array();
	/**
	 * 
	 * 
	 * @var string
	 */
	private $_requestBody = '';
	/**
	 * 
	 * @var string
	 */
	private $_urlParams = '';
	/**
	 * curl request response
	 * 
	 * @var array
	 */
	private $_response = array();
	/**
	 * stores output data when {@link self::returnTransfer} is set to true
	 * 
	 * @var string
	 */
	private $_output = '';
	/**
	 * 
	 * @var boolean
	 */
	private $_returnTransfer = true;
	/**
	 * 
	 * @var string
	 */
	private $_error = '';
	/**
	 * 
	 * @var int
	 */
	private $_errno = 0;
	/**
	 * curl handler
	 * @var resource
	 */
	private $_ch = null;
	/**
	 * 
	 * @var array
	 */
	private $_curlOptions = array();
	
	/**
	 * 
	 * @param string $url
	 * @param array $urlParams
	 * @param array $requestData
	 * @param string $method
	 * @param string $return
	 * @param array $header
	 * @param string $enableSsl
	 */
	public function curlSend($url='',$urlParams=array(),$requestData=array(),$method='',$return=true,$header=array(),$enableSsl=false){
		if ( $this->beforeSend() !== true ){
			return;
		}
		
		if ( $url !== '' ){
			$this->setUrl($url);
		}
		if ( $urlParams !== array() ){
			$this->setUrlParams($urlParams);
		}
		if ( $requestData !== array() ){
			$this->setRequestBody($requestData);
		}
		if ( $method !== '' ){
			$this->setMethod($method);
		}
		if ( $header !== array() ){
			$this->setHeader($header);
		}
		$this->setEnableSsl($enableSsl);
		$this->setReturn($return);
		
		$this->curlBuildOpts();
		$handler = $this->getCurlHandler();
		$result = curl_exec($handler);
		
		if ( $result === false ){
			$this->setError();
		}else {
			$this->_response = curl_getinfo($handler);
			if ( $return !== false ){
				$this->_output = $result;
			}
		}
		curl_close($handler);
	}
	
	/**
	 *
	 * @param string $url
	 */
	public function curlInit($url=''){
		$this->_ch = curl_init();
		if ( $url !== '' ){
			$this->setUrl($url);
		}
	}

	/**
	 *
	 */
	public function curlBuildOpts(){
		if ( $this->getCanBuildOpts() === false ){
			return false;
		}
		$ch = $this->getCurlHandler();
		if ( $this->_urlParams !== '' ){
			$this->setUrl($this->_url.'?'.$this->_urlParams);
		}
		curl_setopt_array($ch,$this->_curlOptions);
	}
	
	/**
	 * 
	 * @return boolean
	 */
	protected function beforeSend(){
		return true;
	}
	
	/**
	 *
	 * @param array $opts
	 */
	public function setOpts($opts = array()){
		$this->_curlOptions = array_merge($this->_curlOptions,$opts);
	}
	
	/**
	 *
	 * @param int $key
	 * @param mixed $value
	 */
	public function setOpt($key,$value){
		$this->_curlOptions[$key] = $value;
	}
	
	/**
	 * please use {@link CurlBehavior::setOpt} or {@link CurlBehavior::setOpts} set ssl options
	 * 
	 * @param boolean $value
	 */
	public function setEnableSsl($value){
		$this->_enableSsl = $value;
	}
	
	/**
	 * 
	 * @param string $url
	 */
	public function setUrl($url){
		$this->_url = $url;
		$this->_curlOptions[CURLOPT_URL] = $url;
	}
	
	/**
	 * 
	 * @param string $method
	 */
	public function setMethod($method){
		$this->_method = strtoupper($method);
		$this->_curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
	}
	
	/**
	 * 
	 * @param array $header
	 */
	public function setHeader($header,$overwrite=false){
		if ( $overwrite === true ){
			$this->_header = $header;
		}else {
			$this->_header = array_merge($this->_header,$header);
		}
		$this->_curlOptions[CURLOPT_HTTPHEADER] = $this->_header;
	}
	
	/**
	 * 
	 * @param array $data
	 * @param boolean $overwrite
	 */
	public function setRequestBody($data,$overwrite=false){
		if ( $overwrite === true || $this->_requestBody === '' ){
			$this->_requestBody = http_build_query($data);
		}else {
			$this->_requestBody .= '&'.http_build_query($data);
		}
		$this->_curlOptions[CURLOPT_POSTFIELDS] = $this->_requestBody;
	}
	
	/**
	 *
	 * @param array $params
	 * @param boolean $overwrite
	 */
	public function setUrlParams($params,$overwrite=false){
		if ( $overwrite === true || $this->_urlParams === '' ){
			$this->_urlParams = http_build_query($params);
		}else {
			$this->_urlParams .= '&'.http_build_query($params);
		}
	
	}
	
	/**
	 * 
	 * @param boolean $value
	 */
	public function setReturn($value){
		$this->_returnTransfer = $value;
		$this->_curlOptions[CURLOPT_RETURNTRANSFER] = $value;
	}
	
	public function setError(){
		$this->_error = curl_error($this->_ch);
		$this->_errno = curl_errno($this->_ch);
	}
	
	public function reset($disableHandler=false){
		$this->_enableSsl = false;
		$this->_url = '';
		$this->_method = '';
		$this->_header = array();
		$this->_requestBody = '';
		$this->_urlParams = '';
		$this->_output = '';
		$this->_returnTransfer = false;
		$this->_error = '';
		$this->_errno = 0;
		if ( $disableHandler === true ){
			$this->_ch = null;
		}
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getResponse($name=null){
		if ( $name !== null && isset($this->_response[$name]) ){
			return $this->_response[$name];
		}else {
			return $this->_response;
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getOutput(){
		return $this->_output;
	}
	
	/**
	 * 
	 * @return resource
	 */
	public function getCurlHandler($refresh=false){
		if ( $refresh === true || $this->_ch === null ){
			$this->reset();
			$this->curlInit();
		}
		return $this->_ch;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getError(){
		return array(
				'errno' => $this->_errno,
				'error' => $this->_error
		);
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function getHasError(){
		return $this->_errno !== 0;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getCanBuildOpts(){
		return $this->_url !== '';
	}
}