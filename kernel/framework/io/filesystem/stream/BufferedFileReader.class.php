<?php
/**
 * @package     IO
 * @subpackage  Filesystem\stream
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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
		$buffer_size = TextHelper::strlen($buffer);
		$this->offset_in_file += TextHelper::strlen($buffer);
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
