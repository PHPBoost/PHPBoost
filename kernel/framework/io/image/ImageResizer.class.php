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

/**
 * @author Kévin MASSY <soldier.weasel@gmail.com>
 * @desc This class allows you to resize images easily.
 * @package {@package}
 */
class ImageResizer
{
	/**
	 * @desc Create image identifier
	 * @param Image $image the element to load
	 * @return Image identifier
	 */
	private function create_image_identifier(Image $image)
	{
		switch ($image->get_mime_type()) 
		{
			case 'image/jpeg':
					return imagecreatefromjpeg($image->get_path());
				break;
			case 'image/png':
					return imagecreatefrompng($image->get_path());
				break;
			case 'image/gif':
					return imagecreatefromgif($image->get_path());
				break;
			// TODO Erreur mime non prise en compte
		}
	}
	
	/**
	 * @desc Create ressource of new picture 
	 * @param Image $image the element to load
	 * @param int $width Width of the new picture create in pixel.
	 * @param int $height Height of the new picture create in pixel.
	 * @return Ressource image
	 */
	private function create_ressource(Image $image, $width = 0, $height = 0)
	{
		if($image->get_mime_type() == 'image/gif')
		{
			return imagecreate($width, $height); 
		}
		else
		{
			return imagecreatetruecolor($width, $height); 
		}
	}
	
	/**
	 * @desc Directory of the create a new image
	 * @param Image $image the element to load
	 * @param string $directory Path of the new image directory
	 * @return Directory image of the create a new image
	 */
	private function directory_new_image (Image $image, $directory)
	{
		if (!$directory)
		{
			return $image->get_path();
		}
		else
		{
			return $directory;
		}
	}
	/**
	 * @desc Resize image
	 * @param Image $image the element to load
	 * @param int $width Width of the new picture create in pixel.
	 * @param int $height Height of the new picture create in pixel.
	 * @param string $directory Path of the new image directory
	 */
	public function resize(Image $image, $width = 0, $height = 0, $directory = false)
	{
		if ($width == 0 && $height > 0)
		{
			$height = $image->get_width() / $width;
			$height = $image->get_height() / $height;
		}
		elseif ($height == 0 && $width > 0)
		{
			$width = $image->get_height() / $height;
			$width = $image->get_width() / $width;
		}
		
		$directory = $this->directory_new_image($image, $directory);

		$original_picture = $this->create_image_identifier($image);
		$create_picture = $this->create_ressource($image, $width, $height);
		
		imagecopyresized($create_picture, $original_picture, 0, 0, 0, 0, $width, $height, $image->get_width(), $image->get_height()); 

		$this->create_image($image, $directory);
	}
	
	/**
	 * @desc Create a new image of the directory
	 * @param Image $image the element to load
	 * @param string $directory Path of the new image directory
	 */
	private function create_image(Image $image, $directory)
	{
		switch ($image->get_mime_type()) 
		{
			case 'image/jpeg':
					imagejpeg($create_picture, $directory);
				break;
			case 'image/png':
					imagepng($create_picture, $directory);
				break;
			case 'image/gif':
					imagegif($create_picture, $directory);
				break;
			// TODO mime non prise en compte
		}
	}

}
?>