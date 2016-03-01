<?php
/*##################################################
 *		                   Image.class.php
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
 * @desc This class allows you to obtain informations on an image.
 * @package {@package}
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
	 * @desc Return Array value properties of the image 
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
		$filename = $this->get_name_and_extension();
		return strtolower(substr(strrchr($filename, '.'), 1));
	}
	
	public function get_name()
	{
		$filename = $this->get_name_and_extension();
		return substr($filename, 0, strpos($filename, '.'));
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
