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
	
	public function init(){
		if ( $this->uploadDir === null ){
			$this->uploadDir = Yii::app()->basePath.DS.'..'.DS.'upload'.DS.'trends'.DS;
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
		$dir = $this->resolveDirName($publishTime);
		$fileName = $this->resolveFileName(array($uid,$publishTime),$picFile);
		
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
	public function resolveDirName($time,$createIfNotDir=true){
		$dir = $this->uploadDir.DS.date('Ymd',$time).DS;
		if ( $createIfNotDir === true && !is_dir($dir) ){
			mkdir($dir,0777);
		}
		return $dir;
	}
	
	/**
	 * 
	 * @param mixed $dependence
	 * @param CUploadedFile $file
	 * @return string
	 */
	public function resolveFileName($dependence,$file){
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
			$dir = $this->resolveDirName($trend->publish_time);
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
	public function findMyTrends($uid){
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
			foreach ( $pics as $pic ){
				$trends[$key]['data']['pics'][] = $pic->getAttribute('url');
			}
			
			$replies = $myTrend->getRelated('replies');
			foreach ( $replies as $i => $reply ){
				$trends[$key]['replies'][$i]['content'] = $reply->getAttribute('content');
				$trends[$key]['replies'][$i]['user'] = $reply->getRelated('user')->getAttribute('nickname');
			}
		}
		
		return $trends;
	}
}