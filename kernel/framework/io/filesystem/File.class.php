<?php
/*##################################################
 *                               File.class.php
 *                            -------------------
 *   begin                : July 06, 2008
 *   copyright            : (C) 2008 Nicolas Duhamel, Benoit Sautel, Loic Rouchon
 *   email                : akhenathon2@gmail.com, ben.popeye@phpboost.com, loic.rouchon@phpboost.com
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
 * @author Benoît Sautel <ben.popeye@phpboost.com> Nicolas Duhamel <akhenathon2@gmail.com>
 * @desc This class represents a text file which can be read and written.
 */
class File extends FileSystemElement
{
	const READ = 0x1;
	const WRITE = 0x2;
	const APPEND = 0x3;
	private static $BUFFER_SIZE = 8192;
	
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
	private $file_descriptor;

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

	public function __destruct()
	{
		$this->close();
	}
	
	/**
	 * @desc Returns the element name without extension.
	 * @return string The element name without extension.
	 */
	public function get_name_without_extension()
	{
		$name = $this->get_name();
		return substr($name, 0, strpos($name, '.'));
	}
	
	/**
	 * @desc Returns the extension of the element.
	 * @return string Element extension.
	 */
	public function get_extension()
	{
		$name = $this->get_name();
		return substr(strrchr($name,'.'), 1);
	}
	
	/**
	 * @desc Returns the content of the file.
	 * @param int $start Byte from which you want to start. 0 if you want to read the file from its begening, 1 to start with the second etc.
	 * @param int $len Number of bytes you want to read.
	 * @return string The read content.
	 */
	public function read($start = 0, $len = -1)
	{
		$opened_by_me = $this->open(self::READ);

		fseek($this->file_descriptor, $start);

		if ($len == -1)
		{
			$len = filesize($this->get_path());
		}

		$content = '';
		while (!feof($this->file_descriptor) && $len > 0)
		{
			$content .= fread($this->file_descriptor, min($len, self::$BUFFER_SIZE));
			$len -= self::$BUFFER_SIZE;
		}
		if ($opened_by_me)
		{
			$this->close();
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
		$opened_by_me = $this->open(self::WRITE);
		$this->write_data($data);
		if ($opened_by_me)
		{
			$this->close();
		}
	}

	/**
	 * @desc Appends some text at the end of the file.
	 * @param string $data The text you want to write in the file.
	 * @throws IOException If it's not possible to write the file
	 */
	public function append($data)
	{
		$opened_by_me = $this->open(self::APPEND);
		$this->write_data($data);
		if ($opened_by_me)
		{
			$this->close();
		}
	}

	/**
	 * @desc empty the file
	 */
	public function erase()
	{
		$opened_by_me = $this->open(self::WRITE);
		ftruncate($this->file_descriptor, 0);
		if ($opened_by_me)
		{
			$this->close();
		}
	}

	/**
	 * @desc Closes a file and frees the allocated memory relative to the file.
	 */
	public function close()
	{
		if ($this->is_open())
		{
			$this->mode = 0;
			fclose($this->file_descriptor);
			$this->file_descriptor = null;
		}
	}

	/**
	 * @desc Deletes the file.
	 * @throws IOException if the file cannot been deleted
	 */
	public function delete()
	{
		$this->close();
		if (file_exists($this->get_path())) {
			if (!unlink($this->get_path()))
			{
				// Empty the file if it couldn't delete it
				$this->erase();
				throw new IOException('The file ' . $this->get_path()  . ' couldn\'t been deleted');
			}
		}
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
			throw new IOException('The file ' . $this->get_path() . ' should be opened before trying to lock it');
		}
		$this->open(self::WRITE);
		$success = @flock($this->file_descriptor, LOCK_EX, $blocking);
		/*
		if (!$success)
		{
			throw new IOException('The file ' . $this->get_path() . ' couldn\'t been locked');
		}
		*/
	}

	/**
	 * @desc Unlocks a file. The file must have been locked before you call this method.
	 * @throws IOException if the file cannot been unlocked
	 */
	public function unlock()
	{
		if (!$this->is_open())
		{
			throw new IOException('The file ' . $this->get_path() . ' should be opened before trying to unlock it');
		}
		$this->open(self::WRITE);
		$succeed = @flock($this->file_descriptor, LOCK_UN);
		/*if (!$succeed)
		{
			throw new IOException('The file ' . $this->get_path() . ' couldn\'t been unlocked');
		}
		*/
	}

	/**
	 * @desc Forces the system to write all the buffered output.
	 */
	public function flush()
	{
		if ($this->is_open())
		{
			fflush($this->file_descriptor);
		}
	}

	/**
	 * @desc Returns the date of the last modification of the file.
	 * @return int The UNIX timestamp corresponding to the last modification date.
	 */
	public function get_last_modification_date()
	{
		return filemtime($this->get_path());
	}

	/**
	 * @desc Returns the last access date of the file.
	 * @return int The UNIX timestamp corresponding to the last access date of the file.
	 */
	public function get_last_access_date()
	{
		return filectime($this->get_path());
	}

	/**
	 * @desc Returns the size of the file.
	 * @return int The size of the file in bytes.
	 */
	public function get_file_size()
	{
		return (int)@filesize($this->get_path());
	}

	/**
	 * @desc Opens the file. You cannot read or write a closed file, use this method to open it.
	 * @throws IOException If the file can neither been read nor created.
	 */
	public function open($mode)
	{
		if ($this->mode != $mode)
		{
			$this->close();
			$this->mode = $mode;
			switch ($this->mode)
			{
				case self::APPEND:
					$this->file_descriptor = @fopen($this->get_path(), 'a+b');
					$this->check_file_descriptor('Can\'t open the file for creating / writing');
					break;
				case self::WRITE:
					$this->file_descriptor = @fopen($this->get_path(), 'w+b');
					$this->check_file_descriptor('Can\'t open the file for creating / writing');
					break;
				case self::READ:
				default:
					$this->file_descriptor = @fopen($this->get_path(), 'rb');
					$this->check_file_descriptor('Can\'t open the file for reading');
					break;
			}
			return true;
		}
		return false;
	}

	/**
	 * @desc Allows you to know if the file is already open.
	 * @return bool true if the file is open, false if it's closed.
	 */
	private function is_open()
	{
		return is_resource($this->file_descriptor);
	}

	private function write_data($data)
	{
		$bytes_to_write = strlen($data);
		$bytes_written = 0;
		while ($bytes_written < $bytes_to_write)
		{
			$bytes = fwrite($this->file_descriptor, substr($data, $bytes_written, self::$BUFFER_SIZE));
			if ($bytes === false || $bytes == 0)
			{
				break;
			}
			$bytes_written += $bytes;
		}
	}

	private function check_file_descriptor($message)
	{
		if ($this->file_descriptor === false || $this->file_descriptor === null)
		{
			throw new IOException($message . ' : ' . $this->get_path());
		}
	}
}
?>