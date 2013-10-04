<?php
/**
 * @name AccessControlFilter.php UTF-8
 * @author ChenJunAn<lancelot1215@gmail.com>
 * 
 * Date 2013-10-4
 * Encoding UTF-8
 */
class AccessControlFilter extends CAccessControlFilter{
	protected function preFilter($filterChain)
	{
		$app=Yii::app();
		$request=$app->getRequest();
		$user=$app->getUser();
		$verb=$request->getRequestType();
		$ip=$request->getUserHostAddress();
		
		foreach($this->getRules() as $rule)
		{
			if(($allow=$rule->isUserAllowed($user,$filterChain->controller,$filterChain->action,$ip,$verb))>0){ // allowed
				return true;
			}elseif($allow<0) // denied
			{
				if(isset($rule->deniedCallback))
					call_user_func($rule->deniedCallback, $rule);
				else
					$this->accessDenied($user,$this->resolveErrorMessage($rule));
				return false;
			}
		}
	
		return false;
	}
}