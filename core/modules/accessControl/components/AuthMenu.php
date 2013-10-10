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
		
		$menus = array();
		$topMenuChildrenMap = array();
		$user = Yii::app()->getUser();
		foreach ( $topMenus as $topCount => $topMenu ){
			$topMenuChildrenMap[$topCount] = false;
			$childrenTree = array_values($model->findChildrenInPreorder($topMenu));
			$count = count($childrenTree)-1;
			while ( $count > 0 ){
				$record = $childrenTree[$count]['record'];
				$opKey = 'op'.$record->getPrimaryKey();
				$level = $record->getAttribute('level');
				
				$operation = array(
						'module' => $record->getAttribute('module'),
						'controller' => $record->getAttribute('controller'),
						'action' => $record->getAttribute('action')
				);
				
				if ( $level > $deepth ){
					if ( isset($opIds[$opKey]) ){
						$operationKey = $user->generateOperationKey($operation);
						$user->cacheAccess($operationKey,true);
					}
					unset($childrenTree[$count]);
				}elseif ( !isset($opIds[$opKey]) ){
					unset($childrenTree[$count]);
				}else {
					$topMenuChildrenMap[$topCount] = true;
					$operationKey = $user->generateOperationKey($operation);
					$user->cacheAccess($operationKey,true);
				}
				--$count;
			}
			$menus = array_merge($menus,$childrenTree);
		}
		
		foreach ( $topMenuChildrenMap as $count => $value ){
			if ( $value === false ){
				unset($menus[$count]);
			}
		}
		
		return $menus;
	}
}