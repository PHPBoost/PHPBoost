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
 * @package io
 * @subpackage filesystem
 * @author Benoît Sautel <ben.popeye@phpboost.com> Nicolas Duhamel <akhenathon2@gmail.com>
 * @desc This class represents a text file which can be read and written.
 */
class File extends FileSystemElement
{
	private static $READ = 0x1;
	private static $WRITE = 0x2;
	private static $APPEND = 0x3;
	private static $BUFFER_SIZE = 8192;

	/**
	 * @var string[] List of the lines of the file.
	 */
	private $lines = array();
	/**
	 * @var string Content of the file
	 */
	private $contents;
	/**
	 * @var int Open mode
	 */
	private $mode = 0;
	/**
	 * @var File descriptor of the open file.
	 */
	private $fd;

	/**
	 * @desc Builds a File object.
	 * @param string $path Path of the file you want to work with.
	 * @param int $mode If you want to open it only to read it, use the flag File::READ, if it's to write it use the File::WRITE flag, you also can use the File::READ_WRITE flag.
	 * @param bool $whenopen If you want to open the file now, use the File::DIRECT_OPENING constant, if you want to open it only when you will need it, use the File::LAZY_OPENING constant.
	 */
	public function __construct($path)
	{
		parent::__construct($path);
	}

	/**
	 * @desc Opens the file. You cannot read or write a closed file, use this method to open it.
	 * @throws IOException If the file can neither been read nor created.
	 */
	protected function open($mode)
	{
		if ($this->mode != $mode)
		{
			$this->close();
			$this->mode = $mode;
			switch ($this->mode)
			{
				case self::$APPEND:
					$this->fd = @fopen($this->path, 'a+b');
					break;
				case self::$WRITE:
					$this->fd = @fopen($this->path, 'w+b');
					break;
				case self::$READ:
				default:
					$this->fd = @fopen($this->path, 'rb');
					break;
			}
			if ($this->fd === false)
			{
				throw new IOException('Can neither open nor create the file ' . $this->path);
			}
		}
	}

	/**
	 * @desc Returns the content of the file.
	 * @param int $start Byte from which you want to start. 0 if you want to read the file from its begening, 1 to start with the second etc.
	 * @param int $len Number of bytes you want to read.
	 * @return string The read content.
	 */
	public function read($start = 0, $len = -1)
	{
		$this->open(self::$READ);

		if ($start > 0)
		{
			fseek($this->fd, $start);
		}
		if ($len == -1)
		{
			$len = filesize($this->get_path());
		}

		$content = '';
		while (!feof($this->fd) && $len > 0)
		{
			$content .= fread($this->fd, min($len, self::$BUFFER_SIZE));
			$len -= self::$BUFFER_SIZE;
		}

		return $content;
	}

	/**
	 * @desc Returns the content of the file grouped by lines.
	 * @return string[] The list of the lines of the file.
	 */
	public function read_lines()
	{
		return explode("\n", $this->read());
	}

	/**
	 * @desc Writes some text in the file. Erases the file previous content
	 * @param string $data The text you want to write in the file.
	 * @throws IOException If it's not possible to write the file
	 */
	public function write($data)
	{
		$this->open(self::$WRITE);
		$this->write_data($data);
	}

	/**
	 * @desc Appends some text at the end of the file.
	 * @param string $data The text you want to write in the file.
	 * @throws IOException If it's not possible to write the file
	 */
	public function append($data)
	{
		$this->open(self::$APPEND);
		$this->write_data($data);
	}

	private function write_data($data)
	{
		$bytes_to_write = strlen($data);
		$bytes_written = 0;
		while ($bytes_written < $bytes_to_write)
		{
			$bytes = fwrite($this->fd, substr($data, $bytes_written, 4096));
			if ($bytes === false || $bytes == 0)
			{
				break;
			}

			$bytes_written += $bytes;
		}
	}

	/**
	 * @desc Closes a file and frees the allocated memory relative to the file.
	 */
	public function close()
	{
		if (is_resource($this->fd))
		{
			$this->mode = 0;
			fclose($this->fd);
		}
	}

	/**
	 * @desc Deletes the file.
	 * @throws IOException if the file cannot been deleted
	 */
	public function delete()
	{
		$this->close();

		if (file_exists($this->path) && !@unlink($this->path)) // Empty the file if it couldn't delete it
		{
			$this->write('');
			throw new IOException('The file ' . $this->path . ' couldn\'t been deleted');
		}
	}

	/**
	 * @desc Allows you to know if the file is already open.
	 * @return bool true if the file is open, false if it's closed.
	 */
	public function is_open()
	{
		return is_resource($this->fd);
	}

	/**
	 * @param bool $blocking if true, block the script, if false, non blocking operation
	 * @desc Locks the file (it won't be readable by another thread which could try to access it).
	 * @throws IOException if the file cannot been locked
	 */
	public function lock($blocking = true)
	{
		if (!$this->is_open())
		{
			$this->open();
		}

		if (!@flock($this->fd, LOCK_EX, $blocking))
		{
			throw new IOException('The file ' . $this->path . ' couldn\'t been locked');
		}
	}

	/**
	 * @desc Unlocks a file. The file must have been locked before you call this method.
	 * @throws IOException if the file cannot been unlocked
	 */
	public function unlock()
	{
		if (!$this->is_open())
		{
			$this->open();
		}

		if (!@flock($this->fd, LOCK_UN))
		{
			throw new IOException('The file ' . $this->path . ' couldn\'t been unlocked');
		}
	}

	/**
	 * @desc Forces the system to write all the buffered output.
	 */
	public function flush()
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
	public function finclude($once = true)
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
	public function frequire($once = true)
	{
		if ($once)
		{
			return require_once $this->path;
		}
		else
		{
			return require $this->path;
		}
	}

	/**
	 * @desc Returns the date of the last modification of the file.
	 * @return int The UNIX timestamp corresponding to the last modification date.
	 */
	public function get_last_modification_date()
	{
		return filemtime($this->path);
	}

	/**
	 * @desc Returns the last access date of the file.
	 * @return int The UNIX timestamp corresponding to the last access date of the file.
	 */
	public function get_last_access_date()
	{
		return filectime($this->path);
	}
}

?>