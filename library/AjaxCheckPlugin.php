<?php
class AjaxCheckPlugin extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$rootDir = dirname(dirname(__FILE__));
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        	$viewRenderer->init();

		$view = $viewRenderer->view;
		$this->_view = $view;

		// set up common variables for the view
		$view->module = $request->getModuleName();
		$view->controller = $request->getControllerName();
		$view->action = $request->getActionName();
	
		//If the request is an XHR, render Ajax layout.
		if($request->isXmlHttpRequest())
		{
			Zend_Layout::startMvc(
			array(
				'layoutPath' => $rootDir . '/application/modules/default/views/layouts',
				'layout' => 'ajax'
			)
			);
		}
		// Render default layout
		else
		{
			//var_dump($this->_view->layout()->isEnabled());
			Zend_Layout::startMvc(
			array(
				'layoutPath' => $rootDir . '/application/modules/default/views/layouts',
				'layout' => 'common'
			)
			);
		}
	}
}
?>