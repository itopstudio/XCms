<?php
/**
 * @name AsyncLogRoute.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-10 
 * Encoding UTF-8
 */
abstract class AsyncLogRoute extends CLogRoute{
	/**
	 *
	 * @var CLogger
	 */
	public $logger;
	/**
	 * 
	 * @var AsyncEventRunner
	 */
	public $eventRunner;
	public $logEventName = 'onAsyncLog';
	protected $logCommand = 'logger';
	protected $logCommandAction;
	
	public function init(){
		$this->logger = Yii::getLogger();
		if ( $this->eventRunner instanceof AsyncEventRunner ){
			$this->eventRunner->registerAsyncEvent($this->logEventName,array(
					'command'=>array($this->logCommand,$this->logCommandAction)
			));
		}
	}
}