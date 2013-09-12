<?php
/**
 * @name AdModule.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-11
 * Encoding UTF-8
 */
class AdModule extends CmsModule{
	public function init(){
		Yii::import('ad.components.*');
		Yii::import('ad.models.*');
		Yii::app()->setComponent('AdManager',array(
				'class' => 'ad.components.AdManager'
		));
	}
}