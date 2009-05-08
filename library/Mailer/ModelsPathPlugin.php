<?php
class Mailer_ModelsPathPlugin extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)  
	{
		set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_DIR . '/application/modules/' . $request->getModuleName() . '/models');
	}
}
?>