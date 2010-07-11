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
 * @author Kévib MASSY <soldier.weasel@gmail.com>
 */
class ImageResizer
{
	private $properties_image;
	private $width;
	private $height;
	
	public function __construct()
	{
	
	}
	
	public function resize($properties_image, $width = 0, $height = 0, $directory = false)
	{
		if (empty($width) && !empty($height))
		{
			$width = $properties_image->get_height() / $height;
			$width = $properties_image->get_width() / $width;
		}	
		elseif (!empty($width) && empty($height))
		{
			$height = $properties_image->get_width() / $width;
			$height = $properties_image->get_height() / $height;
		}
		
		if (!$directory)
			$directory = $properties_image->get_path();
		else
			$directory;
			
		switch ($properties_image->get_mime()) {
			case 'image/jpeg':
					$original_picture = imagecreatefromjpeg($properties_image->get_path());
				break;
			case 'image/png':
					$original_picture = imagecreatefrompng($properties_image->get_path());
				break;
			case 'image/gif':
					$original_picture = imagecreatefromgif($properties_image->get_path());
				break;
			// TODO Erreur mime non prise en compte
		}
		
		if($properties_image->get_mime() == 'image/gif')
			$create_picture = imagecreate($width, $height); 
		else
			$create_picture = imagecreatetruecolor($width, $height); 
			
		imagecopyresized($create_picture, $original_picture, 0, 0, 0, 0, $width, $height, $properties_image->get_width(), $properties_image->get_height()); 
	
		switch ($properties_image->get_mime()) {
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