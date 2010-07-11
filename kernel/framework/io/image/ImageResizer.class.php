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
 */
class ImageResizer
{

	private function create_image_identifier($properties_image)
	{
		switch ($properties_image->get_mime_type) {
			case 'image/jpeg':
					return imagecreatefromjpeg($properties_image->get_path());
				break;
			case 'image/png':
					return imagecreatefrompng($properties_image->get_path());
				break;
			case 'image/gif':
					return imagecreatefromgif($properties_image->get_path());
				break;
			// TODO Erreur mime non prise en compte
		}
	}
	
	private function return_ressource($properties_image, $width = 0, $height = 0)
	{
		if($properties_image->get_mime_type == 'image/gif'){
			return imagecreate($width, $height); 
		}
		else{
			return imagecreatetruecolor($width, $height); 
		}
	}

	public function resize($properties_image, $width = 0, $height = 0, $directory = false)
	{
		if ($width == 0 && $height > 0)
		{
			$height = $properties_image->get_width() / $width;
			$height = $properties_image->get_height() / $height;
		}	
		elseif ($height == 0 && $width > 0)
		{
			$width = $properties_image->get_height() / $height;
			$width = $properties_image->get_width() / $width;
		}
		
		if (!$directory){
			$directory = $properties_image->get_path();
		}
		else{
			$directory;
		}

		$original_picture = $this->create_image_identifier($properties_image);
		$create_picture = $this->return_ressource($properties_image, $width, $height);
		
		imagecopyresized($create_picture, $original_picture, 0, 0, 0, 0, $width, $height, $properties_image->get_width(), $properties_image->get_height()); 

		$this->create_image($properties_image, $directory);
	}
	
	private function create_image($properties_image, $directory)
	{
		switch ($properties_image->get_mime_type) {
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