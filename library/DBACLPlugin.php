<?php
 
class DBACLPlugin extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
		Zend_Session::start();
		$user = new Zend_Session_Namespace('UserData');
		$role_id = $user->role_id;
		if (!$role_id)
		{
			$userRoleId = 'guest';
		}
		else
		{
			$userRoleId = $role_id;
		}
		Zend_Registry::set('userRoleId',$userRoleId);

		$acl = MailerACL::getInstance();
		$request = $this->getRequest();

		if (!$acl->hasRole($userRoleId)) {
			$error = "Sorry, the requested user role '".$userRoleId."' does not exist";
		}
		if (!$acl->has($request->getModuleName().'_'.$request->getControllerName())) {
			$error = "Sorry, the requested controller '".$request->getControllerName()."' does not exist as an ACL resource";
 		}
		try
		{
			$ok = $acl->isAllowed($userRoleId, $request->getModuleName().'_'.$request->getControllerName(), $request->getActionName());
			if (!$ok)
			{
				if ($userRoleId == 'guest')
				{
					$request->setControllerName('auth');
					$request->setActionName('login');
					$request->setDispatched(false);
				}
				else
				{
					$request->setControllerName('error');
					$request->setActionName('forbidden');
					$request->setDispatched(false);
				}
			}
			
		}
		catch (Zend_Exception $e)
		{
			$error = "Sorry, the page you requested does not exist";
		}
		
 
		if (isset($error)) {
			Zend_Layout::getMvcInstance()->getView()->error = $error;
			$request->setControllerName('error');
			$request->setActionName('error');
			$request->setDispatched(false);
		}
 
    }
 
} 
?>