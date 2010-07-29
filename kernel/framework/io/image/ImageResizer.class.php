<?php
/*##################################################
 *		                   ImageResizer.class.php
 *                            -------------------
 *   begin                : July 11, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

define('KEEP_RATIO', 	1);
define('FORCE_SIZE', 	2);

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc This class allows you to resize images easily.
 * @package {@package}
 */
class ImageResizer
{
	public function resize(Image $image, $width = 0, $height = 0, $directory = '', $option = KEEP_RATIO)
	{
		$this->check_GD_is_enabled();
		
		$height = $this->default_height_for_width($image, $width, $height, $option);
		$width = $this->default_width_for_height($image, $width, $height, $option);
		
		$path = $this->default_path($image, $directory);
		
		$original_picture = $this->create_image_identifier($image);
		$new_picture = $this->create_ressource($image, $width, $height);
		
		imagecopyresampled($new_picture, $original_picture, 0, 0, 0, 0, $width, $height, $image->get_width(), $image->get_height()); 
	
		$this->create_image($image, $new_picture, $path);
	}
	
	private function default_height_for_width(Image $image, $width, $height, $option)
	{
		if ($height == 0 && $width > 0 || $option == KEEP_RATIO)
		{
			return $image->get_height() / ($image->get_width() / $width);
		}
		elseif ($height > 0 || $option == FORCE_SIZE)
		{
			return $height;
		}
	}
	
	private function default_width_for_height(Image $image, $width, $height, $option)
	{
		if ($width == 0 && $height > 0 || $option == KEEP_RATIO)
		{
			return $image->get_width() / ($image->get_height() / $height);
		}
		elseif ($width > 0 || $option == FORCE_SIZE)
		{
			return $width;
		}
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
			case 'image/bmp':
					return imagecreatefrombmp($Image->get_path());
				break;
			// TODO Erreur mime non prise en compte
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
	
	private function extension_news_path($directory)
	{
		$explode = explode('/', $directory);
		$name_and_extension = array_pop($explode);
		$explode = explode('.', $name_and_extension);
		return array_pop($explode);
	}
	
	private function create_image(Image $image, $create_picture, $directory)
	{
	
		$extension = $this->extension_news_path($directory);
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
			case 'bmp':
				return imagebmp($create_picture, $directory);
					break;
			// TODO extension non prise en compte
		}
	}
	
	private function check_GD_is_enabled()
	{
		$server_configuration = new ServerConfiguration();
		if (!$server_configuration->has_gd_libray())
		{
			throw new GDNotAvailableException();
		}
	}
	
}
?>