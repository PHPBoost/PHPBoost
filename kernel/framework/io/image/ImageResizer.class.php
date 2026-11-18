<?php
/**
 * This class allows you to resize images easily.
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

class ImageResizer
{
	/**
	 * @throws Exception if the GD extension is not loaded
	 */
	public function resize(Image $image, int|float $width, int|float $height, string $directory = '')
	{
		$this->assert_gd_extension_is_loaded();

		$path = $this->default_path($image, $directory);

		$original_picture = $this->create_image_identifier($image);
		$new_picture = $this->create_ressource($image, $width, $height);

		imagealphablending($new_picture, false);
		imagesavealpha($new_picture, true);

		imagecopyresampled($new_picture, $original_picture, 0, 0, 0, 0, $width, $height, $image->get_width(), $image->get_height());

		$this->create_image($image, $new_picture, $path);
	}

	public function resize_with_max_values(Image $image, int|float $width, int|float $height, string $directory = ''): void
	{
		$coef_width = $image->get_width() / $width;
		$coef_height = $image->get_height() / $height;

		$ratio = max($coef_width,$coef_height);

		$width = $image->get_width() / $ratio;
		$height = $image->get_height() / $ratio;

		$this->resize($image, $width, $height, $directory);
	}

	public function resize_width(Image $image, int|float $width, string $directory): void
	{
		$height = $image->get_height() / ($image->get_width() / $width);

		$this->resize($image, $width, $height, $directory);
	}

	public function resize_height(Image $image, int|float $height, string$directory): void
	{
		$width = $image->get_width() / ($image->get_height() / $height);

		$this->resize($image, $width, $height, $directory);
	}

	private function default_path(Image $image, string $directory): string
	{
		if (empty($directory))
        {
			return $image->get_path();
        }
		else
        {
			return $directory;
        }
	}

	private function create_image_identifier(Image $Image): \GdImage|false
	{
		switch ($Image->get_mime_type())
		{
			case 'image/jpeg':
                return imagecreatefromjpeg($Image->get_path());
				break;
			case 'image/png':
                return imagecreatefrompng($Image->get_path());
				break;
			case 'image/webp':
                return imagecreatefromwebp($Image->get_path());
				break;
			case 'image/gif':
                return imagecreatefromgif($Image->get_path());
				break;
			default:
				throw new UnsupportedOperationException($Image->get_mime_type() . ' mime type is not supported.');
		}
	}

	private function create_ressource(Image $Image, int|float $width, int|float $height): \GdImage|false
	{
		if ($Image->get_mime_type() == 'image/gif')
		{
			return imagecreate($width, $height);
		}
		else
		{
			return imagecreatetruecolor($width, $height);
		}
	}

	private function extension_new_path(string $directory): string
	{
		$explode = explode('/', $directory);
		$name_and_extension = array_pop($explode);
		$explode = explode('.', $name_and_extension);
		return TextHelper::strtolower(array_pop($explode));
	}

	private function create_image(Image $image, \GdImage $create_picture, string $directory): bool
	{
		$extension = $this->extension_new_path($directory);
		switch ($extension)
		{
			case 'jpeg':
				return imagejpeg($create_picture, $directory);
			case 'jpg':
				return imagejpeg($create_picture, $directory);
			case 'png':
				return imagepng($create_picture, $directory);
			case 'webp':
				return imagewebp($create_picture, $directory);
			case 'gif':
				return imagegif($create_picture, $directory);
			default:
				throw new UnsupportedOperationException($image->get_mime_type() . ' mime type is not supported.');
		}
	}

	/**
	 * @throws Exception if the GD extension is not loaded
	 */
	private function assert_gd_extension_is_loaded(): void
	{
		$server_configuration = new ServerConfiguration();
		if (!$server_configuration->has_gd_extension())
		{
			throw new Exception('The GD extension is required but not loaded.');
		}
	}
}
?>
