<?php
	class GatewayController extends Zend_Controller_Action
	{
		public function indexAction()
		{
			$this->_helper->layout->setLayout('json');
			$this->getHelper('ViewRenderer')->setNoRender();
			$server = new Zend_Amf_Server();
			$server->addDirectory( dirname(__FILE__) . '/../services/' );
			echo($server->handle());
		}
	}