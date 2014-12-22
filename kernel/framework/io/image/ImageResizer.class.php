<?php
/*##################################################
 *		                   ImageResizer.class.php
 *                            -------------------
 *   begin                : July 11, 2010
 *   copyright            : (C) 2010 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 * @desc This class allows you to resize images easily.
 * @package {@package}
 */
class ImageResizer
{
	/**
	 * @throws Exception if the GD extension is not loaded
	 */
	public function resize(Image $image, $width, $height, $directory = '')
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
	
	public function resize_with_max_values(Image $image, $width, $height, $directory = '')
	{
		$coef_width = $image->get_width() / $width;
		$coef_height = $image->get_height() / $height;

		$ratio = max($coef_width,$coef_height);

		$width = $image->get_width() / $ratio;
		$height = $image->get_height() / $ratio;
		
		$this->resize($image, $width, $height, $directory);
	}
	
	public function resize_width(Image $image, $width, $directory)
	{
		$height = $image->get_height() / ($image->get_width() / $width);
		
		$this->resize($image, $width, $height, $directory);
	}
	
	public function resize_height(Image $image, $height, $directory)
	{
		$width = $image->get_width() / ($image->get_height() / $height);
		
		$this->resize($image, $width, $height, $directory);
	}
	
	private function default_path(Image $image, $directory)
	{
		if (empty($directory))
			return $image->get_path();
		else
			return $directory;
	}

	private function create_image_identifier(Image $Image)
	{
		switch ($Image->get_mime_type()) 
		{
			case 'image/jpeg':
					return imagecreatefromjpeg($Image->get_path());
				break;
			case 'image/png':
					return imagecreatefrompng($Image->get_path());
				break;
			case 'image/gif':
					return imagecreatefromgif($Image->get_path());
				break;
			default:
				throw new UnsupportedOperationException($image->get_mime_type() . ' mime type is not supported.');
		}
	}
	
	private function create_ressource(Image $Image, $width, $height)
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
	
	private function extension_new_path($directory)
	{
		$explode = explode('/', $directory);
		$name_and_extension = array_pop($explode);
		$explode = explode('.', $name_and_extension);
		return strtolower(array_pop($explode));
	}
	
	private function create_image(Image $image, $create_picture, $directory)
	{
		$extension = $this->extension_new_path($directory);
		switch ($extension) 
		{
			case 'jpeg':
				return imagejpeg($create_picture, $directory);
					break;
			case 'jpg':
				return imagejpeg($create_picture, $directory);
					break;
			case 'png':
				return imagepng($create_picture, $directory);
					break;
			case 'gif':
				return imagegif($create_picture, $directory);
					break;
			default:
				throw new UnsupportedOperationException($image->get_mime_type() . ' mime type is not supported.');
		}
	}
	
	/**
	 * @throws Exception if the GD extension is not loaded
	 */
	private function assert_gd_extension_is_loaded()
	{
		$server_configuration = new ServerConfiguration();
		if (!$server_configuration->has_gd_library())
		{
			throw new Exception('The GD extension is required but not loaded.');
		}
	}
}
?>