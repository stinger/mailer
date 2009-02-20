<?php
class Zend_View_Helper_LoggedInUser
{
	protected $_view;

	function setView($view)
	{
		$this->_view = $view;
	}

	function loggedInUser()
	{
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity())
		{
			$logoutUrl = $this->_view->url(array('controller'=>'auth', 'action'=>'logout'), 'default');
			$user = $auth->getIdentity();
			$username = $this->_view->escape(ucfirst($user->username));
			$string = 'Welcome ' . $username . ' | <a href="' . $logoutUrl . '">Log out</a>';
		}
		else
		{
			$loginUrl = $this->_view->url(array('controller'=>'auth', 'action'=>'identify'), 'default');
			$string = $this->_view->partial('index/_form.phtml', array('baseUrl'=>$this->_view->baseUrl));
		}
		return $string;
	}
}
?>