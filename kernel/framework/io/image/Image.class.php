<?php
/*##################################################
 *		                   Image.class.php
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
 * @desc This class allows you to obtain informations on an image.
 * @package {@package}
 */
class Image
{	

	private $path;

	function __construct($path)
	{
		$this->path = $path;		
	}
	
	/**
	 * @desc Return Array value properties of the image 
	 * @return Array properties
	 */
	private function get_properties()
	{
		return getimagesize($this->path);
	}

	public function get_path()
	{
		return $this->path;
	}

	public function get_width()
	{
		$property = $this->get_properties();
		return $property[0];
	}

	public function get_height()
	{
		$property = $this->get_properties();
		return $property[1];
	}
	
	public function get_mime_type()
	{
		$property = $this->get_properties();
		return $property['mime'];
	}
	
	/**
	 * @desc Return Size image
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
		$explode = explode('.', $this->get_name_and_extension());
		return array_pop($explode);
	}
	
	public function get_name()
	{
		$explode = explode('.', $this->get_name_and_extension());
		return $explode[0];
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