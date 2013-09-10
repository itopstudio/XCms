<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-9-10
 * Encoding GBK 
 */
class ChatModule extends CmsModule{
	public function init(){
		parent::init();
		Yii::import('chat.controllers.*');
	}
}