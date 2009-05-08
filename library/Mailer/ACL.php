<?php
class Mailer_ACL extends Zend_Acl {
 
	protected static $_instance = null;
 
	private function __construct()
	{}
 
	private function __clone()
	{}
 
	protected function _initialize()
	{
		$this->add(new Zend_Acl_Resource('default_auth'));    // AuthController
		$this->add(new Zend_Acl_Resource('default_error'));   // ErrorController
		$this->add(new Zend_Acl_Resource('admin_error'));   // ErrorController
		$this->add(new Zend_Acl_Resource('default_gateway'));   // AMF GatewayController
 
		$db = Zend_Db_Table::getDefaultAdapter();
		$roles = $db->fetchAll("SELECT roles.role_name, resources.module_name, resources.controller_name, resources.action_name, roles_resources_permissions.allowed FROM roles INNER JOIN roles_resources_permissions ON roles_resources_permissions.role_id = roles.id INNER JOIN resources ON resources.id = roles_resources_permissions.resource_id");

		$this->deny();
 
		foreach ($roles as $role)
		{
			if (!$this->has($role['module_name'].'_'.$role['controller_name'])) {
				$this->add(new Zend_Acl_Resource($role['module_name'].'_'.$role['controller_name']));
			}
			if (!$this->hasRole($role['role_name'])) {
				$this->addRole(new Zend_Acl_Role($role['role_name']));
				$this->allow($role['role_name'],'default_error');
				$this->allow($role['role_name'],'admin_error');
				$this->allow($role['role_name'],'default_auth');
				$this->allow($role['role_name'],'default_gateway');
			}
		}
		foreach ($roles as $role)
		{
			if ($role['allowed'] == 1)
			{
				$this->allow($role['role_name'],$role['module_name'].'_'.$role['controller_name'],$role['action_name']);
			}
		}
	}
 
	public static function getInstance()
	{
	   if (null === self::$_instance) {
		self::$_instance = new self();
		self::$_instance->_initialize();
	   }
 
	   return self::$_instance;
	}
 
} 
