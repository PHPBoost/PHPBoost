<?php
/**
 * Represents a HTTP uploaded file
 * @package     IO
 * @subpackage  HTTP\upload
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 01 24
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class UploadedFile
{
	private $name = '';
	private $mime_type = '';
	private $size = 0;
	private $tmp_name = '';

	public function __construct($name, $mime_type, $size, $tmp_name)
	{
		$this->name = $name;
		$this->mime_type = $mime_type;
		$this->size = $size;
		$this->tmp_name = $tmp_name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function get_name_without_extension()
	{
		$name = $this->get_name();
		return TextHelper::substr($name, 0, TextHelper::strpos($name, '.'));
	}

	public function get_extension()
	{
		$filename = $this->get_name();
		return TextHelper::strtolower(TextHelper::substr(TextHelper::strrchr($filename, '.'), 1));
	}

	public function get_mime_type()
	{
		return $this->mime_type;
	}

	public function get_size()
	{
		return $this->size;
	}

	public function get_temporary_filename()
	{
		return $this->tmp_name;
	}

	/**
	 * Saves the uploaded file on the server's filesystem.
	 * @param File $destination The destination file
	 */
	public function save(File $destination)
	{
		move_uploaded_file($this->tmp_name, $destination->get_path());
	}
}
?>
