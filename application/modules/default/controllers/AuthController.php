<?php
class AuthController extends Zend_Controller_Action
{

	protected function _getAuthAdapter($formData)
	{
		Zend_Loader::loadClass('Zend_Auth_Adapter_DbTable');
		$dbAdapter = Zend_Registry::get('db');
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
		$authAdapter->setTableName('users')
		            ->setIdentityColumn('username')
			    ->setCredentialColumn('password');
		// get "salt" for better security
		$config = Zend_Registry::get('config');
		$salt = $config->auth->salt;
		$password = sha1($salt.$formData['password']);
		$authAdapter->setIdentity($formData['username']);
		$authAdapter->setCredential($password);
		return $authAdapter;
	}

	public function indexAction()
	{
		$this->_forward('login');
	}

	public function loginAction()
	{
		$form = new forms_LoginForm();
		$form->setAction($this->view->url(array('controller'=>'auth','action'=>'identify'),'default'));
		$this->view->form = $form;
		$flashMessenger = $this->_helper->FlashMessenger;
		$flashMessenger->setNamespace('actionErrors');
		$this->view->errors = $flashMessenger->getMessages();
	}

	public function identifyAction()
	{
		$success = false;
		$message = '';
		if ($this->getRequest()->isPost())
		{
			// collect the data from the user
			$formData = $this->getRequest()->getParams();
			
			if (empty($formData['username']) || empty($formData['password']))
			{
				$message = 'Please provide a username and password.';
			}
			else
			{
				// do the authentication
				$authAdapter = $this->_getAuthAdapter($formData);
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);
				if ($result->isValid())
				{
					// success: store database row to auth's storage
					// (Not the password though!)
					$data = $authAdapter->getResultRowObject(null,'password');
					$auth->getStorage()->write($data);
					$success = true;
					$db = Zend_Registry::get('db');
					$role_id = $db->fetchRow("SELECT role_name FROM roles WHERE id={$data->role_id}");
					$user = new Zend_Session_Namespace('UserData');
					$user->role_id = $role_id['role_name'];
					$redirectUrl = $this->_redirectUrl;
					
					
				}
				else
				{
					$message = 'Login failed';
				}
			}
		}
		if(!$success) 
		{
			$flashMessenger = $this->_helper->FlashMessenger;
			$flashMessenger->setNamespace('actionErrors');
			$flashMessenger->addMessage($message);
			$redirectUrl = '/auth/login';
		}
		$this->_redirect($redirectUrl);
	}


	public function logoutAction()
	{
		$auth = Zend_Auth::getInstance();
		$auth->clearIdentity();
		$user = new Zend_Session_Namespace('UserData');
		$user->role_id = null;
		$this->_redirect('/');
	}
}
?>