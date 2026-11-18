<?php
/**
 * Represents a HTTP uploaded file
 * @package     IO
 * @subpackage  HTTP\upload
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 3.0 - 2010 01 24
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class UploadedFile
{
	private string $name = '';
	private string $mime_type = '';
	private int $size = 0;
	private string $tmp_name = '';

	public function __construct(string $name, string $mime_type, int $size, string $tmp_name)
	{
		$this->name = $name;
		$this->mime_type = $mime_type;
		$this->size = $size;
		$this->tmp_name = $tmp_name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function get_name_without_extension(): string
	{
		$name = $this->get_name();
		return TextHelper::substr($name, 0, TextHelper::strpos($name, '.'));
	}

	public function get_extension(): string
	{
		$filename = $this->get_name();
		return TextHelper::strtolower(TextHelper::substr(TextHelper::strrchr($filename, '.'), 1));
	}

	public function get_mime_type(): string
	{
		return $this->mime_type;
	}

	public function get_size(): int
	{
		return $this->size;
	}

	public function get_temporary_filename(): string
	{
		return $this->tmp_name;
	}

	/**
	 * Saves the uploaded file on the server's filesystem.
	 * @param File $destination The destination file
	 */
	public function save(File $destination): void
	{
		move_uploaded_file($this->tmp_name, $destination->get_path());
	}
}
?>
