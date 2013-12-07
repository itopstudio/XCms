<?php
/**
 * @name RouteCommand.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-7 
 * Encoding UTF-8
 * 
 * 根据请求的事件运行命令
 */
class BrokerEventRouter extends BrokerBackend{
	public function work($params=''){
		if ( $params === '' ){
			return false;
		}
		
		$config = json_decode($params,true);
		if ( !isset($config['command']) ){
			return false;
		}
		$command = $config['command'];
		unset($config['command']);
		
		if ( is_array($command) ){
			if ( count($command) > 1 ){
				list($commandName,$action) = $command;
			}else {
				$commandName = $command[0];
				$action = null;
			}
		}else {
			$commandName = $command;
			$action = null;
		}
		
		$args = array();
		$args[0] = null;//script name
		$args[1] = $commandName;
		$args[2] = $action;
		$args[3] = '--params='.json_encode($config);
		
		return Yii::app()->getCommandRunner()->run($args);
	}
}