<?php

class Zend_View_Helper_LinkTo
{
	public $view;
	protected static $_baseurl = null;
/**
  * Constructor
  */

	public function __construct()
	{
		if (null === self::$_baseurl)
		{
			$url = Zend_Controller_Front::getInstance()->getRequest()->getBaseUrl();
			$root = '/' . trim($url, '/');
			if ('/' == $root)
			{
				$root = '';
			}
			self::$_baseurl = $root . '/';
		}
	}


      /**
	* Output the <img> tag
	*
	* @param string $path
	* @param array $params
	* @return string
	*/

	public function linkTo($path, $title, $params = array())
	{
		$plist = array();
		$paramstr = null;
		$imagepath = self::$_baseurl . ltrim($path, '/');
		
		foreach ($params as $param => $value)
		{
			$plist[] = $param . '="' . $this->view->escape($value) . '"';
		}

		$paramstr = ' ' . join(' ', $plist);

		return '<a href="' . self::$_baseurl . ltrim($path, '/') . '"' . $paramstr . '>' . $title . '</a>';
	}


      /**
	* Set the view object
	*
	* @param Zend_View_Interface $view
	* @return void
	*/

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}

?>
