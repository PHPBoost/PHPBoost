<?php
/*##################################################
 *                               file.class.php
 *                            -------------------
 *   begin                : July 06, 2008
 *   copyright            : (C) 2008 Nicolas Duhamel, Benoit Sautel
 *   email                : akhenathon2@gmail.com, ben.popeye@phpboost.com
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

import('io/filesystem/file_system_element');

define('ERASE', false);
define('ADD', true);

define('READ', 0x1);
define('WRITE', 0x2);
define('READ_WRITE', 0x3);
define('LOCK', 0x4);

define('CLOSEFILE', 0x1);
define('NOTCLOSEFILE', 0x2);

/**
 * @package io
 * @subpackage filesystem
 * @author Benoît Sautel <ben.popeye@phpboost.com> Nicolas Duhamel <akhenathon2@gmail.com>
 * @desc This class represents a text file which can be read and written.
 */
class File extends FileSystemElement
{
	/**
	 * @desc Builds a File object.
	 * @param string $path Path of the file you want to work with.
	 * @param int $mode If you want to open it only to read it, use the flag READ, if it's to write it use the WRITE flag, you also can use the READ_WRITE flag.
	 * @param bool $whenopen If you want to open the file now, use the OPEN_NOW constant, if you want to open it only when you will need it, use the OPEN_AFTER constant.
	 */
	function File($path, $mode = READ_WRITE, $whenopen = OPEN_AFTER)
	{
		parent::FileSystemElement($path);

		$this->mode = $mode;

		if (@file_exists($this->path))
		{
			if (!@is_file($this->path))
			{
				return false;
			}

			if ($whenopen == OPEN_NOW)
			{
				$this->open();
			}
		}
		else if (!@touch($this->path))
		{
			return false;
		}
			
		return true;
	}

	/**
	 * @desc Opens the file. You cannot read or write a closed file, use this method to open it.
	 */
	function open()
	{
		if (!$this->is_open())
		{
			parent::open();
			if (file_exists($this->path) && is_file($this->path))
			{   // The file already exists and is a file (not a folder)
				$this->fd = fopen($this->path, 'r+');
			}
			else if (!file_exists($this->path))
			{   // The file does not exists
				$this->fd = fopen($this->path, 'x+');
			}
				
			if ($this->mode & READ)
			{
				$this->contents = file_get_contents_emulate($this->path);
				$this->lines = explode("\n", $this->contents);
			}
		}
	}

	/**
	 * @desc Returns the content of the file.
	 * @param int $start Byte from which you want to start. 0 if you want to read the file from its begening, 1 to start with the second etc.
	 * @param int $len Number of bytes you want to read.
	 * @return string The read content.
	 */
	function get_contents($start = 0, $len = -1)
	{
		if ($this->mode & READ)
		{
			parent::get();

			if (!$start && $len == -1)
			{
				return $this->contents;
			}
			else if ($len == -1)
			{
				return substr($this->contents, $start);
			}
			else
			{
				return substr($this->contents, $start, $len);
			}
		}
		else
		{
			user_error('File ' . $this->path . ' is not open for read');
		}
	}

	/**
	 * @desc Returns the content of the file grouped by lines.
	 * @param int $start Byte from which you want to start. 0 if you want to read the file from its begening, 1 to start with the second etc.
	 * @param int $len Number of bytes you want to read.
	 * @return string[] The list of the lines of the file.
	 */
	function get_lines($start = 0, $n = -1)
	{
		if ($this->mode & READ)
		{
			parent::get();

			if (!$start && $n == -1)
			{
				return $this->lines;
			}
			else if ($n == -1)
			{
				return array_slice($this->lines, $start);
			}
			else
			{
				return array_slice($this->lines, $start, $n);
			}
		}
		else
		{
			user_error('File ' . $this->path . ' is open in the write only mode, it can\'t be read');
		}
	}

