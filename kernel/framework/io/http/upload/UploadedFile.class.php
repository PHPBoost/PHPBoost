<?php
/*##################################################
 *                           UploadedFile.class.php
 *                            -------------------
 *   begin                : January 24 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @author Benoit Sautel
 * @desc Represents a HTTP uploaded file
 * @package {@package}
 */
class UploadedFile
{
	private $name = '';
	private $mime_type = '';
	private $size = 0;
	private $tmp_name = '';
	
	public function __construct($name, $mime_type, $size, $tmp_name)
	{
		$this->name = $name;
		$this->mime_type = $mime_type;
		$this->size = $size;
		$this->tmp_name = $tmp_name;
	}
	
	public function get_name()
	{
		return $this->name;
	}
	
	public function get_name_without_extension()
	{
		$name = $this->get_name();
		return substr($name, 0, strpos($name, '.'));
	}

	public function get_extension()
	{
		$filename = $this->get_name();
		return strtolower(substr(strrchr($filename, '.'), 1));
	}
	
	public function get_mime_type()
	{
		return $this->mime_type;
	}
	
	public function get_size()
	{
		return $this->size;
	}
	
	public function get_temporary_filename()
	{
		return $this->tmp_name;
	}
	
	/**
	 * @desc Saves the uploaded file on the server's filesystem.
	 * @param File $destination The destination file 
	 */
	public function save(File $destination)
	{
		move_uploaded_file($this->tmp_name, $destination->get_path());
	}
}
?>