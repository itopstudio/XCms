<?php
/**
 * @name TrendsManager.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-19
 * Encoding UTF-8
 */
class TrendsManager extends CApplicationComponent{
	public $uploadDir = null;
	public $uploadUrl = null;
	
	public function init(){
		if ( $this->uploadDir === null ){
			$this->uploadDir = Yii::app()->basePath.DS.'..'.DS.'upload'.DS.'trends'.DS;
		}
		if ( $this->uploadUrl === null ){
			$this->uploadUrl = Yii::app()->baseUrl.'/upload/trends/';
		}
	}
	
	public function publish($data=array()){
		$publishTime = time();
		$data['publish_time'] = $publishTime;
		$userTrends = new UserTrends();
		$userTrends->attributes = $data;
		
		if ( $userTrends->save() ){
			$save = $this->savePic($userTrends);
			if ( $save === true ){
				return true;
			}else {
				$userTrends->delete();
				return $save;
			}
			return true;
		}else {
			return $userTrends->getErrors();
		}
	}
	
	/**
	 * 
	 * @param UserTrends $trend
	 * @return boolean
	 */
	public function savePic($trend){
		$picFile = CUploadedFile::getInstanceByName('pic');
		if ( $picFile === null ){
			return true;
		}
		if ( $picFile->getHasError() ){
			return $picFile->getError();
		}
		$uid = $trend->getAttribute('user_id');
		$publishTime = $trend->getAttribute('publish_time');
		$dir = $this->resolvePicDirName($publishTime);
		$fileName = $this->resolvePicFileName(array($uid,$publishTime),$picFile);
		
		$attributes = array(
				'msg_id' => $trend->getPrimaryKey(),
				'url' => $fileName
		);
		$trendPic = new UserTrendsPic();
		$trendPic->attributes = $attributes;
		if ( $trendPic->save() ){
			if ( $picFile->saveAs($dir.$fileName) ){
				return true;
			}else {
				$trendPic->delete();
				return $picFile->getError();
			}
		}else {
			return $trendPic->getErrors();
		}
	}
	
	/**
	 * 
	 * @param int $time
	 * @param string $createIfNotDir
	 * @return string
	 */
	public function resolvePicDirName($time,$createIfNotDir=true){
		$dir = $this->uploadDir.DS.date('Ymd',$time).DS;
		if ( $createIfNotDir === true && !is_dir($dir) ){
			mkdir($dir,0777);
		}
		return $dir;
	}
	
	/**
	 *
	 * @param int $time
	 * @return string
	 */
	public function resolvePicUrl($time){
		return $this->uploadUrl.'/'.date('Ymd',$time).'/';
	}
	
	/**
	 * 
	 * @param mixed $dependence
	 * @param CUploadedFile $file
	 * @return string
	 */
	public function resolvePicFileName($dependence,$file){
		$extName = $file->getExtensionName();
		return md5(json_encode($dependence)).'.'.$extName;
	}
	
	/**
	 * 
	 * @param UserTrends $trend
	 * @return boolean
	 */
	public function delete($trend){
		$pics = $trend->getRelated('pics');
		foreach ( $pics as $pic ){
			$dir = $this->resolvePicDirName($trend->publish_time);
			unlink($dir.$pic->url);
		}
		$trend->delete();
		return true;
	}
	
	/**
	 * 
	 * @param int $uid
	 * @return array
	 */
	public function findUserTrends($uid,$pageSize){
		$criteria = new CDbCriteria();
		$trendModel = UserTrends::model();
		$criteria->alias = 'trends';
		$criteria->condition = 'trends.user_id='.$uid;
		
		$count = $trendModel->count($criteria);
		$pager = new CPagination($count);
		$pager->pageSize = $pageSize;
		$pager->applyLimit($criteria);
		
		$criteria->with = array(
				'pics',
				'replies' => array(
					'select' => 'id,content',
					'with' => array(
						'user' => array(
								'select' => 'nickname',
						)
					)
				)
		);
		$criteria->order = 'publish_time DESC';
		
		$trends = $trendModel->findAll($criteria);
		$return = array();
		foreach ( $trends as $key => $trend ){
			$return[$key]['data'] = $trend->getAttributes();
			$return[$key]['replies'] = array();
			
			$pics = $trend->getRelated('pics');
			$picUrl = $this->resolvePicUrl($trend->getAttribute('publish_time'));
			foreach ( $pics as $pic ){
				$return[$key]['data']['pics'][] = $picUrl.$pic->getAttribute('url');
			}
			
			$replies = $trend->getRelated('replies');
			foreach ( $replies as $i => $reply ){
				$return[$key]['replies'][$i]['content'] = $reply->getAttribute('content');
				$return[$key]['replies'][$i]['user'] = $reply->getRelated('user')->getAttribute('nickname');
			}
		}
		
		return $return;
	}
	