	/**
	 * @desc Writes some text in the file.
	 * @param string $data The text you want to write in the file.
	 * @param bool $what ERASE if you want to erase the file, ADD if you want to write at the end of the file.
	 * @param bool $mode CLOSEFILE if you want to close the file before to write in it, NOTCLOSEFILE otherwise.
	 * @return bool True if it could write, false otherwise.
	 */
	function write($data, $how = ERASE, $mode = CLOSEFILE)
	{
		if ($this->mode & WRITE)
		{
			if (($mode == NOTCLOSEFILE && !is_ressource($this->fd)) || $mode == CLOSEFILE)
			{
				if (!($this->fd = @fopen($this->path, ( $how == ADD ) ? 'a' : 'w')))
				{
					return false;
				}
			}

			$bytes_to_write = strlen($data);
			$bytes_written = 0;
			while ($bytes_written < $bytes_to_write)
			{
				// on écrit par bloc de 4Ko
				$bytes = fwrite($this->fd, substr($data, $bytes_written, 4096));

				if ($bytes === false || $bytes == 0)
				{
					break;
				}

				$bytes_written += $bytes;
			}

			parent::write();

			return $bytes_written == $bytes_to_write;
		}
		else
		{
			user_error('File ' . $this->path . ' is open in the read only mode, it can\'t be written.');
		}
	}

	/**
	 * @desc Closes a file and frees the allocated memory relative to the file.
	 */
	function close()
	{
		$this->contents = '';
		$this->lines = array();

		if (is_resource($this->fd))
		{
			fclose($this->fd);
		}
	}

	/**
	 * @desc Deletes the file.
	 */
	function delete()
	{
		$this->close();

		if (!@unlink($this->path)) // Empty the file if it couldn't delete it
		{
			$this->write('');
		}
		// Clear file stats (@see http://fr3.php.net/clearstatcache for futher informations)
		// TODO clearstatcache(true, $this->path);
	}

	/**
	 * @desc Allows you to know if the file is already open.
	 * @return bool true if the file is open, false if it's closed.
	 */
	function is_open()
	{
		return $this->is_open && is_resource($this->fd);
	}

	/**
	 * @param bool $blocking if true, block the script, if false, non blocking operation
	 * @desc Locks the file (it won't be readable by another thread which could try to access it).
	 */
	function lock($blocking = true)
	{
		if (!$this->is_open())
		{
			$this->open();
		}

		return flock($this->fd, LOCK_EX, $blocking);
	}

	/**
	 * @desc Unlocks a file. The file must have been locked before you call this method.
	 */
	function unlock()
	{
		if (!$this->is_open())
		{
			$this->open();
		}

		return flock($this->fd, LOCK_UN);
	}

	/**
	 * @desc forces a write of all buffered output.
	 */
	function flush()
	{
		if ($this->is_open())
		{
			fflush($this->fd);
		}
	}


	/**
	 * @desc Includes the file. Executes its PHP content here. Equivalent to the PHP include function.
	 * @param bool $once true if you don't want to include it if it has already been included.
	 * @return true if the file has been successfully included
	 */
	function finclude($once = true)
	{
		if ($once)
		{
			return include_once $this->path;
		}
		else
		{
			return include $this->path;
		}
	}

	/**
	 * @desc Requires the file. Executes its PHP content here. Equivalent to the PHP require function.
	 * @param bool $once true if you don't want to include it if it has already been included.
	 * @return true if the file has been successfully included
	 */
	function frequire($once = true)
	{
		if ($once)
		return require_once $this->path;
		return require $this->path;
	}

	/**
	 * @desc Returns the date of the last modification of the file.
	 * @return int The UNIX timestamp corresponding to the last modification date.
	 */
	function get_last_modification_date()
	{
		return filemtime($this->path);
	}

	/**
	 * @desc Returns the last access date of the file.
	 * @return int The UNIX timestamp corresponding to the last access date of the file.
	 */
	function get_last_access_date()
	{
		return filectime($this->path);
	}

	## Private Attributes ##
	/**
	 * @var string[] List of the lines of the file.
	 */
	var $lines = array();

	/**
	 * @var string Content of the file
	 */
	var $contents;

	/**
	 * @var int Open mode
	 */
	var $mode;

	/**
	 * @var File descriptor of the open file.
	 */
	var $fd;
}

?>