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
		$userPermissions = $calculator->run($uid);
		if ( empty($userPermissions) ){
			return array();
		}
		foreach ( $userPermissions as $permission ){
			$opKey = 'op'.$permission->getAttribute('operation_id');
			$opIds[$opKey] = true;
		}
		
		$model = AuthOperation::model();
		$topMenus = $model->findChildrenByLevel(1);
		if ( $topMenus === null ){
			return array();
		}
		$menu = array();
		foreach ( $topMenus as $topMenu ){
			$childrenTree = $model->findChildrenInPreorder($topMenu);
			$count = count($childrenTree)-1;
			while ( $count >= 0 ){
				$opKey = 'op'.$childrenTree[$count]->getPrimaryKey();
				$level = $childrenTree[$count]->getAttribute('level');
				if ( $level > $deepth || !isset($opIds[$opKey]) ){
					unset($childrenTree[$count]);
				}
				++$count;
			}
			$menu = array_merge($menu,$childrenTree);
		}
		return $menu;
	}
}