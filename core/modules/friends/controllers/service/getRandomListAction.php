<?php
/**
 * @name getRandomListAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getRandomListAction extends CmsAction{
	public function run($resourceId){
		$loginedId = $this->app->getUser()->getId();
		if ( $loginedId !== $resourceId ){
			$this->response(400,Yii::t('friends','can not get random list'));
		}
		$className = $this->getController()->getModule()->frontUserModelClass;
		if ( $className === null ){
			$this->response(501);
		}
		$model = $className::model();
		$criteria = new CDbCriteria();
		$count = $model->count();
	}
}