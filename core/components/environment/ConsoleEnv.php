<?php
/**
 * @name ConsoleEnv.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-5 
 * Encoding UTF-8
 * 
 * @property CConsoleApplication $application
 */
require_once dirname(__FILE__).'/Environment.php';
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
class ConsoleEnv extends Environment{
	public function prepare(){
		parent::prepare();
		$this->application->commandRunner->addCommands(YII_PATH.'/cli/commands');
		$env=@getenv('YII_CONSOLE_COMMANDS');
		if(!empty($env))
			$this->application->commandRunner->addCommands($env);
		
// 		$commands = $configs['params']['commands'];
// 		foreach ( $commands as $command ){
// 			$this->application->commandRunner->addCommands(Yii::getPathOfAlias($command));
// 		}
	}
}