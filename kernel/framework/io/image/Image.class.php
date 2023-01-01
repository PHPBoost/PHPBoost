<?php
/**
 * This class allows you to obtain informations on an image.
 * @package     IO
 * @subpackage  Image
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 28
 * @since       PHPBoost 3.0 - 2010 07 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class Image extends FileSystemElement
{
	private $properties;

	public function __construct($path)
	{
		$this->path = $path;
		$this->properties = $this->get_properties();
	}

	/**
	 * Return Array value properties of the image
	 * @return Array properties
	 */
	private function get_properties()
	{
		return @getimagesize($this->path);
	}

	public function get_path()
	{
		return $this->path;
	}

	public function get_width()
	{
		return is_array($this->properties) ? $this->properties[0] : 0;
	}

	public function get_height()
	{
		return is_array($this->properties) ? $this->properties[1] : 0;
	}

	public function get_mime_type()
	{
		return is_array($this->properties) ? $this->properties['mime'] : null;
	}

	/**
	 * Return Size image
	 * @return Size image in bytes
	 */
	public function get_size()
	{
		return filesize($this->path);
	}

	public function get_name_and_extension()
	{
		$explode = explode('/', $this->path);
		return array_pop($explode);
	}

	public function get_extension()
	{
		$filename = $this->get_name_and_extension();
		return TextHelper::strtolower(TextHelper::substr(TextHelper::strrchr($filename, '.'), 1));
	}

	public function get_name()
	{
		$filename = $this->get_name_and_extension();
		return TextHelper::substr($filename, 0, TextHelper::strpos($filename, '.'));
	}

	public function get_folder_image()
	{
		return dirname($this->path);
	}

	function delete()
	{
		$file = new File($this->path);
		$file->delete();
	}
}
?>
