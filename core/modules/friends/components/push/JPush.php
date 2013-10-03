<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-9-10
 * Encoding GBK 
 * 
 */
class JPush extends PushBase{
	/**
	 * 设备类型android
	 * @var string
	 */
	const DEVICE_ANDROID = 'android';
	/**
	 * 设备类型ios
	 * @var string
	 */
	const DEVICE_IOS = 'ios';
	/**
	 * 调用URL
	 * @var string
	 */
	const BASE_URL = 'http://api.jpush.cn:8800/sendmsg/v2/';
	/**
	 * SSL URL
	 * @var string
	 */
	const BASE_URL_SSL = 'https://api.jpush.cn:443/sendmsg/v2/';
	/**
	 * 使用接口类型，发送消息
	 * @var string
	 */
	const API_SENDMSG = 'sendmsg';
	/**
	 * 发送通知
	 * @var string
	 */
	const API_NOTIFICATION = 'notification';
	/**
	 * 发送自定义消息
	 * @var string
	 */
	const API_CUSTOM_MESSAGE = 'custom_message';
	/**
	 * 是否使用ssl连接
	 * @var boolean
	 */
	private $_enableSsl = false;
	/**
	 * Protal上生成的app key
	 * @var string
	 */
	private $_appKey = '';
	/**
	 * Protal上生成的masterSecret
	 * @var string
	 */
	private $_masterSecret = '';
	/**
	 * 离线消息保存时间，默认不保存
	 * @var int
	 */
	private $_timeToLive = 0;
	
	private $_sendno = 0;
	private $_message = '';
	private $_messageType = 1;
	private $_receiverValue = '';
	private $_receiverType = null;
	private $_verificationCode = '';
	private $_platform = 'ios,android';
	private $_description = '';
	private $_overrideMsgId = null;
	private $_baseUrl = '';
	private $_apiUrl = '';
	private $_requestUrl = '';
	private $_requestData = array();
	
