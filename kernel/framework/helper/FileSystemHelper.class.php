<?php
/**
 * File System helper
 * @package     Helper
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 06 15
 * @since       PHPBoost 5.2 - 2019 02 14
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

	/**
	 * Download a file from a remote url with md5 sum check and extract it if needed in case of a zip file. Curl library needed.
	 * @param string $url Url of the remote file.
	 * @param string $destination_path Destination path of the file.
	 * @param string $extract_archive Extract the content of the zip file and delete the zip file if true.
	 * @param string $retry Second try if the archive is corrupted. No need to use this parameter, it is used automatically by the function.
	 * @return bool True if the file is successfully downloaded, otherwise false.
	 */
	public static function download_remote_file($url, $destination_path, $extract_archive = true, $retry = false)
	{
		if (!preg_match( "/^.*\/$/", $destination_path))
			$destination_path .= '/';
		
		$server_configuration = new ServerConfiguration();
		
		if ($server_configuration->has_curl_library() && Url::check_url_validity($url))
		{
			$file_basename = basename($url);
			$file_extension = substr($file_basename, strrpos($file_basename, '.') + 1);
			$file_name = PATH_TO_ROOT . '/cache/' . $file_basename;
			
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$content = curl_exec($ch);
			curl_close($ch);
			file_put_contents($file_name, $content);
			
			$file = new File($file_name);
			if ($file->exists())
			{
				if (File::get_file_checksum($url) != File::get_file_checksum($file_name))
				{
					if (!$retry)
						FileSystemHelper::download_remote_file($url, $destination_path, $extract_archive, true);
					else
						return false;
				}
				
				if ($extract_archive && $file_extension == 'zip')
				{
					if ($server_configuration->has_zip_library())
					{
						$zip_archive = new ZipArchive();
						if ($zip_archive->open($file_name))
						{
							$zip_archive->extractTo($destination_path);
							$zip_archive->close();
						}
						else
							return false;
					}
					else
					{
						include_once(PATH_TO_ROOT . '/kernel/lib/php/pcl/pclzip.lib.php');
						$zip = new PclZip($file_name);
						$zip->extract(PCLZIP_OPT_PATH, $destination_path, PCLZIP_OPT_SET_CHMOD, 0755);
					}
					unlink($file_name);
				}
				
				return true;
			}
			return false;
		}
		return false;
	}
}
?>
