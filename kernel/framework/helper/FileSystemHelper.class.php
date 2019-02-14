<?php
/**
 * File System helper
 * @package     Helper
 * @copyright   &copy; 2005-2019 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.2 - last update: 2018 02 14
 * @since       PHPBoost 5.2 - 2018 02 14
*/

class FileSystemHelper
{
	/**
	 * Remove a folder and its content. The folder can be kept and only emptied.
	 * @param string $folder The name of the folder to delete.
	 * @param bool $protect_folder True if the original folder has to be kept. False per default.
	 * @param string $first_folder_name The name of the first folder if it has to be kept. No need to fill it, used for recursive matter.
	 * @param string $parent_folder The name of the parent folder. No need to fill it, used for recursive matter.
	 * @return bool True if the folder is successfully removed, otherwise false.
	 */
	public static function remove_folder($folder, $protect_folder = false, $first_folder_name = '', $parent_folder = '')
	{
		if ($protect_folder && empty($first_folder_name))
			$first_folder_name = $folder;
		if (empty($parent_folder))
			$parent_folder = $folder;
		if (!preg_match( "/^.*\/$/", $folder))
			$folder .= '/';
		
		$handle = @opendir($folder);
		
		if ($handle != false)
		{
			while ($item = readdir($handle))
			{
				$protect_folder_delete = $protect_folder ? !preg_match('/' . $first_folder . '/', $item) : true;
				
				if ($item != "." && $item != ".." && $protect_folder_delete)
				{
					$full_path = $folder . $item;
					if (is_dir($full_path))
						self::remove_folder($full_path, $protect_folder, $first_folder_name, $parent_folder);
					else
						unlink($full_path);
				}
			}
			
			closedir ($handle);
			
			$protect_folder_delete = $protect_folder ? !preg_match('/' . $first_folder . '/', $folder) : true;
			if ($protect_folder_delete)
				$result = rmdir($folder);
		}
		else
			$result = false;
		
		return $result;
	}

	/**
	 * Copy a folder from a source path to a destination.
	 * @param string $source_path Source path of the folder.
	 * @param string $destination_path Destination path of the folder.
	 */
	public static function copy_folder($source_path, $destination_path)
	{
		if (!preg_match( "/^.*\/$/", $source_path))
			$source_path .= '/';
		
		if (!preg_match( "/^.*\/$/", $destination_path))
			$destination_path .= '/';
		
		if (is_dir($source_path))
		{
			if ($dh = opendir($source_path))
			{
				while (($file = readdir($dh)) !== false)
				{
					if (!is_dir($destination_path))
						mkdir($destination_path, 0755);
					
					if (is_dir($source_path . $file) && $file != '..'  && $file != '.')
						self::copy_folder($source_path . $file . '/', $destination_path . $file . '/');
					elseif ($file != '..'  && $file != '.')
						copy($source_path . $file, $destination_path . $file);
				}
				
				closedir ($dh);
			}
		}
	}
}
?>
