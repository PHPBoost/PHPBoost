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
	/**
	 * @var string Path the image.
	 */
	private $path;
	
	/**
	 * @desc Builds a new properties object
	 * @param string Path or is the image.
	 */
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
	
	/**
	 * @desc Path image
	 * @return Path image
	 */
	public function get_path()
	{
		return $this->path;
	}
	
	/**
	 * @desc Width image
	 * @return Width image
	 */
	public function get_width()
	{
		$property = $this->get_properties();
		return $property[0];
	}
	
	/**
	 * @desc Height image
	 * @return Height image
	 */
	public function get_height()
	{
		$property = $this->get_properties();
		return $property[1];
	}
	
	/**
	 * @desc Mime type image
	 * @return Mime type image
	 */
	public function get_mime_type()
	{
		$property = $this->get_properties();
		return $property['mime'];
	}
	
	/**
	 * @desc Return Size image
	 * @return Size image
	 */
	public function get_size()
	{
		return filesize($this->path);
	}
	
	/**
	 * @desc Destroy image
	 */
	function destroy()
	{
		unlink($this->path);
	}
	
}
?>