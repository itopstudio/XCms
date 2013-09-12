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
	public $enableSsl = false;
	/**
	 * Protal上生成的app key
	 * @var string
	 */
	public $appKey;
	/**
	 * Protal上生成的masterSecret
	 * @var string
	 */
	public $masterSecret;
	/**
	 * 离线消息保存时间，默认10天
	 * @var int
	 */
	public $timeToLive = 864000;
	/**
	 * 用户终端类型
	 * @var string
	 */
	public $platform = null;
	
}