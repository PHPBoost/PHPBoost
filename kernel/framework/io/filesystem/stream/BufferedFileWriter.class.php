<?php
/**
 * @package     IO
 * @subpackage  Filesystem\stream
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 3.0 - 2010 05 29
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BufferedFileWriter implements FileWriter
{
	const DEFAULT_BUFFER_SIZE = 100000;

	private File $file;
	private string $buffer = '';
	private int $buffer_max_size;

	public function __construct(File $file, int $buffer_size = self::DEFAULT_BUFFER_SIZE)
	{
		$this->file = $file;
		$this->buffer_max_size = $buffer_size;
	}

	public function append(string $content): void
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

	private function will_exceed_buffer_size(string $content): bool
	{
		return TextHelper::strlen($this->buffer) + TextHelper::strlen($content) > $this->buffer_max_size;
	}

	public function flush(): void
	{
		$this->file->append($this->buffer);

	}

	private function append_to_buffer(string $content): void
	{
		$this->buffer .= $content;
	}
}
?>
