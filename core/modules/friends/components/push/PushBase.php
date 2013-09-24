<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-9-10
 * Encoding GBK 
 */
abstract class PushBase extends CApplicationComponent{
	public function init(){
		$this->attachBehavior('curl','CurlBehavior');
	}
	
	private function generateVerification(){
		throw Yii::t('friends','child class must impliment PushBase::generateVerification()');
	}
}