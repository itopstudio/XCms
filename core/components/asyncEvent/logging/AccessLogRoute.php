<?php
/**
 * @name AccessLogRoute.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-10 
 * Encoding UTF-8
 */
class AccessLogRoute extends AsyncLogRoute{
	protected $logCommandAction = 'access';
	
	protected function processLogs($logs){
		$app = Yii::app();
		$user = $app->getUser();
		
		$params = array(
				'user_id' => $user->getId(),
				'username' => $user->getName(),
				'remote_ip' => $app->getRequest()->getUserHostAddress(),
				'logtime' => time(),
				'logs' => $logs,
				'execution_time' => $this->logger->getExecutionTime(),
				'memory_usage' => $this->logger->getMemoryUsage(),
				'backtrace' => debug_backtrace()
		);
		$this->eventRunner->raiseAsyncEvent($this->logEventName,$params);
	}
}