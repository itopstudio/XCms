<?php
/**
 * @name getSayHelloToMeAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class getSayHelloToMeAction extends CmsAction{
	public function run($resourceId){
		$loginedUid = $this->app->getUser()->getId();
		if ( $loginedUid === $resourceId ){
			$list = UserInterest::model()->getSayHelloList($loginedUid);
			$this->response(200,'',$list);
		}else {
			$this->response(400,Yii::t('friends','you can only get your say hello list'));
		}
	}
}