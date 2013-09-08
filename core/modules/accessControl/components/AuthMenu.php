<?php
/**
 * @name AuthMenu.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-9-5
 * Encoding UTF-8
 */
class AuthMenu extends CApplicationComponent{
	/**
	 * generate a menu to a user
	 * @param int $uid
	 * @param int $deepth
	 */
	public function generateUserMenu($uid,$deepth=2){
		$calculator = Yii::app()->getAuthManager()->getCalculator();
		$userPermissions = $calculator->getFinalPermissions($uid);
		if ( empty($userPermissions) ){
			return array();
		}
		
	}
}