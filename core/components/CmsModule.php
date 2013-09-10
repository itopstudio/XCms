<?php
/**
 * @name CmsModule.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-8-12
 * Encoding UTF-8
 */
class CmsModule extends CWebModule{
	protected function init(){
		Yii::app()->setComponent('messages', array('basePath'=>Yii::getPathOfAlias('cms.messages')));
	}
}