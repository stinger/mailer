<?php
class FilesService
{
	public function listFiles()
	{
		Zend_Loader::loadClass('Files');
		$files = new Files();
		$list = $files->listFiles('public/files/uploaded/');
		sort($list);
		$all=array();
		foreach ($list as $filename)
		{
			$row['name']=$filename;
			$row['filesize'] = filesize('public/files/uploaded/'.$filename);
			array_push($all, $row);
		}
		return $all;
	}
}