	public function init(){
		parent::init();
		$this->_baseUrl = self::BASE_URL;
		$this->_apiUrl = self::API_SENDMSG;
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $tags
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $overrideMsgId
	 * @return JPushError
	 */
	public function pushNotificationWithTags($sendno=null,$tags='',$msgContent='',$msgTitle='',$builderId=0,$extras=array(),$overrideMsgId=null){
		return $this->push($sendno,$msgContent,$msgTitle,$builderId,'',$extras,1,$tags,2,$overrideMsgId);
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $alias
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $overrideMsgId
	 * @return JPushError
	 */
	public function pushNotificationWithAlias($sendno=null,$alias='',$msgContent='',$msgTitle='',$builderId=0,$extras=array(),$overrideMsgId=null){
		return $this->push($sendno,$msgContent,$msgTitle,$builderId,'',$extras,1,$alias,3,$overrideMsgId);
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $overrideMsgId
	 * @return JPushError
	 */
	public function pushNotificationWithAppKey($sendno=null,$msgContent='',$msgTitle='',$builderId=0,$extras=array(),$overrideMsgId=null){
		$this->setReceiver('');
		return $this->push($sendno,$msgContent,$msgTitle,$builderId,'',$extras,1,'',4,$overrideMsgId);
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $tags
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $overrideMsgId
	 * @return JPushError
	 */
	public function pushMessageWithTags($sendno=null,$tags='',$msgContent='',$msgTitle='',$contentType='',$extras=array(),$overrideMsgId=null){
		return $this->push($sendno,$msgContent,$msgTitle,0,$contentType,$extras,2,$tags,2,$overrideMsgId);
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $alias
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $overrideMsgId
	 * @return JPushError
	 */
	public function pushMessageWithAlias($sendno=null,$alias='',$msgContent='',$msgTitle='',$contentType='',$extras=array(),$overrideMsgId=null){
		return $this->push($sendno,$msgContent,$msgTitle,0,$contentType,$extras,2,$alias,3,$overrideMsgId);
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $overrideMsgId
	 * @return JPushError
	 */
	public function pushMessageWithAppKey($sendno=null,$msgContent='',$msgTitle='',$contentType='',$extras=array(),$overrideMsgId=null){
		$this->setReceiver('');
		return $this->push($sendno,$msgContent,$msgTitle,0,$contentType,$extras,2,'',4,$overrideMsgId);
	}
	
	/**
	 * 
	 * @param string $sendno
	 * @param string $msgContent
	 * @param string $msgTitle
	 * @param number $builderId
	 * @param array $extras
	 * @param string $msgType
	 * @param string $receiver
	 * @param string $receiverType
	 * @param string $overrideMsgId
	 * @throws CException
	 * @return JPushError
	 */
	public function push($sendno=null,$msgContent='',$msgTitle='',$builderId=0,$contentType='',$extras=array(),$msgType=null,$receiver='',$receiverType=null,$overrideMsgId=null){
		if ( $sendno !== null ){
			$this->setSendno($sendno);
		}
		$this->setMessage($msgContent,$msgTitle,$builderId,$contentType,$extras,$msgType);
		if ( $receiver !== '' ){
			$this->setReceiver($receiver);
		}
		if ( $receiverType !== null ){
			$this->setReceiverType($receiverType);
		}
		if ( $overrideMsgId !== null ){
			$this->setOverrideMsgId($overrideMsgId);
		}
		
		$this->generateVerification();
		$this->buildMessage();
		$this->buildUrl();
		$curl = $this->curl;
		
		$curl->curlSend($this->_requestUrl,array(),$this->_requestData,'POST');
		if ( $curl->hasError ){
			$error = $curl->getError();
			throw new CException('Http Error:'.$error['errno'].' '.$error['error']);
		}else {
			$output = json_decode($curl->getOutput(),true);
			$error = new JPushError($output);
			return $error;
		}
	}
	
	public function pushMulti($data,$maxConnection=50){
		$handlers = array();
		$curl = $this->curl;
		$curlMulti = $this->curlMulti;
		$curlMulti->setMaxConnections($maxConnection);
		
		foreach ( $data as $d ){
			list($sendno,$msgContent,$msgTitle,$builderId,$contentType,
				 $extras,$msgType,$receiver,$receiverType,$overrideMsgId) = $d;
			
			if ( $sendno !== null ){
				$this->setSendno($sendno);
			}
			$this->setMessage($msgContent,$msgTitle,$builderId,$contentType,$extras,$msgType);
			if ( $receiver !== '' ){
				$this->setReceiver($receiver);
			}
			if ( $receiverType !== null ){
				$this->setReceiverType($receiverType);
			}
			if ( $overrideMsgId !== null ){
				$this->setOverrideMsgId($overrideMsgId);
			}
			
			$this->generateVerification();
			$this->buildMessage();
			$this->buildUrl();
			
			$handlers[] = $curl->getCurlHandler(true);
			$curl->setUrl($this->_requestUrl);
			$curl->setReturn(true);
			$curl->setMethod('POST');
			$curl->setRequestBody($this->_requestData);
			$curl->curlBuildOpts();
			
			$this->_requestData = null;
			$this->_requestData = array();
		}
		
		$curlMulti->addHandlersToMultiHandler($handlers);
		return $curlMulti->exec();
	}
	
	/**
	 *
	 * @param string $platform
	 * @param boolean $overwrite
	 */
	public function setPlatform($platform,$overwrite=false){
		if ( $overwrite === true || $this->_platform === '' ){
			$this->_platform = $platform;
		}else {
			$this->_platform .= ','.$platform;
		}
	}
	
	/**
	 * 
	 * @param int $sendno
	 */
	public function setSendno($sendno){
		$this->_sendno = $sendno;
		$this->_requestData['sendno'] = $sendno;
	}
	
	/**
	 * 
	 * @param string $appKey
	 */
	public function setAppKey($appKey){
		$this->_appKey = $appKey;
		$this->_requestData['app_key'] = $appKey;
	}
	
	/**
	 * 
	 * @param string $masterSecret
	 */
	public function setMasterSecret($masterSecret){
		$this->_masterSecret = $masterSecret;
	}
	
	/**
	 * 
	 * @param boolean $value
	 */
	public function setEnableSsl($value){
		$this->_enableSsl = $value;
		if ( $value === true ){
			$this->_baseUrl = self::BASE_URL_SSL;
		}
	}
	
	/**
	 * 
	 * @param string $receiver
	 * @param int $type
	 */
	public function setReceiver($receiver,$type=null){
		$this->_receiverValue = $receiver;
		$this->_requestData['receiver_value'] = $receiver;
		if ( $type !== null ){
			$this->setReceiverType($type);
		}
	}
	
	/**
	 * 
	 * @param int $type
	 */
	public function setReceiverType($type){
		$this->_receiverType = $type;
		$this->_requestData['receiver_type'] = $type;
	}
	
	/**
	 * 
	 * @param array $data
	 * @param int $type
	 */
	public function setMessage($content,$title='',$builderId=0,$contentType='',$extras=array(),$type=null){
		$this->_message = array(
				'content' => $content,
				'contentType' => $contentType,
				'title' => $title,
				'builderId' => $builderId,
				'extras' => $extras
		);;
		if ( $type !== null ){
			$this->setMessageType($type);
		}
	}
	
	/**
	 * 
	 * @param int $type
	 */
	public function setMessageType($type){
		$this->_messageType = $type;
		$this->_requestData['msg_type'] = $type;
	}
	
	/**
	 * 
	 * @param string $description
	 */
	public function setDescription($description){
		$this->_description = $description;
		$this->_requestData['send_description'] = $description;
	}
	
	/**
	 * 
	 * @param int $timeToLive
	 */
	public function setTimeToLive($timeToLive){
		$this->_timeToLive = $timeToLive;
		$this->_requestData['time_to_live'] = $timeToLive;
	}
	
	/**
	 *
	 * @param int $timeToLive
	 */
	public function setOverrideMsgId($overrideMsgId){
		$this->_overrideMsgId = $overrideMsgId;
		$this->_requestData['override_msg_id'] = $overrideMsgId;
	}
	
	private function generateVerification(){
		$this->_verificationCode = md5($this->_sendno.$this->_receiverType.$this->_receiverValue.$this->_masterSecret);
		$this->_requestData['verification_code'] = $this->_verificationCode;
	}
	
	private function buildUrl(){
		$this->_requestUrl = $this->_baseUrl.$this->_apiUrl;
	}
	
	private function buildMessage(){
		if ( !isset($this->_message['content']) ){
			throw new CException(Yii::t('friends','can not build message without content field'));
		}
		
		if ( $this->_messageType === 1 ){
			$data = array(
					'n_builder_id' => $this->_message['builderId'],
					'n_title' => $this->_message['title'],
					'n_content' => $this->_message['content'],
					'n_extras' => $this->_message['extras']
			);
			$this->_requestData['msg_content'] = json_encode($data);
		}else {
			$data = array(
					'message' => $this->_message['content'],
					'content_type' => $this->_message['contentType'],
					'title' => $this->_message['title'],
					'extras'=> $this->_message['extras']
			);
			$this->_requestData['msg_content'] = json_encode($data);
		}
	}
}

class JPushError extends CComponent{
	private $_sendno=null;
	private $_msgId=null;
	private $_errorCode;
	private $_errorMsg;
	
	public function __construct($errorInfo){
		$this->_errorCode = $errorInfo['errcode'];
		$this->_errorMsg = $errorInfo['errmsg'];
		if ( $this->_errorCode === 0 ){
			$this->_sendno = $errorInfo['sendno'];
			$this->_msgId = $errorInfo['msg_id'];
		}
		
	}
	
	public function getHasError(){
		return $this->_errorCode !== 0;
	}
	
	public function getSendno(){
		return $this->_sendno;
	}
	
	public function getMsgId(){
		return $this->_msgId;
	}
	
	public function getErrorCode(){
		return $this->_errorCode;
	}
	
	public function getErrorMsg(){
		return $this->_errorMsg;
	}
}