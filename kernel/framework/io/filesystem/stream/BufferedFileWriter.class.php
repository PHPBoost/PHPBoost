<?php
/**
 * @package     IO
 * @subpackage  Filesystem\stream
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 05 29
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class BufferedFileWriter implements FileWriter
{
	const DEFAULT_BUFFER_SIZE = 100000;

	/**
	 * @var File
	 */
	private $file;
	private $buffer = '';
	private $buffer_max_size;

	public function __construct(File $file, $buffer_size = self::DEFAULT_BUFFER_SIZE)
	{
		$this->file = $file;
		$this->buffer_max_size = $buffer_size;
	}

	public function append($content)
	{
		if ($this->will_exceed_buffer_size($content))
		{
			$this->flush();
			$this->buffer = $content;
		}
		else
		{
			$this->append_to_buffer($content);
		}
	}

	private function will_exceed_buffer_size($content)
	{
		return TextHelper::strlen($this->buffer) + TextHelper::strlen($content) > $this->buffer_max_size;
	}

	public function flush()
	{
		$this->file->append($this->buffer);

	}

	private function append_to_buffer($content)
	{
		$this->buffer .= $content;
	}
}
?>
