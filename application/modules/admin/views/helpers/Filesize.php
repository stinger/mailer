<?php

class Zend_View_Helper_Filesize
{
	public $view;
	protected static $_baseurl = null;
	protected static $_cache = null;
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
	* Output the formatted filesize
	*
	* @param int $bytes
	* @return string
	*/

	public function filesize($bytes)
	{
		$symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

		$exp = 0;
		$converted_value = 0;
		if( $bytes > 0 )
		{
			$exp = floor( log($bytes)/log(1024) );
			$converted_value = ( $bytes/pow(1024,floor($exp)) );
		}

		return sprintf( '%.2f '.$symbol[$exp], $converted_value );
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