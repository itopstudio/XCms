<?php
/**
 * @author lancelot <cja.china@gmail.com>
 * Date 2013-8-27
 * Encoding GBK 
 */
class AuthManagerTest extends CDbTestCase{
	/**
	 * @var AuthManager
	 */
	public $auth;
	
	protected function setUp(){
		parent::setUp();
		$this->auth = Yii::app()->getAuthManager();
	}
	
	public function testGenerateRole(){
		$data = array('fid' => 0,'role_name' => 'test role4');
		//$this->auth->generate(AuthManager::ROLE,$data);
	}
	
	public function testGenerateRecordsRole(){
		$data = array(
// 				array('fid' => 19,'role_name' => 'test role4'),
//  				array('fid' => 19,'role_name' => 'test role5'),
 				array('fid' => 20,'role_name' => 'test role6'),
 				array('fid' => 20,'role_name' => 'test role7'),
				array('fid' => 20,'role_name' => 'test role8'),
		);
		//$r = $this->auth->generateRecords($data,AuthManager::ROLE);
	}
	
	public function testUpdateLevelModel(){
		$role = AuthRoles::model()->findByPk(20);
		$role->fid = 0;
		$role->save();
	}
}