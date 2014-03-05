<?php
/*##################################################
 *                         BufferedFileReader.class.php
 *                            -------------------
 *   begin                : May 29, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class BufferedFileReader implements FileReader
{
	const DEFAULT_BUFFER_SIZE = 100000;

	/**
	 * @var File
	 */
	private $file;
	private $buffer_max_size;
	private $offset_in_file = 0;
	private $reached_end_of_file = false;
	private $lines = array();

	public function __construct(File $file, $buffer_size = self::DEFAULT_BUFFER_SIZE)
	{
		$this->file = $file;
		$this->buffer_max_size = $buffer_size;
	}

	public function read_all()
	{
		$result = '';
		while (!$this->reached_end_of_file)
		{
			$result .= $this->read_packet();
		}
		return $result;
	}

	private function read_packet()
	{
		$buffer = $this->file->read($this->offset_in_file, $this->buffer_max_size);
		$buffer_size = strlen($buffer);
		$this->offset_in_file += strlen($buffer);
		if ($buffer_size == 0)
		{
			$this->reached_end_of_file = true;
		}
		return $buffer;
	}

	public function read_line()
	{
		if ($this->has_no_more_line())
		{
			return null;
		}
		if ($this->has_buffered_line() || $this->is_last_line())
		{
			return $this->get_oldest_line();
		}
		else
		{
			while (!$this->has_buffered_line() && !$this->reached_end_of_file)
			{
				$this->read_lines_in_new_packet();
			}
			return $this->get_oldest_line();
		}
	}

	private function has_buffered_line()
	{
		// We must have at least two entries because we don't know if the last one is a full line or a partial one.
		return count($this->lines) > 1;
	}

	private function read_lines_in_new_packet()
	{
		$packet = $this->read_packet();
		$lines = explode("\n", $packet);
		$last_partial_line = array_pop($this->lines);
		if ($last_partial_line == null)
		{
			$last_partial_line = '';
		}
		$last_partial_line .= array_shift($lines);
		array_push($this->lines, $last_partial_line);
		$this->lines = array_merge($this->lines, $lines);
	}

	private function is_last_line()
	{
		return $this->reached_end_of_file && count($this->lines) == 1;
	}

	private function has_no_more_line()
	{
		return $this->reached_end_of_file && count($this->lines) == 0;
	}
	
	private function get_oldest_line()
	{
		return array_shift($this->lines);
	}
}
?>