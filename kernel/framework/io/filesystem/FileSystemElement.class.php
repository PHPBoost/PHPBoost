<?php
/**
 * This class represents any file system element.
 * @package     IO
 * @subpackage  Filesystem
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Nicolas Duhamel <akhenathon2@gmail.com>
 * @version     PHPBoost 6.0 - last update: 2022 03 28
 * @since       PHPBoost 2.0 - 2008 07 06
 * @contributor Loic ROUCHON <horn@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor ph-7 <me@ph7.me>
*/

abstract class FileSystemElement
{
	/**
	 * @var string Path of the file system element
	 */
	protected $path;

	/**
	 * Builds a FileSystemElement object from the path of the element.
	 * @param string $path Path of the element
	 */
	protected function __construct($path)
	{
		$this->path = Path::uniformize_path($path);
	}

	/**
	 * Allows you to know if the file system element exists.
	 * @return bool True if the file exists, else, false.
	 */
	public function exists()
	{
		return file_exists($this->path);
	}

	/**
	 * Returns the element full path.
	 * @return string The element full path.
	 */
	public function get_path()
	{
		return $this->path;
	}

	/**
	 * Returns the element path from the phpboost root.
	 * @return string The element from the phpboost root.
	 */
	public function get_path_from_root()
	{
		$path_from_root = Path::get_path_from_root($this->path);
		if (empty($path_from_root))
		{
			return $this->path;
		}
		return $path_from_root;
	}

	/**
	 * Returns the element name.
	 * @return string The element name.
	 */
	public function get_name()
	{
		if (mb_strpos($this->path, '/') !== false)
		{
			$parts = explode('/', trim($this->path, '/'));
			return $parts[count($parts) - 1];
		}
		return $this->path;
	}

	/**
	 * Returns true if the file or the folder is writable.
	 * @param bool $force_chmod If true, then, chmod will be forced to 755 if not writable.
	 * @return true if the file or the folder is writable.
	 */
	public function is_writable($force_chmod = false)
	{
		if (!$this->exists())
		{
			return false;
		}
		else if (@is_writable($this->path))
		{
			return true;
		}
		else if ($force_chmod)
		{
			return $this->change_chmod(0755) && @is_writable($this->path);
		}
		return false;
	}

	/**
	 * Changes the chmod of the element.
	 * @param int $chmod The new chmod of the file. Put a 0 at the begening of the number to indicate to the PHP parser that it's an octal value.
	 * @return true if the chmod has been successfully changed.
	 */
	public function change_chmod($chmod)
	{
		if (!empty($this->path))
		{
			return chmod($this->path, $chmod);
		}
		return false;
	}

	/**
	 * @abstract
	 * Deletes the element
	 */
	public abstract function delete();
}
?>
