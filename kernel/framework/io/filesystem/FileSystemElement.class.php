<?php
/*##################################################
 *                      file_system_element.class.php
 *                            -------------------
 *   begin                : July 06, 2008
 *   copyright            : (C) 2008 Nicolas Duhamel, Loic Rouchon
 *   email                : akhenathon2@gmail.com, loic.rouchon@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @package {@package}
 * @abstract
 * @author Benoît Sautel <ben.popeye@phpboost.com> Nicolas Duhamel <akhenathon2@gmail.com>
 * @desc This class represents any file system element.
 */
abstract class FileSystemElement
{
	/**
	 * @var string Path of the file system element
	 */
	private $path;

	/**
	 * @desc Builds a FileSystemElement object from the path of the element.
	 * @param string $path Path of the element
	 */
	protected function __construct($path)
	{
		$this->path = Path::uniformize_path($path);
	}

	/**
	 * @desc Allows you to know if the file system element exists.
	 * @return bool True if the file exists, else, false.
	 */
	public function exists()
	{
		return file_exists($this->path);
	}

	/**
	 * @desc Returns the element full path.
	 * @return string The element full path.
	 */
	public function get_path()
	{
		return $this->path;
	}

	/**
	 * @desc Returns the element path from the phpboost root.
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
	 * @desc Returns the element name.
	 * @return string The element name.
	 */
	public function get_name()
	{
		if (strpos($this->path, '/') !== false)
		{
			$parts = explode('/', trim($this->path, '/'));
			return $parts[count($parts) - 1];
		}
		return $this->path;
	}

	/**
	 * @desc Returns true if the file or the folder is writable.
	 * @param bool $force_chmod If true, then, chmod will be forced to 777 if not writable.
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
			return $folder->change_chmod(0777) && @is_writable($this->path);
		}
		return false;
	}

	/**
	 * @desc Changes the chmod of the element.
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
	 * @desc Deletes the element
	 */
	public abstract function delete();
}
?>