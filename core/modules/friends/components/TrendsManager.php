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
	public function findUserTrends($uid){
		$criteria = new CDbCriteria();
		$criteria->alias = 'trends';
		$criteria->condition = 'trends.user_id='.$uid;
		
		$myTrends = UserTrends::model()->with(array(
				'pics',
				'replies' => array(
						'select' => 'id,content',
						'with' => array(
								'user' => array(
										'select' => 'nickname',
								)
						)
				)
		))->findAll($criteria);
		$trends = array();
		foreach ( $myTrends as $key => $myTrend ){
			$trends[$key]['data'] = $myTrend->getAttributes();
			$trends[$key]['replies'] = array();
			
			$pics = $myTrend->getRelated('pics');
			$picUrl = $this->resolvePicUrl($myTrend->getAttribute('publish_time'));
			foreach ( $pics as $pic ){
				$trends[$key]['data']['pics'][] = $picUrl.$pic->getAttribute('url');
			}
			
			$replies = $myTrend->getRelated('replies');
			foreach ( $replies as $i => $reply ){
				$trends[$key]['replies'][$i]['content'] = $reply->getAttribute('content');
				$trends[$key]['replies'][$i]['user'] = $reply->getRelated('user')->getAttribute('nickname');
			}
		}
		
		return $trends;
	}
	
	/**
	 * 
	 * @param BaseUserManager $userManager
	 * @param int $uid
	 * @return array
	 */
	public function findFriendsTrends($userManager,$uid){
		$criteria = array(
				'select' => 'id' ,
				'with' => array(
						'baseUser' => array(
								'select' => 'id,nickname',
								'with' => array(
										'friends' => array(
												'select' => 'remark',
												'with' => array(
														'followed' => array(
																'alias' => 'friend',
																'select' => 'friend.id,friend.nickname',
																'with' => array(
																		'frontUser' => array(
																				'alias' => 'frontUser',
																				'select' => 'frontUser.icon'
																		),
																		'trends' => array(
																				'alias' => 'trends',
																				'with' => array(
																						'pics',
																						'replies' => array(
																								'order' => 'time DESC',
																								'with' => array(
																										'user' => array(
																												'alias' => 'reply_user' ,
																												'select' => 'reply_user.nickname',
																										)
																								)
																						),
																				),
																				'order' => 'publish_time DESC'
																		),
																),
														),
												),
										),
								),
						),
				),
		);
		
		$user = $userManager->findByPk($uid,$criteria);
		if ( $user === null ){
			return array();
		}
		
		$data = array();
		$friends = $user->getRelated('baseUser')->getRelated('friends');
		foreach ( $friends as $count => $friend ){
			$f = $friend->getRelated('followed');
			$data[$count]['userInfo'] = array(
					'id' => $f->getAttribute('id'),
					'nickname' => $f->getAttribute('nickname'),
					'icon' => $f->getRelated('frontUser')->getAttribute('icon'),
					'remark' => $friend->getAttribute('remark')
			);
			
			$trends = $f->getRelated('trends');
			$data[$count]['trends'] = array();
			foreach ( $trends as $tCount => $trend ){
				$data[$count]['trends'][$tCount] = array(
						'id' => $trend->getPrimaryKey(),
						'content' => $trend->getAttribute('content'),
						'publish_time' => $trend->getAttribute('publish_time')
				);
				
				$data[$count]['trends'][$tCount]['pics'] = array();
				$pics = $trend->getRelated('pics');
				$picUrl = $this->resolvePicUrl($trend->getAttribute('publish_time'));
				foreach ( $pics as $pic ){
					$data[$count]['trends'][$tCount]['pics'][] = $picUrl.$pic->getAttribute('url');
				}
				
				$data[$count]['trends'][$tCount]['replies'] = array();
				$replies = $trend->getRelated('replies');
				foreach ( $replies as $reply ){
					$replyData = $reply->getAttributes();
					$replyData['nickname'] = $reply->getRelated('user')->getAttribute('nickname');
					$data[$count]['trends'][$tCount]['replies'][] = $replyData;
				}
			}
		}
		return $data;
	}
	
	/**
	 * 
	 * @param UserTrends $trend
	 * @param string $content
	 * @return boolean return array if error
	 */
	public function reply($trend,$content){
		$data = array(
				'user_id' => $trend->getAttribute('user_id'),
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