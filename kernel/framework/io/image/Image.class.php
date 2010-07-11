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
 */
class Image
{
	private $path;

	function __construct($path)
	{
		$this->path = $path;		
	}
	
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
	
	public function get_size()
	{
		return filesize($this->path);
	}	
	
	function destroy()
	{
		unlink($this->path);
	}
	
}
?>