<?php
	class Files
	{
		public function listFiles($dirname)
		{
			$list = array();
			if ($handle = opendir($dirname))
			{
				/* This is the correct way to loop over the directory. */
				while (false !== ($file = readdir($handle)))
				{
					if (is_file($dirname.$file))
					{
						array_push($list, $file);
					}
				}
				closedir($handle);
			}
			sort($list);
			return $list;
		}
	}
?>