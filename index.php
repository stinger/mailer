<?php
//
	//session_start();

// 	Setup environmnent
	error_reporting(E_ALL|E_STRICT);
	ini_set('display_errors', true);
	date_default_timezone_set('Europe/Sofia');

// 	Set the Paths
	$rootDir = dirname(__FILE__);
	define('ROOT_DIR', $rootDir);
 	set_include_path($rootDir . '/library' . PATH_SEPARATOR . get_include_path());

	require_once 'Zend/Loader/Autoloader.php';
	$autoloader = Zend_Loader_Autoloader::getInstance();
	$autoloader->registerNamespace('Mailer_')->registerNamespace('forms_');
	
// 	load configuration
	$section = getenv('MAILER_CONFIG') ? getenv('MAILER_CONFIG') : 'live';
	$config = new Zend_Config_Ini('library/config.ini', $section);
	Zend_Registry::set('config', $config);
	

// 	Setup controller
	$frontController = Zend_Controller_Front::getInstance();
	$frontController->registerPlugin(new Mailer_ModelsPathPlugin());
	$frontController->registerPlugin(new Mailer_AjaxCheckPlugin());
	$frontController->registerPlugin(new Mailer_DBACLPlugin());

// 	Routes
	$router = $frontController->getRouter();

// 	Add your routes here
	//$router->addRoute('post', new Zend_Controller_Router_Route('post/:id', array('controller' => 'index', 'action' => 'readpost', 'id' => null), array('id'=>'\d+')));

// 	set up database
	$db = Zend_Db::factory($config->adapter, $config->database->toArray());
	Zend_Db_Table::setDefaultAdapter($db);
	$db->query('SET NAMES '.$config->charset);
	Zend_Registry::set('db', $db);


// 	Remove the following line in production
	$frontController->throwExceptions(true);

	$frontController->setBaseUrl('/mailer/');
	$frontController->addModuleDirectory('application/modules');

	$view = new Zend_View();
	$view->doctype('XHTML1_STRICT');
	$view->setEncoding('UTF-8');
	$view->baseUrl = $frontController->getBaseUrl();


// 	layout to be determined by the AjaxCheckPlugin, see library directory for details :)
	$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

// 	Paginator setup
	Zend_Paginator::setDefaultScrollingStyle('Sliding');
	Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginator.phtml');


// 	Run the dispatcher
	$frontController->returnResponse(true);
	$response = $frontController->dispatch();

// 	Output in UTF-8
	
	if ($view->layout()->isEnabled())
	{
		if ($view->layout()->getLayout() == 'common')
		{
			$response->setHeader("Content-Type", "text/html; charset=UTF-8", true);
		}
		elseif($view->layout()->getLayout() == 'ajax')
		{
			$response->setHeader("Content-Type", "text/xml; charset=UTF-8", true);
		}
		elseif($view->layout()->getLayout() == 'xml')
		{
			$response->setHeader("Content-Type", "text/xml; charset=UTF-8", true);
		}
	}
	$response->sendresponse();


?>