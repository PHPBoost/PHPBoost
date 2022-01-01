<?php
/**
 * This class allows you to handle very easily a folder on the server.
 * @package     IO
 * @subpackage  Filesystem
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Nicolas Duhamel <akhenathon2@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 07 06
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Benoit SAUTEL <ben.popeye@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class Folder extends FileSystemElement
{
	private $opened = false;

	/**
	 * @var File[] List of the files contained by this folder.
	 */
	private $files = array();
	/**
	 * @var Folder[] List of the folders contained by this folder.
	 */
	private $folders = array();

	/**
	 * Builds a Folder object.
	 * @param string $path Path of the folder.
	 */
	public function __construct($path)
	{
		parent::__construct(rtrim($path, '/'));
	}

	/**
	 * Returns true if the folder exists after this call, else, false
	 * @return bool true if the folder exists after this call, else, false
	 */
	public function create()
	{
		$path = $this->get_path();
		if (@file_exists($path))
		{
			if (!@is_dir($path))
			{
				return false;
			}
		}
		else if (!@mkdir($path))
		{
			return false;
		}
		return true;
	}

	/**
	 * Opens the folder.
	 */
	private function open()
	{
		if (!$this->opened)
		{
			$this->files = array();
			$this->folders = array();
			$path = $this->get_path();
			if ($dh = @opendir($path))
			{
				while (!is_bool($fse_name = readdir($dh)))
				{
					if ($fse_name == '.' || $fse_name == '..')
					{
						continue;
					}

					$file = $path . '/' . $fse_name;
					if (!is_link($file))
					{
						if (is_dir($file))
						{
							$this->folders[] = new Folder($file);
						}
						else
						{
							$this->files[] = new File($file);
						}
					}
				}
				closedir($dh);
			}
			else
			{
				throw new IOException('Can\'t open folder : ' . $this->get_path());
			}
			$this->opened = true;
		}
	}

	/**
	 * Lists the files contained in this folder.
	 * @param string $regex PREG which describes the pattern the files you want to list must match. If you want all of them, don't use this parameter.
	 * @param bool $regex_exclude_files true if the regex to exclude files
	 * @return File[] The files list.
	 */
	public function get_files($regex = '', $regex_exclude_files = false)
	{
		$this->open();
		if (empty($regex))
		{
			return $this->files;
		}

		$files = array();
		foreach ($this->files as $file)
		{
			if ($regex_exclude_files)
			{
				if (!preg_match($regex, $file->get_name()))
				{
					$files[] = $file;
				}
			}
			else
			{
				if (preg_match($regex, $file->get_name()))
				{
					$files[] = $file;
				}
			}

		}
		return $files;
	}

	/**
	 * Lists the folders contained in this folder.
	 * @param string $regex PREG which describes the pattern the folders you want to list must match. If you want all of them, don't use this parameter.
	 * @return Folder[] The folders list.
	 */
	public function get_folders($regex = '')
	{
		$this->open();
		if (empty($regex))
		{
			return $this->folders;
		}

		$folders = array();
		foreach ($this->folders as $folder)
		{
			if (preg_match($regex, $folder->get_name()))
			{
				$folders[] = $folder;
			}
		}
		return $folders;
	}

	/**
	 * Returns the first folder present in this folder
	 * @return Folder The first folder of this folder or null if it doesn't contain any folder.
	 */
	public function get_first_folder()
	{
		$this->open();
		if (isset($this->folders[0]))
		{
			return $this->folders[0];
		}
		else
		{
			return null;
		}
	}

	/**
	 * Returns all the file system elements contained by the folder.
	 * @return FileSystemElement[] The list of the file system element contained in this folder.
	 */
	public function get_all_content()
	{
		return array_merge($this->get_files(), $this->get_folders());
	}

	/**
	 * Deletes the folder and all what it contains.
	 * @return True if deleted successfully.
	 */
	public function delete()
	{
		$fs = $this->get_all_content();

		foreach ($fs as $fse)
		{
			$fse->delete();
		}

		if (!@rmdir($this->get_path()) && !file_exists($this->get_path()))
		{
			throw new IOException('The folder ' . $this->get_path() . ' couldn\'t been deleted');
		}
	}

	/**
	 * Returns the date of the last modification of the folder.
	 * @return int The UNIX timestamp corresponding to the last modification date.
	 */
	public function get_last_modification_date()
	{
		$folder_infos = stat($this->get_path());
		return $folder_infos['mtime'];
	}
}
?>
