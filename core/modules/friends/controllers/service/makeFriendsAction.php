<?php
/**
 * @name makeFriendsAction.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-17
 * Encoding UTF-8
 */
class makeFriendsAction extends CmsAction{
	/**
	 * 
	 * @param int $resourceId logined user id
	 */
	public function run($resourceId){
		$loginedUid = $this->app->getUser()->getId();
		
		if ( $resourceId === $loginedUid ){
			$with = $this->getPost('with');
			if ( $with === $loginedUid ){
				$this->response(400,Yii::t('friends','can not make friends with yourself'));
			}
			$interest = new UserInterest();
			
			$post = $this->getPost();
			$post['follower'] = $loginedUid;
			$post['followed'] = $with;
			$post['status'] = 0;
			
			$interest->attributes = $post;
			if ( $interest->save() ){
				$this->response(200,Yii::t('friends','say hello success'));
			}else {
				$this->response(400,'',$interest->getErrors());
			}
			
		}else {
			$this->response(400,Yii::t('friends','can not make friends with him or her'));
		}
	}
}