	/**
	 * 
	 * @param int $uid
	 * $param int $pageSize
	 * @return array
	 */
	public function findFriendsTrends($uid,$pageSize){
		$criteria = new CDbCriteria();
		$criteria->select = 'id';
		$criteria->with = array(
				'friends' => array(
					'select' => 'remark',
					'with' => array(
						'followed' => array(
							'alias' => 'followed',
							'select' => 'followed.id,followed.nickname',
							'with' => array(
								'frontUser' => array(
										'alias' => 'front',
										'select' => 'front.icon'
								)
							)
						)
					)
				)
		);
		$user = UserModel::model()->findByPk($uid,$criteria);
		if ( $user === null ){
			return array();
		}
		$friends = $user->getRelated('friends');
		if ( empty($friends) ){
			return array();
		}
		$friendMap = array();
		$friendIds = array();
		foreach ( $friends as $friend ){
			$followed = $friend->getRelated('followed');
			$friendId = $followed->getAttribute('id');
			$friendIds[] = $friendId;
			$friendMap['f'.$friendId] = array(
					'id' => $friendId,
					'icon' => $followed->getRelated('frontUser')->getAttribute('icon'),
					'nickname' => $followed->getAttribute('nickname'),
					'remark' => $friend->getAttribute('remark')
			);
		}
		
		$trendsModel = UserTrends::model();
		$criteria = null;
		$criteria = new CDbCriteria();
		$criteria->addInCondition('user_id',$friendIds);
		
		$count = $trendsModel->count($criteria);
		$pager = new CPagination($count);
		$pager->pageSize = $pageSize;
		$pager->applyLimit($criteria);
		
		$criteria->alias = 'trends';
		$criteria->with = array(
				'pics' => array(
					'select' => 'url'
				),
				'replies' => array(
					'order' => 'time DESC',
					'with' => array(
						'user' => array(
							'alias' => 'reply_user' ,
							'select' => 'reply_user.nickname',
						)
					)
				),
		);
		$criteria->order = 'publish_time DESC';
		
		$trends = $trendsModel->findAll($criteria);
		$data = array();
		foreach ( $trends as $trend ){
			$pics = $trend->getRelated('pics');
			$picData = array();
			$picUrl = $this->resolvePicUrl($trend->getAttribute('publish_time'));
			foreach ( $pics as $pic ){
				$picData[] = $picUrl.$pic->getAttribute('url');
			}
			
			$replies = $trend->getRelated('replies');
			$replyData = array();
			foreach ( $replies as $reply ){
				$user = $reply->getRelated('user');
				$replyData[] = array(
						'nickname' => $user->getAttribute('nickname'),
						'content' => $reply->getAttribute('content'),
						'time' => $reply->getAttribute('time'),
				);
			}
			
			$publisher = $trend->getAttribute('user_id');
			$data[] = array(
					'trend' => array(
						'id' => $trend->getAttribute('id'),
						'content' => $trend->getAttribute('content'),
						'publish_time' => $trend->getAttribute('publish_time')
					),
					'userInfo' => $friendMap['f'.$publisher],
					'replies' => $replyData,
					'pics' => $picData
			);
		}
		
		return $data;
	}
	
	/**
	 * 
	 * @param int $uid
	 * @param UserTrends $trend
	 * @param string $content
	 * @return boolean return array if error
	 */
	public function reply($uid,$trend,$content){
		$data = array(
				'user_id' => $uid,
				'trends_id' => $trend->getAttribute('id'),
				'content' => $content,
				'time' => time()
		);
		$reply = new UserTrendsReply();
		$reply->attributes = $data;
		
		if ( $reply->save() ){
			$replyNum = $trend->getAttribute('reply')+1;
			$trend->setAttribute('reply',$replyNum);
			$trend->save();
			return true;
		}else {
			return $reply->getErrors();
		}
	}
}