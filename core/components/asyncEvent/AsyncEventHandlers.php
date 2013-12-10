<?php
/**
 * @name AsyncEventHandlers.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-10 
 * Encoding UTF-8
 */
class AsyncEventHandlers extends CComponent{
	/**
	 * 
	 * @var AsyncEventRunner
	 */
	public static $runner;
	/**
	 * onEndRequest事件处理函数
	 * @param CEvent $event
	 */
	public static function onAppEnd($event){
		self::$runner->raiseAsyncEvent('onEndRequest',$event->params);
	}
	
	/**
	 * onException事件处理函数
	 * @param CExceptionEvent $event
	 */
	public static function onException($event){
		$message = $event->exception->__toString();
		$message.="\nREQUEST_URI=".$_SERVER['REQUEST_URI'];
		$message.="\nHTTP_REFERER=".$_SERVER['HTTP_REFERER'];
		$message.="\n---";
		$event->handled = false;
	
		$event->params = array('message'=>$message);
		self::$runner->raiseAsyncEvent('onException',$event->params);
	}
	
	/**
	 * onError事件处理函数
	 * @param CErrorEvent $event
	 */
	public static function onError($event){
		self::$runner->raiseAsyncEvent('onError',$event->params);
	}
}