<?php
/**
 * @name LoggerCommand.php
 * @author lancelot <lancelot1215@gmail.com>
 * Date 2013-12-10 
 * Encoding UTF-8
 */
Yii::import('cms.components.asyncEvent.logging.*');
class LoggerCommand extends CConsoleCommand{
	public function actionAccess($params){
		$message = json_decode($params,true);
		$attributes = $message['params'];
		$logs = $attributes['logs'];
		unset($attributes['logs']);
		
		$attributes['backtrace'] = json_encode($attributes['backtrace']);
		
		foreach ( $logs as $log ){
			$model = new LoggerAccess();
			$attributes['message'] = $log[0];
			$attributes['level'] = $log[1];
			$attributes['category'] = $log[2];
			$model->attributes = $attributes;
			$model->save();
		}
	}
}