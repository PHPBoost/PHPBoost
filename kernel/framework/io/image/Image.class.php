<?php
/**
 * This class allows you to obtain informations on an image.
 * @package     IO
 * @subpackage  Image
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 16
 * @since       PHPBoost 3.0 - 2010 07 11
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Image extends FileSystemElement
{
	private array|false $properties;

	public function __construct(string $path)
	{
		$this->path = $path;
		$this->properties = $this->get_properties();
	}

	/**
	 * Return Array value properties of the image
	 * @return array properties
	 */
	private function get_properties(): array|false
	{
		return @getimagesize($this->path);
	}

	public function get_path(): string
	{
		return $this->path;
	}

	public function get_width(): int
	{
		return is_array($this->properties) ? $this->properties[0] : 0;
	}

	public function get_height(): int
	{
		return is_array($this->properties) ? $this->properties[1] : 0;
	}

	public function get_mime_type(): ?string
	{
		return is_array($this->properties) ? $this->properties['mime'] : null;
	}

	/**
	 * Return Size image
	 * @return int Size image in bytes
	 */
	public function get_size(): int|false
	{
		return filesize($this->path);
	}

	public function get_name_and_extension(): string
	{
		$explode = explode('/', $this->path);
		return array_pop($explode);
	}

	public function get_extension(): string
	{
		$filename = $this->get_name_and_extension();
		return TextHelper::strtolower(TextHelper::substr(TextHelper::strrchr($filename, '.'), 1));
	}

	public function get_name(): string
	{
		$filename = $this->get_name_and_extension();
		return TextHelper::substr($filename, 0, TextHelper::strpos($filename, '.'));
	}

	public function get_folder_image(): string
	{
		return dirname($this->path);
	}

	function delete(): void
	{
		$file = new File($this->path);
		$file->delete();
	}
}
?>
